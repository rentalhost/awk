<?php

    /**
     * O recurso não suporta obtenção de identificador.
     */
    class Awk_Module_GetIdIsNotSupported_Exception extends Awk_Module_Exception {
        /**
         * Constrói a exceção.
         * @param string $feature_name Nome do recurso sem o suporte.
         */
        public function __construct($feature_name) {
            parent::__construct("O recurso {$feature_name} não possui suporte a obtenção de identificador.");
        }
    }
