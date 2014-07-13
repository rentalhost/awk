<?php

	// Responsável pela interação com as rotas.
	class awk_router_driver {
		// Define o módulo que está sendo processado pelo driver.
		// @type awk_module;
		private $current_module;

		// Define a URL que está sendo processada pelo driver.
		// @type array<string>;
		private $url_array;

		// Armazena o status da operação.
		// @type string;
		private $callback_status;

		/** CONSTRUCT */
		// Constrói um novo driver.
		public function __construct($url) {
			// Divide a URL em um array, eliminando espaços vazios.
			// Ex. "1//2//3" => ["1", "2", "3"]
			$this->url_array = array_filter(explode("/", $url), "strlen");
		}

		/** REDIRECT */
		// Redireciona para um módulo.
		public function redirect_module($module_id, $router_id = "index") {
			$this->current_module = awk_module::get($module_id);
			$this->redirect($router_id);
		}

		// Redireciona para um roteador do mesmo módulo.
		public function redirect($router_id) {
			$current_router = $this->current_module->router($router_id);
			$this->solve($current_router);
		}

		/** SOLVE */
		// Resolve a URL atual no roteador.
		private function solve($current_router) {
			// Obtém todas as rotas definidas no roteador atual.
			// Será necessário testar uma a uma, até encontrar uma que possa ser resolvida.
			$router_routes = $current_router->get_routes();
			foreach($router_routes as $router_route) {
				// Verifica se a rota atual pode ser resolvida.
				// Se puder, seu callback será executado.
				if($router_route->match($this->url_array, $callback_args)) {
					$this->callback_execute($router_route->get_callback(), $callback_args);

					// Se tudo ocorreu bem, finaliza a execução.
					return;
				}
			}

			// Verifica se há um fallback.
			// Se houver, ele será executado com o driver e finalizará a resolução.
			$router_fallback = $current_router->get_fallback();
			if($router_fallback) {
				$this->callback_execute($router_fallback);
				return;
			}

			// Se o driver falhar, então força um erro de objeto não encontrado.
			awk_error::force_404();
		}

		/** CALLBACK */
		// Executa uma callback e retorna o status da operação.
		private function callback_execute($callback, $callback_args = null) {
			$this->callback_status = null;

			// O driver é o primeiro argumento do callback.
			$callback_args = $callback_args ?: [];
			array_unshift($callback_args, $this);

			// Executa o callback.
			call_user_func_array($callback, $callback_args);
		}
	}
