<?php

	/**
	 * Responsável pelo controle de arquivos privados.
	 */
	class Awk_Private extends Awk_Module_Base {
		/**
		 * Define o tipo de recurso.
		 * @var string
		 */
		static protected $feature_type = "private";

		/**
		 * Armazena se o caminho do arquivo remete a um arquivo acessível.
		 * @var boolean
		 */
		private $exists = false;

		/**
		 * Carrega a definição do arquivo e retorna.
		 * @param  string $private_name Identificador do arquivo privado.
		 */
		public function load($private_name) {
			$this->name = $private_name;
			$this->path = $this->module->get_path() . "/privates/" . $private_name;
			$this->exists = file_exists($this->path);
		}

		/**
		 * Obtém os arquivos de um diretório, eliminando dots.
		 * @param  boolean $include_dirs Se deve incluir os diretórios.
		 */
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

		/**
		 * Retorna se o arquivo existe.
		 * @return boolean
		 */
		public function exists() {
			return $this->exists;
		}
	}
