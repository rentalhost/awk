<?php

	// Responsável pelo controle de arquivos internos.
	class awk_file extends awk_module_base {
		static protected $feature_type = "file";

		// Armazena se o caminho do arquivo remete a um arquivo acessível.
		// @type boolean;
		private $exists = false;

		/** LOAD */
		// Carrega a definição do arquivo e retorna.
		// @return self;
		public function load($file_name) {
			$this->name = $file_name;
			$this->path = $this->module->get_path() . "/files/" . $file_name;
			$this->exists = is_readable($this->path);
		}

		/** PROPRIEDADES */
		// Retorna se o arquivo existe.
		// @return boolean;
		public function exists() {
			return $this->exists;
		}
	}
