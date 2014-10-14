<?php

    /**
     * Responsável pelo modelo de dados do type.
     */
    class Awk_Type extends Awk_Module_Base {
        /**
         * Armazena o validador de tipo.
         * @var callable
         */
        private $validate_callback;

        /**
         * Armazena o transformador de tipo.
         * @var callable
         */
        private $transform_callback;

        /**
         * Carrega o type e o retorna.
         * @param  string $type_name Identificador do tipo.
         * @throws Awk_Type_NotExists_Exception                 Caso o Type não exista no módulo.
         * @throws Awk_Type_WithoutValidateCallback_Exception   Caso um método de validação não tenha sido definido.
         * @throws Awk_Type_InvalidValidateCallback_Exception   Caso o método de validação não seja válido.
         * @throws Awk_Type_WithoutTransformCallback_Exception  Caso um método de transformação não tenha sido definido.
         * @throws Awk_Type_InvalidTransformCallback_Exception  Caso o método de transformação não seja válido.
         */
        public function load($type_name) {
            $this->name = $type_name;
            $this->path = new Awk_Path($this->module->path->get() . "/types/{$this->name}.php");

            // Se o arquivo do type não existir, lança um erro.
            if(!$this->path->is_file()
            || !$this->path->is_readable()) {
                throw new Awk_Type_NotExists_Exception($this->module, $this->name);
            }

            // Carrega o arquivo do type.
            $this->module->include_clean($this->path->get(), [ "type" => $this ]);

            // Verifica se o método de validação foi definido.
            if(!$this->validate_callback) {
                throw new Awk_Type_WithoutValidateCallback_Exception($this->module, $this->name);
            }
            else
            // Verifica se o método de validação é válida.
            if(!is_callable($this->validate_callback)) {
                throw new Awk_Type_InvalidValidateCallback_Exception($this->module, $this->name);
            }

            // Verifica se o método de transformação foi definido.
            if(!$this->transform_callback) {
                throw new Awk_Type_WithoutTransformCallback_Exception($this->module, $this->name);
            }
            else
            // Verifica se o método de transformação é válido.
            if(!is_callable($this->transform_callback)) {
                throw new Awk_Type_InvalidTransformCallback_Exception($this->module, $this->name);
            }
        }

        /**
         * Define o validador do tipo.
         * @param callable $callback Definição do callable.
         */
        public function set_validate($callback) {
            $this->validate_callback = $callback;
        }

        /**
         * Executa um teste de validação.
         * @param  mixed $value Valor a ser testado.
         * @return boolean
         */
        public function validate($value) {
            return call_user_func($this->validate_callback, $value);
        }

        /**
         * Define o transformador do tipo.
         * @param callable $callback Definição do callable.
         */
        public function set_transform($callback) {
            $this->transform_callback = $callback;
        }

        /**
         * Executa uma transformação.
         * @param  string $value Valor que será transformado.
         * @return mixed|null
         */
        public function transform($value) {
            return call_user_func($this->transform_callback, $value);
        }
    }
