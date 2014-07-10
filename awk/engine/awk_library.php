<?php

	// Responsável pelo modelo de dados da library.
	class awk_library extends awk_base {
		/** LIBRARY */
		// Armazena o nome da classe da library registrada.
		// @type string;
		private $classname;

		// Armazena a reflexão da classe registrada.
		// @type ReflectionClass;
		private $reflection;

		// Armazena a instância única da library.
		// @type instance;
		private $unique_instance;

		/** LOAD */
		// Carrega a library e a retorna.
		// @return self;
		public function load($library_id) {
			$this->id = $library_id;
			$this->path = $this->module->get_path() . "/libraries/{$this->id}.php";

			// Se o arquivo da library não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "O módulo \"" . $this->module->get_id() . "\" não possui a library \"{$this->id}\"."
				]);
			}

			// Carrega o arquivo da library.
			// É esperado que a library registre uma classe.
			$this->module->include_clean($this->path, [ "library" => $this ]);

			// Se não foi registrado uma classe nesta library, gera um erro.
			if(!$this->classname) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "A library \"{$this->id}\" do módulo \"" . $this->module->get_id() . "\" não efetuou o registro de classe."
				]);
			}

			// Se a classe não existir, gera um erro.
			if(!class_exists($this->classname)) {
				awk_error::create([
					"type" => awk_error::TYPE_FATAL,
					"message" => "A library \"{$this->id}\" do módulo \"" . $this->module->get_id() . "\" registrou uma classe inexistente (\"{$this->classname}\")."
				]);
			}
		}

		/** REGISTER */
		// Registra a classe da library.
		public function register($classname) {
			$this->classname = $classname;
		}

		/** REFLECTION */
		// Obtém a reflexão da classe.
		private function get_reflection() {
			// Se já foi iniciada, apenas retorna.
			// Caso contrário será necessário inicializá-la.
			if($this->reflection) {
				return $this->reflection;
			}

			// Inicializa a reflexão.
			return $this->reflection = new ReflectionClass($this->classname);
		}

		/** CREATE */
		// Cria uma nova instância da classe.
		// Os argumentos serão enviados diretamente ao construtor.
		public function create() {
			$reflection_instance = $this->get_reflection();
			return $reflection_instance->newInstanceArgs(func_get_args());
		}

		/** UNIQUE */
		// Cria uma instância única da classe.
		// Os argumentos serão enviados ao método `library_unique()`, se disponível. \
		// Neste caso, o próprio método deverá retornar a nova instância da classe. \
		// Se isso não acontecer, o construtor será executado sem argumentos.
		public function unique() {
			// Se a instância já foi criada, retorna.
			if($this->unique_instance) {
				return $this->unique_instance;
			}

			// Inicia a reflexão.
			$reflection_instance = $this->get_reflection();
			$unique_instance = null;

			// Se existir o método `library_unique()` ele será executado.
			if($reflection_instance->hasMethod("library_unique")) {
				$unique_instance = $reflection_instance->getMethod("library_unique")->invokeArgs(null, func_get_args());

				// Se não for retornado um objeto, um erro é retornado.
				if(!$unique_instance instanceof $this->classname) {
					$unique_instance_type = is_object($unique_instance) ? get_class($unique_instance) : gettype($unique_instance);
					awk_error::create([
						"type" => awk_error::TYPE_FATAL,
						"message" => "O método \"library_unique\" da library \"{$this->id}\" do módulo \"" .
							$this->module->get_id() . "\" não retornou uma instância da classe \"{$this->classname}\", " .
							" ao invés disso, retornou \"{$unique_instance_type}\"."
					]);
				}

				// Armazena e retorna a instância.
				return $this->unique_instance = $unique_instance;
			}

			// Caso contrário, será criado a partir do construtor.
			// Neste caso, é obrigatório que o construtor não possua argumentos obrigatórios.
			$reflection_constructor = $reflection_instance->getConstructor();
			if($reflection_constructor) {
				if($reflection_constructor->getNumberOfRequiredParameters() !== 0) {
					awk_error::create([
						"type" => awk_error::TYPE_FATAL,
						"message" => "A instância única da library \"{$this->id}\" do módulo \"" .
							$this->module->get_id() . "\" não pôde ser criada pois seu construtor requer parâmetros. " .
							"Considere definir o método `library_unique`."
					]);
				}
			}

			// Inicia a instância única.
			return $this->unique_instance = $reflection_instance->newInstance();
		}
	}
