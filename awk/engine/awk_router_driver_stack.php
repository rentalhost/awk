<?php

	// Responsável pela pilha de execução de um driver.
	class awk_router_driver_stack {
		// Armazena a pilha anterior.
		// @type self;
		public $stack_parent;

		// Armazena o status gerado na stack.
		// @type array<string>;
		public $stack_status = [];

		// Armazena a URL Array da stack.
		// @type array<string>;
		public $url_array;

		// Armazena quantas partes da URL Array foram processadas.
		// Este argumento será levado em conta ao iniciar o slice.
		// @type int;
		public $url_array_index = 0;

		// Indica que a URL Array será preservada e não sofrerá slice.
		// @type boolean;
		public $url_array_preserve = false;

		// Indica se a URL foi processada por uma rota.
		// @type boolean;
		public $url_processed = false;

		// Armazena a instância do módulo responsável pela stack.
		// @type awk_module;
		public $module_instance;

		// Armazena a instância do roteador responsável pela stack.
		// @type awk_router;
		public $router_instance;

		/** RESET */
		// Reinica alguns parâmetros da pilha.
		public function reset() {
			// Se não for necessário preservar, processa a URL Array. \
			// Será levado em consideração a pilha anterior.
			if($this->stack_parent
			&& $this->stack_parent->url_array_preserve === false
			&& $this->stack_parent->url_array_index !== 0) {
				$this->url_array = array_slice($this->stack_parent->url_array, $this->stack_parent->url_array_index);
			}

			// Reseta alguns parâmetros.
			$this->stack_status = [];
			$this->url_array_index = 0;
			$this->url_array_preserve = false;
			$this->url_processed = false;
		}
	}
