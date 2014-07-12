<?php

	// Responsável pela definição de configurações.
	class awk_settings extends awk_base {
		// Armazena o path de sobreposição de configurações.
		// @type string;
		private $overwrite_path;

		// Armazena se o caminho de sobreposição existe.
		// @type boolean;
		private $overwrite_exists;

		/** SETTINGS */
		// Armazena as configurações.
		// @type array<string, mixed>;
		private $settings = [
		];

		/** LOAD */
		// Carrega as settings e o retorna.
		// @return self;
		public function load() {
			$this->path = $this->module->get_path() . "/settings.php";

			// Para ser um módulo válido, é esperado que o arquivo "settings.php" exista,\
			// então, carrega o arquivo.
			$this->module->include_clean($this->path, [ "settings" => $this ]);

			// Define o caminho de sobreposição.
			$this->overwrite_path = $this->module->get_path() . "/../settings." . $this->module->get_id() . ".php";
			$this->overwrite_exists = is_readable($this->overwrite_path);

			// Se o arquivo de sobreposição existe, ele é executado.
			if($this->overwrite_exists) {
				$this->module->include_clean($this->overwrite_path, [ "settings" => $this ]);
			}
		}

		/** OVERWRITED */
		// Retorna o caminho do arquivo de sobreposição.
		public function overwrite_path() {
			return awk_path::normalize($this->overwrite_path);
		}

		// Retorna se há um arquivo de sobreposição.
		// @note Não necessariamente indicará se houve alguma sobreposição.
		public function overwrite_exists() {
			return $this->overwrite_exists;
		}

		/** MAGIC */
		// Define uma configuração.
		public function __set($key, $value) {
			$this->settings[$key] = $value;
		}

		// Obtém uma configuração.
		public function __get($key) {
			return $this->settings[$key];
		}

		// Verifica se uma configuração foi definida.
		public function __isset($key) {
			return array_key_exists($key, $this->settings);
		}

		// Remove a definiçãõ de uma configuração.
		public function __unset($key) {
			unset($this->settings[$key]);
		}
	}
