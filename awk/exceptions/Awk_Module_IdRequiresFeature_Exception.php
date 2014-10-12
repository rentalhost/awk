<?php

    /**
     * O identificador deveria definir um recurso.
     */
    class Awk_Module_IdRequiresFeature_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $id Identificador que recebeu a falha.
         */
        public function __construct($id) {
            parent::__construct("Falha ao identificar \"{$id}\". A definição de um recurso é obrigatório.");
        }
    }
