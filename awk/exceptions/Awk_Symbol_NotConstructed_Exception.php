<?php

    /**
     * O símbolo nunca foi construído.
     */
    class Awk_Symbol_NotConstructed_Exception extends Awk_Symbol_Exception {
        /**
         * Constrói a exceção.
         * @param string $symbol_identifier Identificador com a falha.
         */
        public function __construct($symbol_identifier) {
            parent::__construct("O símbolo \"{$symbol_identifier}\" nunca foi construído.");
        }
    }
