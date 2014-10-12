<?php

    /**
     * O identificador não possui um formato suportado.
     */
    class Awk_Module_IdUnsupportedFormat_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $id Identificador que recebeu a falha.
         */
        public function __construct($id) {
            parent::__construct("Falha ao identificar \"{$id}\". O formato utilizado não é suportado.");
        }
    }
