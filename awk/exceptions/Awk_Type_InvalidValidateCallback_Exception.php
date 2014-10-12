<?php

    /**
     * O método de validação não é válido.
     */
    class Awk_Type_InvalidValidateCallback_Exception extends Awk_Type_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module    Instância do módulo de referência.
         * @param string     $type_name Nome do Type com falha.
         */
        public function __construct($module, $type_name) {
            parent::__construct("O Type \"{$type_name}\" do módulo \"" . $module->get_name() . "\" definiu um método de validação inválido.");
        }
    }
