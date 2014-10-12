<?php

    /**
     * Responsável pelo modelo de dados do controller.
     */
    class Awk_Controller extends Awk_Module_Base {
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
         * @throws Awk_Controller_NotExists_Exception               Caso o Controller não exista.
         * @throws Awk_Controller_WasNotRegisteredClass_Exception   Caso o Controller não tenha registrado uma classe.
         * @throws Awk_Controller_RegisteredNotFoundClass_Exception Caso a classe registrada pelo Controller não seja encontrada.
         * @return self
         */
        public function load($controller_name) {
            $this->name = $controller_name;
            $this->path = new Awk_Path($this->module->get_path()->get() . "/controllers/{$this->name}.php");

            // Se o arquivo do controller não existir ou não for legível, lança um erro.
            if(!$this->path->is_file()
            || !$this->path->is_readable()) {
                throw new Awk_Controller_NotExists_Exception($this->module, $this->name);
            }

            // Carrega o arquivo do controller.
            // É esperado que o controlador registre uma classe.
            $this->module->include_clean($this->path->get(), [ "controller" => $this ], true);

            // Se não foi registrado uma classe neste controlador, gera um erro.
            if(!$this->classname) {
                throw new Awk_Controller_WasNotRegisteredClass_Exception($this->module, $this->name);
            }

            // Se a classe não for encontrada, gera uma exceção.
            if(!class_exists($this->classname)) {
                throw new Awk_Controller_RegisteredNotFoundClass_Exception($this->module, $this->name, $this->classname);
            }

            // Inicia a instância do controller.
            $controller_reflection = new ReflectionClass($this->classname);
            $this->instance = $controller_reflection->newInstance();

            // Se o controller for uma instância de `Awk_Base`, armazena as informações do módulo.
            if($this->instance instanceof Awk_Base) {
                $this->instance->set_base($this->module, $this);
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
