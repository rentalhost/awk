<?php

    /**
     * Responsável por um model.
     */
    class Awk_Model extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "model";

        /**
         * Armazena a base da tabela.
         * @var Awk_Model
         */
        private $model_base;

        /**
         * Armazena o prefixo da tabela.
         * @var string
         */
        private $table_prefix;

        /**
         * Armazena o nome da tabela.
         * @var string
         */
        private $table_name;

        /**
         * Armazena as queries.
         * @var Awk_Model_Query[]
         */
        private $queries = [];

        /**
         * Carrega o model.
         * @param  string $model_name Identificador do model.
         */
        public function load($model_name) {
            $this->name = $model_name;
            $this->path = $this->module->get_path() . "/models/{$this->name}.php";

            // Se o arquivo do model não existir, lança um erro.
            if(!is_readable($this->path)) {
                Awk_Error::create([
                    "message" => "O módulo \"" . $this->module->get_name() . "\" não possui o model \"{$this->name}\"."
                ]);
            } // @codeCoverageIgnore

            // Carrega o arquivo do model.
            $this->module->include_clean($this->path, [ "model" => $this ]);
        }

        /**
         * Define a base da tabela.
         * @param string $table_base Identificador do model base.
         */
        public function set_base($table_base) {
            $this->model_base = $this->get_module()->identify($table_base, "model", true);
        }

        /**
         * Define o prefixo da tabela.
         * @param string $table_prefix Define o prefixo da tabela do model.
         */
        public function set_prefix($table_prefix) {
            $this->table_prefix = $table_prefix;
        }

        /**
         * Obtém o prefixo da tabela.
         * @return string
         */
        public function get_prefix() {
            // Se um model base foi informado, retorna baseado em seu prefixo.
            if($this->model_base) {
                return $this->model_base->get_prefix() . $this->table_prefix;
            }

            // Caso contrário, retorna o próprio prefixo.
            return $this->table_prefix;
        }

        /**
         * Define o nome da tabela.
         * @param string $table_name Nome da tabela, sem o prefixo.
         */
        public function set_table($table_name) {
            $this->table_name = $this->get_prefix() . $table_name;
        }

        /**
         * Retorna o nome da tabela, sem o prefixo.
         * @return string
         */
        public function get_table() {
            return $this->table_name;
        }

        /**
         * Adiciona uma nova query.
         * @param string $query_name    Nome de referência da query.
         * @param string $query_type    Tipo específico da query.
         * @param string $query_definer Definição da query.
         */
        public function add_query($query_name, $query_type, $query_definer) {
            // Se há houver uma chave com o mesmo nome, lança um erro.
            if(array_key_exists($query_name, $this->queries)) {
                Awk_Error::create([
                    "message" => "A query \"{$query_name}\" já foi definida no model \"" . $this->get_name()
                        . "\" do módulo \"" . $this->module->get_name() . "\"."
                ]);
            } // @codeCoverageIgnore

            // Define a query.
            return $this->queries[$query_name] = new Awk_Model_Query($this, $query_name, $query_type, $query_definer);
        }

        /**
         * Executa uma query através do seu nome definido.
         * @param  string  $query_name Nome de referência da query.
         * @param  mixed[] $call_args  Argumentos que serão transferidos a query.
         * @return Awk_Model_Row
         */
        public function __call($query_name, $call_args) {
            // Se não foi definido, lança uma exceção.
            if(!array_key_exists($query_name, $this->queries)) {
                Awk_Error::create([
                    "message" => "A query \"{$query_name}\" não foi definida no model \"" . $this->get_name()
                        . "\" do módulo \"" . $this->module->get_name() . "\"."
                ]);
            } // @codeCoverageIgnore

            // Define os argumentos que serão enviados à query.
            $query_args = isset($call_args[0]) ? $call_args[0] : [];

            // Caso contrário, carrega a query e executa.
            return $this->queries[$query_name]->execute($query_args);
        }
    }
