<?php

    /**
     * Um tipo não suportado de Query foi usado.
     */
    class Awk_Model_UnsupportedQueryType_Exception extends Awk_Model_Exception {
        /**
         * Constrói a exceção.
         * @param string $query_type Tipo da Query não suportada.
         */
        public function __construct($query_type) {
            parent::__construct("Não é suportado o tipo \"{$query_type}\" para Query.");
        }
    }
