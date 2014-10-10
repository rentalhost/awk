<?php

    /**
     * Responsável pelo modelo de dados do type.
     */
    class Awk_Type extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "type";

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
         * @return self
         */
        public function load($type_name) {
            $this->name = $type_name;
            $this->path = new Awk_Path($this->module->get_path()->get() . "/types/{$this->name}.php");

            // Se o arquivo do type não existir, lança um erro.
            if(!$this->path->is_file()
            || !$this->path->is_readable()) {
                Awk_Error::create([
                    "message" => "O módulo \"" . $this->module->get_name() . "\" não possui o tipo \"{$this->name}\"."
                ]);
            } // @codeCoverageIgnore

            // Carrega o arquivo do type.
            $this->module->include_clean($this->path->get(), [ "type" => $this ]);
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
         * Se um validador não foi definido, sempre falhará.
         * @param  mixed $value Valor a ser testado.
         * @return boolean
         */
        public function validate($value) {
            if($this->validate_callback) {
                return call_user_func($this->validate_callback, $value);
            }

            return false;
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
         * Se uma transformação não foi definida, sempre retornará null.
         * @param  string $value Valor que será transformado.
         * @return mixed|null
         */
        public function transform($value) {
            if($this->transform_callback) {
                return call_user_func($this->transform_callback, $value);
            }
        }
    }
