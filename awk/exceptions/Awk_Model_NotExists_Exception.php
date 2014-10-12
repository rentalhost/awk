<?php

    /**
     * O Model não existe no módulo.
     */
    class Awk_Model_NotExists_Exception extends Awk_Model_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $model_name      Nome do Model que não existe.
         */
        public function __construct($module, $model_name) {
            parent::__construct("O Model \"{$model_name}\" não existe no módulo \"" . $module->get_name() . "\".");
        }
    }
