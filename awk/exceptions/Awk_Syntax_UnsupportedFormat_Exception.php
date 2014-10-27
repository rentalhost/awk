<?php

    /**
     * Formato inválido informado em um Syntax.
     */
    class Awk_Syntax_UnsupportedFormat_Exception extends Awk_Syntax_Exception {
        /**
         * Constrói a exceção.
         * @param string $syntax_definition Definição com falha.
         */
        public function __construct($syntax_definition) {
            parent::__construct("A definição \"{$syntax_definition}\" possui um formato não suportado.");
        }
    }
