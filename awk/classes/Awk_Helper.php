<?php

    /**
     * Responsável pelo modelo de dados do helper.
     */
    class Awk_Helper extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "helper";

        /**
         * Armazena os métodos registrados para o helper.
         * @var callable[]
         */
        private $methods = [];

        /**
         * Carrega o helper.
         * @param  string $helper_name Identificador do helper.
         * @throws Awk_Helper_NotExists_Exception Caso o Helper não exista no módulo.
         */
        public function load($helper_name) {
            $this->name = $helper_name;
            $this->path = new Awk_Path($this->module->get_path()->get() . "/helpers/{$this->name}.php");

            // Se o arquivo do helper não existir, lança um erro.
            if(!$this->path->is_file()
            || !$this->path->is_readable()) {
                throw new Awk_Helper_NotExists_Exception($this->module, $this->name);
            }

            // Carrega o arquivo do helper.
            $this->module->include_clean($this->path->get(), [ "helper" => $this ]);
        }

        /**
         * Adiciona um novo helper.
         * @param string   $method   Nome da nova função.
         * @param callable $callback Definição da função.
         */
        public function add($method, $callback) {
            $this->methods[$method] = $callback;
        }

        /**
         * Chama um método do helper, recebendo argumentos.
         * @param  string $method   Nome do método que será executado.
         * @param  mixed  $args,... Argumentos que serão enviados ao método.
         * @return mixed
         */
        public function call($method, $args = null) {
            return $this->call_array($method, array_slice(func_get_args(), 1));
        }

        /**
         * Chama um método do helper, recebendo argumentos através de um array.
         * @param  string  $method  Nome do método que será executado.
         * @param  mixed[] $args    Argumentos que serão enviados ao método.
         * @return mixed
         */
        public function call_array($method, $args) {
            return call_user_func_array($this->methods[$method], $args);
        }
    }
