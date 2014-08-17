<?php

	// Responsável por um model.
	class Awk_Model extends Awk_Module_Base {
		static protected $feature_type = "model";

		// Armazena a base da tabela.
		// @type Awk_Model.
		private $model_base;

		// Armazena o prefixo da tabela.
		// @type string;
		private $table_prefix;

		// Armazena o nome da tabela.
		// @type string;
		private $table_name;

		/** LOAD */
		// Carrega o model e o retorna.
		// @return self;
		public function load($model_name) {
			$this->name = $model_name;
			$this->path = $this->module->get_path() . "/models/{$this->name}.php";

			// Se o arquivo do model não existir, lança um erro.
			if(!is_readable($this->path)) {
				Awk_Error::create([
					"message" => "O módulo \"" . $this->module->get_name() . "\" não possui o model \"{$this->name}\"."
				]);
			} // @codeCoverageIgnore

			// Carrega o arquivo do model.
			$this->module->include_clean($this->path, [ "model" => $this ]);
		}

		/** BASE */
		// Define a base da tabela.
		public function set_base($table_base) {
			$this->model_base = $this->get_module()->identify($table_base, "model", true);
		}

		/** PREFIX */
		// Define o prefixo da tabela.
		public function set_prefix($table_prefix) {
			$this->table_prefix = $table_prefix;
		}

		// Obtém o prefixo da tabela.
		public function get_prefix() {
			// Se um model base foi informado, retorna baseado em seu prefixo.
			if($this->model_base) {
				return $this->model_base->get_prefix() . $this->table_prefix;
			}

			// Caso contrário, retorna o próprio prefixo.
			return $this->table_prefix;
		}

		/** TABLE */
		// Define o nome da tabela.
		public function set_table($table_name) {
			$this->table_name = $this->get_prefix() . $table_name;
		}

		// Obtém o nome da tabela.
		public function get_table() {
			return $this->table_name;
		}
	}
