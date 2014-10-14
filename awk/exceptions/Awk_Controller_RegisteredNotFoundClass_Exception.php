<?php

    /**
     * O Controller registrou uma classe inexistente.
     */
    class Awk_Controller_RegisteredNotFoundClass_Exception extends Awk_Controller_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $controller_name Nome do Controller que registrou uma classe não localizável.
         */
        public function __construct($module, $controller_name, $class_name) {
            parent::__construct("O Controller \"{$controller_name}\" do módulo \"{$module->name}\" registrou a classe \"{$class_name}\", mas ela não foi encontrada.");
        }
    }
