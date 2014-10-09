<?php

    // Responsável pelo modelo de dados da library.
    class Awk_Library extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "library";

        /**
         * Armazena o nome da classe da library registrada.
         * @var string
         */
        private $classname;

        /**
         * Armazena a reflexão da classe registrada.
         * @var ReflectionClass
         */
        private $reflection;

        /**
         * Armazena a instância única da library.
         * @var object
         */
        private $unique_instance;

        /**
         * Carrega a library e a retorna.
         * @param  string $library_name Identificador da library.
         */
        public function load($library_name) {
            $this->name = $library_name;
            $this->path = $this->module->get_path() . "/libraries/{$this->name}.php";

            // Se o arquivo da library não existir, lança um erro.
            if(!is_readable($this->path)) {
                Awk_Error::create([
                    "message" => "O módulo \"" . $this->module->get_name() . "\" não possui a library \"{$this->name}\"."
                ]);
            } // @codeCoverageIgnore

            // Carrega o arquivo da library.
            // É esperado que a library registre uma classe.
            $this->module->include_clean($this->path, [ "library" => $this ], true);

            // Se não foi registrado uma classe nesta library, gera um erro.
            if(!$this->classname) {
                Awk_Error::create([
                    "message" => "A library \"{$this->name}\" do módulo \"" . $this->module->get_name() . "\" não efetuou o registro de classe."
                ]);
            } // @codeCoverageIgnore

            // Se a classe não existir, gera um erro.
            if(!class_exists($this->classname)) {
                Awk_Error::create([
                    "message" => "A library \"{$this->name}\" do módulo \"" . $this->module->get_name() . "\" registrou uma classe inexistente (\"{$this->classname}\")."
                ]);
            } // @codeCoverageIgnore
        }

        /**
         * Registra a classe da library.
         * @param  string  $classname       Nome da classe que será registrada.
         * @param  boolean $autoinit_unique Se deve auto-iniciar a classe registrada.
         */
        public function register($classname, $autoinit_unique = null) {
            $this->classname = $classname;

            // Inicia uma instância única ao registrar a classe.
            if($autoinit_unique === true) {
                $this->unique();
            }
        }

        /**
         * Obtém o nome da classe registrada.
         * @return string
         */
        public function get_registered_classname() {
            return $this->classname;
        }

        /**
         * Obtém a reflexão da classe.
         * @return ReflectionClass
         */
        private function get_reflection() {
            // Se já foi iniciada, apenas retorna.
            // Caso contrário será necessário inicializá-la.
            if($this->reflection) {
                return $this->reflection;
            }

            // Inicializa a reflexão.
            return $this->reflection = new ReflectionClass($this->classname);
        }

        /**
         * Cria uma nova instância da classe.
         * @param  mixed $args,... Argumentos que serão enviados ao construtor.
         * @return object
         */
        public function create($args = null) {
            $reflection_instance = $this->get_reflection();
            $library_instance = $reflection_instance->newInstanceArgs(func_get_args());

            // Se for uma instância de `Awk_Base`, armazena as informações da base.
            if($library_instance instanceof Awk_Base) {
                $library_instance->set_base($this->module);
            }

            // Retorna a instância.
            return $library_instance;
        }

        /**
         * Cria uma instância única da classe.
         * Os argumentos serão enviados ao método `library_unique()`, se disponível.
         * Neste caso, o próprio método deverá retornar a nova instância da classe.
         * Se isso não acontecer, o construtor será executado sem argumentos.
         * @return object
         */
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
                    Awk_Error::create([
                        "message" => "O método \"library_unique\" da library \"{$this->classname}\" do módulo \"" .
                            $this->module->get_name() . "\" não retornou uma instância da classe \"{$this->classname}\"," .
                            " ao invés disso, retornou \"{$unique_instance_type}\"."
                    ]);
                } // @codeCoverageIgnore

                // Se for uma instância de `Awk_Base`, armazena as informações da base.
                if($unique_instance instanceof Awk_Base) {
                    $unique_instance->set_base($this->module);
                }

                // Armazena e retorna a instância.
                return $this->unique_instance = $unique_instance;
            }

            // Caso contrário, será criado a partir do construtor.
            // Neste caso, é obrigatório que o construtor não possua argumentos obrigatórios.
            $reflection_constructor = $reflection_instance->getConstructor();
            if($reflection_constructor) {
                if($reflection_constructor->getNumberOfRequiredParameters() !== 0) {
                    Awk_Error::create([
                        "message" => "A instância única da library \"{$this->classname}\" do módulo \"" .
                            $this->module->get_name() . "\" não pôde ser criada pois seu construtor requer parâmetros. " .
                            "Considere definir o método \"library_unique\"."
                    ]);
                } // @codeCoverageIgnore
            }

            // Inicia a instância única.
            $this->unique_instance = $reflection_instance->newInstance();

            // Se for uma instância de `Awk_Base`, armazena as informações da base.
            if($this->unique_instance instanceof Awk_Base) {
                $this->unique_instance->set_base($this->module);
            }

            // Retorna a instância única.
            return $this->unique_instance;
        }
    }
