<?php

    /**
     * O Controller não registrou uma classe.
     */
    class Awk_Controller_WasNotRegisteredClass_Exception extends Awk_Controller_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $controller_name Nome do Controller que não registrou a classe.
         */
        public function __construct($module, $controller_name) {
            parent::__construct("O Controller \"{$controller_name}\" do módulo \"{$module->name}\" não registrou uma classe.");
        }
    }
