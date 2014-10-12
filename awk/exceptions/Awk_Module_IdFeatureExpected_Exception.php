<?php

    /**
     * O identificador deveria utilizar um determinado recurso.
     */
    class Awk_Module_IdFeatureExpected_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $id            Identificador que recebeu a falha.
         * @param string $feature_name  Nome do recurso esperado.
         */
        public function __construct($id, $feature_name) {
            parent::__construct("Falha ao identificar \"{$id}\". O recurso \"{$feature_name}\" era esperado.");
        }
    }
