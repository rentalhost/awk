<?php

    /**
     * Um erro de um tipo não suportado foi lançado.
     */
    class Awk_Error_NotSupportedType_Exception extends Awk_Error_Exception {
        /**
         * Constrói a exceção.
         * @param string $error_type Tipo do erro que não é suportado.
         */
        public function __construct($error_type) {
            parent::__construct("Erro do tipo \"{$error_type}\" não é suportado.");
        }
    }
