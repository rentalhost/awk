<?php

    /**
     * A Library não registrou uma classe.
     */
    class Awk_Library_WasNotRegisteredClass_Exception extends Awk_Library_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $library_name    Nome da Library que não registrou a classe.
         */
        public function __construct($module, $library_name) {
            parent::__construct("A Library \"{$library_name}\" do módulo \"{$module->name}\" não registrou uma classe.");
        }
    }
