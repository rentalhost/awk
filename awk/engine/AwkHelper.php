<?php

	// Responsável pelo modelo de dados do helper.
	class AwkHelper extends AwkModuleBase {
		static protected $feature_type = "helper";

		/** HELPER */
		// Armazena os métodos registrados para o helper.
		// @type array<string, callback>;
		private $methods = [
		];

		/** LOAD */
		// Carrega o helper e o retorna.
		// @return self;
		public function load($helper_name) {
			$this->name = $helper_name;
			$this->path = $this->module->get_path() . "/helpers/{$this->name}.php";

			// Se o arquivo do helper não existir, lança um erro.
			if(!is_readable($this->path)) {
				AwkError::create([
					"message" => "O módulo \"" . $this->module->get_name() . "\" não possui o helper \"{$this->name}\"."
				]);
			} // @codeCoverageIgnore

			// Carrega o arquivo do helper.
			$this->module->include_clean($this->path, [ "helper" => $this ]);
		}

		/** ADD */
		// Adiciona um novo helper.
		public function add($method, $callback) {
			$this->methods[$method] = $callback;
		}

		/** CALL */
		// Chama um método do helper.
		// @param string $method: nome do método a ser chamado;
		// @param optional ...mixed $method_args: argumentos que serão enviados ao helper;
		// @return mixed;
		public function call($method) {
			return $this->call_array($method, array_slice(func_get_args(), 1));
		}

		// Chama um método do helper passando um array como argumento.
		// @param string $method: nome do método a ser chamado;
		// @param array<mixed> $method_args: argumentos que serão enviados ao helper;
		// @return mixed;
		public function call_array($method, $method_args) {
			return call_user_func_array($this->methods[$method], $method_args);
		}
	}
