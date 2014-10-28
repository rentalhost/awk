<?php

    /**
     * O tipo já foi criado anteriormente.
     */
    class Awk_Type_AlreadyExists_Exception extends Awk_Type_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module    Instância do módulo de referência.
         * @param string     $type_name Nome do type já declarado.
         * @param string     $type_path Local onde o type foi previamente declarado.
         */
        public function __construct($module, $type_name, $type_path) {
            parent::__construct("O tipo \"{$type_name}\" do módulo \"{$module->name}\" já foi definido em \"{$type_path}\".");
        }
    }
