<?php

    /**
     * O Controller não existe no módulo.
     */
    class Awk_Controller_NotExists_Exception extends Awk_Controller_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $controller_name Nome do Controller que não existe.
         */
        public function __construct($module, $controller_name) {
            parent::__construct("O Controller \"{$controller_name}\" não existe no módulo \"" . $module->get_name() . "\".");
        }
    }
