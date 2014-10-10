<?php

    /**
     * Responsável pelo controle da feature session.
     */
    class Awk_Session_Feature extends Awk_Module_Feature {
        /**
         * Armazena a chave da sessão para o módulo.
         * @var string
         */
        private $session_key;

        /**
         * Inicia a chave da sessão.
         */
        private function init_session_key() {
            // Define o caminho base, se não houver.
            if(!$this->session_key) {
                // Inicia a sessão.
                if(session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Define a chave da sessão.
                $this->session_key = $this->get_module()->get_path()->get_normalized();

                // Define a chave da sessão.
                if(!array_key_exists($this->session_key, $_SESSION)) {
                    $this->clear();
                }
            }
        }

        /**
         * Retorna o valor de uma sessão ou define o seu valor.
         * @param  string $session_key   Chave que será obtida/definida.
         * @param  mixed  $session_value Valor que será aplicado na chave.
         * @return mixed
         */
        public function feature_call($session_key = null, $session_value = null) {
            $this->init_session_key();

            switch(func_num_args()) {
                // Se não hover argumentos, retorna todos os dados da sessão.
                case 0:
                    return $_SESSION[$this->session_key];
                    break;
                // Com um argumento, obtém o valor da chave informada.
                case 1:
                    return $_SESSION[$this->session_key][$session_key];
                    break;
            }

            // Caso contrário, define o valor da sessão.
            $_SESSION[$this->session_key][$session_key] = $session_value;
        }

        /**
         * Obtém a chave da sessão.
         * @return string
         */
        public function get_session_key() {
            $this->init_session_key();
            return $this->session_key;
        }

        /**
         * Define uma sessão.
         * @param string $session_key Chave que será obtida.
         * @param mixed  $value       Valor que será aplicado na chave.
         */
        public function __set($session_key, $value) {
            $this->init_session_key();
            $_SESSION[$this->session_key][$session_key] = $value;
        }

        /**
         * Obtém uma sessão.
         * @param  string $session_key Chave que será obtida.
         * @return mixed
         */
        public function __get($session_key) {
            $this->init_session_key();
            return $_SESSION[$this->session_key][$session_key];
        }

        /**
         * Verifica se uma sessão foi definida.
         * @param  string  $session_key Chave que será verificada.
         * @return boolean
         */
        public function __isset($session_key) {
            $this->init_session_key();
            return array_key_exists($session_key, $_SESSION[$this->session_key]);
        }

        /**
         * Remove a definição de uma sessão.
         * @param string $session_key Chave que será removida.
         */
        public function __unset($session_key) {
            $this->init_session_key();
            unset($_SESSION[$this->session_key][$session_key]);
        }

        /**
         * Elimina todos os dados da sessão.
         */
        public function clear() {
            $this->init_session_key();
            $_SESSION[$this->session_key] = [];
        }
    }
