<?php

    /**
     * O método de transformação não é válido.
     */
    class Awk_Type_InvalidTransformCallback_Exception extends Awk_Type_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module    Instância do módulo de referência.
         * @param string     $type_name Nome do tipo com falha.
         */
        public function __construct($module, $type_name) {
            parent::__construct("O tipo \"{$type_name}\" do módulo \"{$module->name}\" definiu um método de transformação inválido.");
        }
    }
