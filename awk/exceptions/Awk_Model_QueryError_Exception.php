<?php

    /**
     * Falha de execução da Query.
     */
    class Awk_Model_QueryError_Exception extends Awk_Model_Exception {
        /**
         * Constrói a exceção.
         */
        public function __construct() {
            parent::__construct("Falha ao executar a Query.");
        }
    }
