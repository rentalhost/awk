<?php

	// Responsável por gerenciar os módulos e suas propriedades.
	class awk_module {
		// Armazena as instâncias dos módulos carregados.
		// @type array<string, self>;
		static private $modules = [];

		/** ID */
		// Identificador do módulo.
		// @type string;
		private $id;

		// Caminho absoluto do módulo.
		// @type string;
		private $path;

		// Retorna o identificador do módulo.
		// @return string;
		public function get_id() {
			return $this->id;
		}

		// Retorna o caminho absoluto do módulo.
		// @return string;
		public function get_path() {
			return awk_path::normalize($this->path);
		}

		/** CONSTRUCT */
		// Constrói uma nova instância de módulo.
		private function __construct($module_id) {
			$this->id = $module_id;
			$this->path = __DIR__ . "/../../{$module_id}";

			// Se o caminho informado não existir, gera um erro.
			// @error generic;
			if(!is_dir($this->path)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O módulo \"{$module_id}\" não existe."
				]);
				return;
			}

			// Se o arquivo de configuração (settings.php) não existe, gera um erro.
			// Módulos devem possuir este arquivo para indicar um módulo valido.
			// @error generic;
			$module_settings_path = "{$this->path}/settings.php";
			if(!is_file($module_settings_path)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O módulo \"{$module_id}\" não definiu o arquivo de configuração."
				]);
			}
		}

		/** LOADER */
		// Carrega e retorna um módulo.
		static public function get($module_id) {
			// Se o módulo já foi carregado, retorna sua instância.
			if(isset(self::$modules[$module_id]))
				return self::$modules[$module_id];

			// Caso contrário, cria sua instância e retorna.
			return self::$modules[$module_id] = new self($module_id);
		}
	}
