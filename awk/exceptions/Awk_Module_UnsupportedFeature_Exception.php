<?php

    /**
     * Não há suporte para o recurso.
     */
    class Awk_Module_UnsupportedFeature_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $feature_name Nome do recurso que não é suportado.
         */
        public function __construct($feature_name) {
            parent::__construct("O recurso \"{$feature_name}\" não é suportado.");
        }
    }
