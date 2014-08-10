<?php

	// Responsável pelo modelo de dados da library.
	class awk_library extends awk_module_base {
		static protected $feature_type = "library";

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
		public function load($library_name) {
			$this->name = $library_name;
			$this->path = $this->module->get_path() . "/libraries/{$this->name}.php";

			// Se o arquivo da library não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"message" => "O módulo \"" . $this->module->get_name() . "\" não possui a library \"{$this->name}\"."
				]);
			}

			// Carrega o arquivo da library.
			// É esperado que a library registre uma classe.
			$this->module->include_clean($this->path, [ "library" => $this ]);

			// Se não foi registrado uma classe nesta library, gera um erro.
			if(!$this->classname) {
				awk_error::create([
					"message" => "A library \"{$this->name}\" do módulo \"" . $this->module->get_name() . "\" não efetuou o registro de classe."
				]);
			}

			// Se a classe não existir, gera um erro.
			if(!class_exists($this->classname)) {
				awk_error::create([
					"message" => "A library \"{$this->name}\" do módulo \"" . $this->module->get_name() . "\" registrou uma classe inexistente (\"{$this->classname}\")."
				]);
			}
		}

		/** REGISTER */
		// Registra a classe da library.
		public function register($classname, $autoinit_unique = null) {
			$this->classname = $classname;

			// Inicia uma instância única ao registrar a classe.
			if($autoinit_unique === true) {
				$this->unique();
			}
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
			$library_instance = $reflection_instance->newInstanceArgs(func_get_args());

			// Se for uma instância de `awk_base`, armazena as informações da base.
			if($library_instance instanceof awk_base) {
				$library_instance->set_base($this->module);
			}

			// Retorna a instância.
			return $library_instance;
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
						"message" => "O método \"library_unique\" da library \"{$this->classname}\" do módulo \"" .
							$this->module->get_name() . "\" não retornou uma instância da classe \"{$this->classname}\"," .
							" ao invés disso, retornou \"{$unique_instance_type}\"."
					]);
				}

				// Se for uma instância de `awk_base`, armazena as informações da base.
				if($this->unique_instance instanceof awk_base) {
					$this->unique_instance->set_base($this->module);
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
						"message" => "A instância única da library \"{$this->classname}\" do módulo \"" .
							$this->module->get_name() . "\" não pôde ser criada pois seu construtor requer parâmetros. " .
							"Considere definir o método \"library_unique\"."
					]);
				}
			}

			// Inicia a instância única.
			$this->unique_instance = $reflection_instance->newInstance();

			// Se for uma instância de `awk_base`, armazena as informações da base.
			if($this->unique_instance instanceof awk_base) {
				$this->unique_instance->set_base($this->module);
			}

			// Retorna a instância única.
			return $this->unique_instance;
		}
	}
