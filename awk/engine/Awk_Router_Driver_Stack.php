<?php

	/**
	 * Responsável pela pilha de execução de um driver.
	 */
	class Awk_Router_Driver_Stack {
		/**
		 * Armazena a pilha anterior.
		 * @var self
		 */
		public $stack_parent;

		/**
		 * Armazena a rota que a stack processou.
		 * @var Awk_Router_Route
		 */
		public $stack_route;

		/**
		 * Armazena o status gerado na stack.
		 * @var string[]
		 */
		public $stack_status = [];

		/**
		 * Armazena a URL Array da stack.
		 * @var string[]
		 */
		public $url_array;

		/**
		 * Armazena quantas partes da URL Array foram processadas.
		 * Este argumento será levado em conta ao iniciar o slice.
		 * @var integer
		 */
		public $url_array_index = 0;

		/**
		 * Indica que a URL Array será preservada e não sofrerá slice.
		 * @var boolean
		 */
		public $url_array_preserve = false;

		/**
		 * Indica se a URL foi processada por uma rota.
		 * @var boolean
		 */
		public $url_processed = false;

		/**
		 * Armazena os atributos capturados da URL.
		 * @var mixed[]
		 */
		public $url_attrs = [];

		/**
		 * Armazena a instância do módulo responsável pela stack.
		 * @var Awk_Module
		 */
		public $module_instance;

		/**
		 * Armazena a instância do roteador responsável pela stack.
		 * @var Awk_Router
		 */
		public $router_instance;

		/**
		 * Reinica alguns parâmetros da pilha.
		 */
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
