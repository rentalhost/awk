<?php

	// Responsável pelo controle de arquivos públicos.
	class awk_public extends awk_module_base {
		static protected $feature_type = "public";

		// Armazena se o caminho do arquivo remete a um arquivo acessível.
		// @type boolean;
		private $exists = false;

		/** LOAD */
		// Carrega a definição do arquivo e retorna.
		// @return self;
		public function load($public_name) {
			$this->name = $public_name;
			$this->path = $this->module->get_path() . "/publics/" . strtok($public_name, "?");
			$this->exists = is_readable($this->path);
		}

		/** URL */
		// Obtém uma URL de acesso ao arquivo.
		// return @string;
		public function get_url($include_baseurl = null) {
			return ( $include_baseurl !== false ? awk_router::get_baseurl()  : null )
				. $this->module->get_name() . "/publics/" . $this->name;
		}

		/** PROPRIEDADES */
		// Retorna se o arquivo existe.
		// @return boolean;
		public function exists() {
			return $this->exists;
		}
	}
