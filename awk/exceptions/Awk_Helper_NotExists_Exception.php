<?php

    /**
     * O Helper não existe no módulo.
     */
    class Awk_Helper_NotExists_Exception extends Awk_Helper_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $helper_name     Nome do helper que não existe.
         */
        public function __construct($module, $helper_name) {
            parent::__construct("O Helper \"{$helper_name}\" não existe no módulo \"" . $module->get_name() . "\".");
        }
    }
