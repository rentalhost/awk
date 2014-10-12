<?php

    /**
     * A Library não existe no módulo.
     */
    class Awk_Library_NotExists_Exception extends Awk_Library_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $library_name    Nome da Library que não existe.
         */
        public function __construct($module, $library_name) {
            parent::__construct("A Library \"{$library_name}\" não existe no módulo \"" . $module->get_name() . "\".");
        }
    }
