<?php

	// Responsável pela interação com as rotas.
	class awk_router_driver {
		// Armazena a pilha de execução do driver.
		// @type array<awk_router_driver_stack>;
		private $stacks = [];

		// Armazena o index de execução do driver.
		// @type int;
		private $stack_index = -1;

		// Indica se a pilha de execução do driver já foi iniciada.
		// @type boolean;
		private $stack_processing = false;

		/** CONSTRUCT */
		// Constrói um novo driver, definindo a rota inicial.
		public function __construct($url, $module_instance) {
			// Define a URL Array da próxima pilha de execução, eliminando partes vazias.
			$stack_next = $this->get_stack_next();
			$stack_next->module_instance = $module_instance;
			$stack_next->url_array = array_filter(explode("/", $url), "strlen");
		}

		/** STACK */
		// Obtém a próxima pilha de processamento.
		private function get_stack_next() {
			// Se a stack já foi gerada, apenas retorna.
			// Caso contrário será necessário iniciá-la.
			$stack_next_index = $this->stack_index + 1;
			if(isset($this->stacks[$stack_next_index])) {
				return $this->stacks[$stack_next_index];
			}

			// Se não for a primeira pilha (zero), então clona a pilha anterior.
			// Será necessário reiniciar alguns valores.
			if($stack_next_index !== 0) {
				$stack_current = $this->get_stack();

				$stack_next_instance = clone $stack_current;
				$stack_next_instance->stack_parent = $stack_current;
				$stack_next_instance->reset();

				return $this->stacks[$stack_next_index] = $stack_next_instance;
			}

			// Inicia e retorna a próxima stack.
			return $this->stacks[$stack_next_index] = new awk_router_driver_stack;
		}

		// Obtém a pilha de processamento atual.
		private function get_stack() {
			return $this->stacks[$this->stack_index];
		}

		// Resolve a stack atual.
		private function stack_solver() {
			// Carrega a pilha atual.
			$stack_current = $this->get_stack();

			// Obtém todas as rotas definidas no roteador atual.
			// Será necessário testar uma a uma, até encontrar uma que possa ser resolvida.
			$router_routes = $stack_current->router_instance->get_routes();
			foreach($router_routes as $router_route) {
				// Verifica se a rota atual pode ser resolvida.
				// Se puder, seu callback será executado.
				if($router_route->match($stack_current->url_array, $output_args, $output_attrs, $url_array_index)) {
					// Armazena a rota que processou a stack.
					$stack_current->stack_route = $router_route;

					// Armazena os atributos capturados no driver.
					$stack_current->url_attrs = $output_attrs;

					// Define quantas partes da URL Array foram processadas pela rota.
					$stack_current->url_array_index = $url_array_index;

					// Executa o callback da rota e finaliza o processo.
					$this->callback_execute($router_route->get_callback(), $output_args);

					// Se houve uma invalidação, continua o processamento de rotas.
					if(in_array("invalidated", $stack_current->stack_status)) {
						$stack_current->reset();
						continue;
					}

					// Indica que a rota atual foi processada com sucesso.
					$stack_current->url_processed = true;
					$stack_current->stack_status[] = "accepted";
					return;
				}
			}
		}

		// Inicia o processo nas stacks existentes.
		private function stack_process() {
			// Se o processo já foi iniciado, então ignora um reprocesso.
			if($this->stack_processing === true) {
				return;
			}

			// Processa as pilhas existentes.
			$this->stack_processing = true;
			$this->stack_index = 0;
			while($this->stack_index < count($this->stacks)) {
				$this->stack_solver();
				$this->stack_index++;
			}

			// Verifica se a pilha atual processou corretamente uma URL, \
			// isso é, se a URL foi direcionada a uma rota.
			// Se isso não aconteceu, então será forçado um Erro 404.
			$stack_last = $this->stacks[$this->stack_index - 1];
			if($stack_last->url_processed === false) {
				awk_error::force_404();
			}
		}

		/** PRESERVE */
		// Indica que a URL deverá ser preservada (não sofrer slice) \
		// após iniciar a próxima stack.
		public function preserve_url($preserve = null) {
			$this->get_stack()->url_array_preserve = $preserve !== false;
		}

		/** REDIRECT */
		// Redireciona para um roteador através da id.
		public function redirect($router_id) {
			// Define o roteador da próxima pilha de execução.
			$stack_next = $this->get_stack_next();

			// Carrega a definição do roteador.
			$router_instance = $stack_next->module_instance->identify($router_id, "router");
			$stack_next->module_instance = $router_instance->get_module();
			$stack_next->router_instance = $router_instance;

			// Inicia o processamento de pilhas.
			$this->stack_process();
		}

		/** INVALIDATE */
		// Determina que a rota atual é inválida e permite o avanço para a próxima rota.
		public function invalidate() {
			return $this->get_stack()->stack_status[] = "invalidated";
		}

		/** ATTR */
		// Retorna um valor de um atributo capturado na pilha atual.
		public function get_attr($key) {
			return $this->get_stack()->url_attrs[$key];
		}

		/** CALLBACK */
		// Executa uma callback e retorna o status da operação.
		private function callback_execute($callback, $callback_args = null) {
			// Se a callback for uma string, é necessário identificá-la.
			if(is_string($callback)) {
				$stack_instance = $this->get_stack();
				$callback_parts = $stack_instance->module_instance->identify($callback, "router", null, null, true);

				// Gera um callback a depender do tipo retornado.
				switch($callback_parts["feature"]) {
					// Identifica um redirecionamento de rota.
					case "router":
						$callback = function($driver) use($callback) { $driver->redirect($callback); };
						break;

					// Identifica uma view a ser impressa.
					case "view":
						$callback = function($driver) use($callback_parts) {
							$callback_parts["module"]->view($callback_parts["name"]);
						};
						break;
				}
			}

			// O driver é o primeiro argumento do callback.
			$callback_args = $callback_args ?: [];
			array_unshift($callback_args, $this);

			// Executa o callback.
			call_user_func_array($callback, $callback_args);
		}
	}
