<?php

    /**
     * O Router não existe no módulo.
     */
    class Awk_Router_NotExists_Exception extends Awk_Router_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $router_name     Nome do Router que não existe.
         */
        public function __construct($module, $router_name) {
            parent::__construct("O Router \"{$router_name}\" não existe no módulo \"" . $module->get_name() . "\".");
        }
    }
