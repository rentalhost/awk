<?php

    /**
     * Responsável pro gerir dados.
     */
    class Awk_Data implements Awk_PropertyAccess_Interface {
        /**
         * Armazena os dados.
         * @var mixed[]
         */
        private $data = [];

        /**
         * Define uma variável.
         * @param string $key   Nome da chave a ser definida.
         * @param mixed  $value Valor a ser definido.
         */
        public function set($key, $value) {
            $this->data[$key] = $value;
        }

        /**
         * Define uma variável com referência.
         * @param  string $key   Nome da chave a ser definida.
         * @param  mixed  $value Valor a ser definido.
         */
        public function bind($key, &$value) {
            $this->data[$key] = &$value;
        }

        /**
         * Obtém uma variável.
         * @param  string $key Nome da chave a ser obtida.
         * @return mixed
         */
        public function get($key) {
            return $this->data[$key];
        }

        /**
         * Define várias variáveis.
         * @param mixed[] $keys Variáveis que serão definidas.
         */
        public function set_array($keys) {
            $this->data = array_replace($this->data, $keys);
        }

        /**
         * Retorna todas as variáveis definidas.
         * @return mixed[]
         */
        public function get_array() {
            return $this->data;
        }

        /**
         * Define uma variável.
         * @param string $key   Nome da chave a ser definida.
         * @param mixed  $value Valor a ser definido.
         */
        public function __set($key, $value) {
            $this->data[$key] = $value;
        }

        /**
         * Obtém uma variável.
         * @param  string $key Nome da chave a ser obtida.
         * @return mixed
         */
        public function __get($key) {
            return $this->data[$key];
        }

        /**
         * Verifica se uma variável existe.
         * @param  string  $key Nome da chave a ser verificada.
         * @return boolean
         */
        public function __isset($key) {
            return array_key_exists($key, $this->data);
        }

        /**
         * Remove uma variavel.
         * @param string $key Nome da chave a ser removida.
         */
        public function __unset($key) {
            unset($this->data[$key]);
        }

        /**
         * Remove todos os dados armazenados.
         */
        public function clear() {
            $this->data = [];
        }
    }
