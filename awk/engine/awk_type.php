<?php

	// Responsável pelo modelo de dados do type.
	class awk_type extends awk_module_base {
		static protected $feature_type = "type";

		// Armazena o validador de tipo.
		// @type callback;
		private $validate_callback;

		// Armazena o transformador de tipo.
		// @type callback;
		private $transform_callback;

		/** LOAD */
		// Carrega o type e o retorna.
		// @return self;
		public function load($type_name) {
			$this->name = $type_name;
			$this->path = $this->module->get_path() . "/types/{$this->name}.php";

			// Se o arquivo do type não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"message" => "O módulo \"" . $this->module->get_name() . "\" não possui o tipo \"{$this->name}\"."
				]);
			}

			// Carrega o arquivo do type.
			$this->module->include_clean($this->path, [ "type" => $this ]);
		}

		/** VALIDATE */
		// Define o validador do tipo.
		public function set_validate($callback) {
			$this->validate_callback = $callback;
		}

		// Executa um teste de validação.
		// @note Retornará true caso não haja um método de validação definido.
		public function validate($value) {
			if($this->validate_callback) {
				return call_user_func($this->validate_callback, $value);
			}

			return true;
		}

		/** TRANSFORM */
		// Define o transformador do tipo.
		public function set_transform($callback) {
			$this->transform_callback = $callback;
		}

		// Executa uma transformação.
		// @note Retornará o mesmo valor caso não haja um método de transformação definido.
		public function transform($value) {
			if($this->transform_callback) {
				return call_user_func($this->transform_callback, $value);
			}

			return $value;
		}
	}
