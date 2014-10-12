<?php

    /**
     * O módulo não existe.
     */
    class Awk_Module_NotExists_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $module_name Nome do módulo que não existe.
         */
        public function __construct($module_name) {
            parent::__construct("O módulo \"{$module_name}\" não foi encontrado.");
        }
    }
