<?php

    /**
     * Responsável por criar um símbolo de representação.
     */
    class Awk_Symbol {
        /**
         * Armazena todos os símbolos gerados.
         * @var self[]
         */
        static private $symbols = [];

        /**
         * Armazena o índex de símbolo atual.
         * @var int
         */
        static private $symbols_index = 0;

        /**
         * Armazena o identificador do símbolo.
         * @var string
         */
        public $id;

        /**
         * Mensagem do símbolo.
         * @var mixed
         */
        public $message;

        /**
         * Cria um símbolo e retorna seu identificador.
         * @param  string $message Mensagem do símbolo.
         * @return string
         */
        static public function create($message = null) {
            $symbol_instance = new self($message);
            return $symbol_instance->id;
        }

        /**
         * Retorna um símbolo armazenado.
         * @param  string $symbol_identifier Identificador do símbolo.
         * @throws Awk_Symbol_NotConstructed_Exception Caso o símbolo nunca tenha sido contruído.
         * @return self|false
         */
        static public function get($symbol_identifier) {
            if(array_key_exists($symbol_identifier, self::$symbols)) {
                return self::$symbols[$symbol_identifier];
            }

            // Lançará uma exceção caso o símbolo não existir.
            throw new Awk_Symbol_NotConstructed_Exception($symbol_identifier);
        }

        /**
         * Constrói um novo símbolo.
         * @param  string $message Mensagem do símbolo.
         */
        public function __construct($message = null) {
            // Determina o identificador do símbolo.
            $symbol_identifier = "Symbol." . self::$symbols_index++;

            // Determina seu identificador.
            $this->id      = $symbol_identifier;
            $this->message = $message;

            // Armazena o símbolo.
            self::$symbols[$symbol_identifier] = $this;
        }

        /**
         * Verifica se o símbolo possui o mesmo identificador ou se possui a mesma mensagem.
         * @param mixed|self $symbol Instância ou mensagem a ser testada.
         * @return boolean
         */
        public function is($symbol) {
            return $this->message === $symbol
                || $this === $symbol;
        }

        /**
         * Verifica se o símbolo informado é similar ao outro símbolo.
         * Ou seja, apesar de serem instâncias diferentes, possuem a mesma mensagem.
         * @param  self  $symbol Símbolo a ser testado.
         * @return boolean
         */
        public function is_similar($symbol) {
            return $symbol instanceof self
                && $symbol->message === $this->message;
        }
    }
