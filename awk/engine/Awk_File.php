<?php

	// Responsável pelo controle de arquivos internos.
	class Awk_File extends Awk_Module_Base {
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
			$this->exists = file_exists($this->path);
		}

		/** DIR */
		// Obtém os arquivos de um diretório, eliminando dots.
		public function get_files($include_dirs = null) {
			$opendir = opendir($this->path);
			$current_path = $this->get_path();

			while($file = readdir($opendir)) {
				// Ignora dots.
				if($file === "."
				|| $file === "..") {
					continue;
				}

				// Se houver necessidade, pode omitir diretórios.
				if($include_dirs === false
				&& is_dir("{$current_path}/{$file}")) {
					continue;
				}

				yield "{$current_path}/{$file}";
			}
		}

		/** PROPRIEDADES */
		// Retorna se o arquivo existe.
		// @return boolean;
		public function exists() {
			return $this->exists;
		}
	}
