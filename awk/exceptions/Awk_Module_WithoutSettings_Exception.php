<?php

    /**
     * O arquivo de configurações do módulo não existe.
     */
    class Awk_Module_WithoutSettings_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $module_name Nome do módulo com a falha.
         */
        public function __construct($module_name) {
            parent::__construct("O módulo \"{$module_name}\" não possui um arquivo de configurações.");
        }
    }
