<?php

    /**
     * A Query não existe no Model.
     */
    class Awk_Model_QueryNotExists_Exception extends Awk_Model_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $model_name      Nome do Model com falha.
         * @param string     $query_name      Nome da Query que não existe.
         */
        public function __construct($module, $model_name, $query_name) {
            parent::__construct("A Query \"{$query_name}\" não foi definida no Model \"{$model_name}\" do módulo \"" . $module->get_name() . "\".");
        }
    }
