<?php

	// Responsável pelo modelo de dados do controller.
	class awk_controller {
		// Armazena o módulo do controller.
		// @type awk_module;
		private $module;

		// Armazena a feature do controller.
		// @type awk_controller_feature;
		private $feature;

		/** CONTROLLER */
		// Armazena o identificador do controller.
		// @type string;
		private $id;

		// Armazena o caminho completo do controller.
		// @type string;
		private $path;

		// Armazena o nome da classe do controller registrado.
		// @type string;
		private $classname;

		// Armazena a instância do controller.
		// @type instance;
		private $instance;

		/** CONSTRUCT */
		// Constrói um novo controller sobre a feature.
		public function __construct(awk_controller_feature $feature) {
			$this->feature = $feature;
			$this->module = $feature->get_module();
		}

		/** LOAD */
		// Carrega o controller e a retorna.
		// @return self;
		public function load($controller_id) {
			$this->id = $controller_id;
			$this->path = $this->module->get_path() . "/controllers/{$this->id}.php";

			// Se o arquivo do controller não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"fatal" => true,
					"message" => "O módulo \"" . $this->module->get_id() . "\" não possui o controller \"{$this->id}\"."
				]);
			}

			// Carrega o arquivo do controller.
			// É esperado que o controlador registre uma classe.
			$this->module->include_clean($this->path, [ "controller" => $this ]);

			// Se não foi registrado uma classe neste controlador, gera um erro.
			if(!$this->classname) {
				awk_error::create([
					"fatal" => true,
					"message" => "O controller \"{$this->id}\" do módulo \"" . $this->module->get_id() . "\" não efetuou o registro de classe."
				]);
			}

			// Se a classe não existir, gera um erro.
			if(!class_exists($this->classname)) {
				awk_error::create([
					"fatal" => true,
					"message" => "O controller \"{$this->id}\" do módulo \"" . $this->module->get_id() . "\" registrou uma classe inexistente (\"{$this->classname}\")."
				]);
			}

			// Inicia a instância do controller.
			$controller_reflection = new ReflectionClass($this->classname);
			$this->instance = $controller_reflection->newInstance();
		}

		/** REGISTER */
		// Registra a classe do controller.
		public function register($classname) {
			$this->classname = $classname;
		}

		/** PROPRIEDADES */
		// Obtém o identificador do controller.
		// @return string;
		public function get_id() {
			return $this->id;
		}

		// Obtém o path normalizado do controller.
		// @return string;
		public function get_path() {
			return awk_path::normalize($this->path);
		}

		// Retorna a instância do controller.
		// @return instance;
		public function get_instance() {
			return $this->instance;
		}
	}
