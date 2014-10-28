<?php

    /**
     * O tipo não existe no módulo.
     */
    class Awk_Type_NotExists_Exception extends Awk_Type_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module    Instância do módulo de referência.
         * @param string     $type_name Nome do tipo que não existe.
         */
        public function __construct($module, $type_name) {
            parent::__construct("O tipo \"{$type_name}\" não existe no módulo \"{$module->name}\".");
        }
    }
