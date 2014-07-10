<?php

	// Responsável pelo modelo de dados do controller.
	class awk_controller extends awk_base {
		/** CONTROLLER */
		// Armazena o nome da classe do controller registrado.
		// @type string;
		private $classname;

		// Armazena a instância do controller.
		// @type instance;
		private $instance;

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
		// Retorna a instância do controller.
		// @return instance;
		public function get_instance() {
			return $this->instance;
		}
	}
