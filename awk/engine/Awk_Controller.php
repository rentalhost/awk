<?php

    /**
     * Responsável pelo modelo de dados do controller.
     */
    class Awk_Controller extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "controller";

        /**
         * Armazena o nome da classe do controller registrado.
         * @var string
         */
        private $classname;

        /**
         * Armazena a instância do controller.
         * @var self
         */
        private $instance;

        /**
         * Carrega o controller e a retorna.
         * @param  string $controller_name Identificador do controller a ser carregado.
         * @return self
         */
        public function load($controller_name) {
            $this->name = $controller_name;
            $this->path = $this->module->get_path() . "/controllers/{$this->name}.php";

            // Se o arquivo do controller não existir, lança um erro.
            if(!is_readable($this->path)) {
                Awk_Error::create([
                    "message" => "O módulo \"" . $this->module->get_name() . "\" não possui o controller \"{$this->name}\"."
                ]);
            } // @codeCoverageIgnore

            // Carrega o arquivo do controller.
            // É esperado que o controlador registre uma classe.
            $this->module->include_clean($this->path, [ "controller" => $this ], true);

            // Se não foi registrado uma classe neste controlador, gera um erro.
            if(!$this->classname) {
                Awk_Error::create([
                    "message" => "O controller \"{$this->name}\" do módulo \"" . $this->module->get_name() . "\" não efetuou o registro de classe."
                ]);
            } // @codeCoverageIgnore

            // Se a classe não existir, gera um erro.
            if(!class_exists($this->classname)) {
                Awk_Error::create([
                    "message" => "O controller \"{$this->name}\" do módulo \"" . $this->module->get_name() .
                        "\" registrou uma classe inexistente (\"{$this->classname}\")."
                ]);
            } // @codeCoverageIgnore

            // Inicia a instância do controller.
            $controller_reflection = new ReflectionClass($this->classname);
            $this->instance = $controller_reflection->newInstance();

            // Se o controller for uma instância de `Awk_Base`, armazena as informações do módulo.
            if($this->instance instanceof Awk_Base) {
                $this->instance->set_base($this->module);
            }
        }

        /**
         * Registra a classe do controller.
         * @param string $classname Nome da classe registrada.
         */
        public function register($classname) {
            $this->classname = $classname;
        }

        /**
         * Retorna a instância do controller.
         * @return $this
         */
        public function get_instance() {
            return $this->instance;
        }
    }
