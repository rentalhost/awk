<?php

    /**
     * Formato inválido informado em um Syntax dentro de um contexto.
     */
    class Awk_Syntax_UnsupportedFormatInContext_Exception extends Awk_Syntax_Exception {
        /**
         * Constrói a exceção.
         * @param string $syntax_definition Definição com falha.
         * @param string $syntax_context    Contexto utilizado.
         */
        public function __construct($syntax_definition, $syntax_context) {
            parent::__construct("A definição \"{$syntax_definition}\" possui um formato inválido para o contexto \"{$syntax_context}\".");
        }
    }
