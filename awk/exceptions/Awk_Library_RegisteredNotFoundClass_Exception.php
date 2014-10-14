<?php

    /**
     * A Library registrou uma classe inexistente.
     */
    class Awk_Library_RegisteredNotFoundClass_Exception extends Awk_Library_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $library_name    Nome da Library que registrou uma classe não localizável.
         */
        public function __construct($module, $library_name, $class_name) {
            parent::__construct("A Library \"{$library_name}\" do módulo \"{$module->name}\" registrou a classe \"{$class_name}\", mas ela não foi encontrada.");
        }
    }
