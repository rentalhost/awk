<?php

    /**
     * Responsável pelo modelo de dados da view.
     */
    class Awk_View extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "view";

        /**
         * Armazena se o caminho da view remete a um arquivo acessível.
         * @var boolean
         */
        private $exists = false;

        /**
         * Armazena a informação retornada pela view.
         * @var mixed
         */
        private $return;

        /**
         * Armazena a informação impressa pela view.
         * @var string
         */
        private $contents;

        /**
         * Armazena se a view foi impressa.
         * @var boolean
         */
        private $printed = false;

        /** LOAD */
        //
        // @return self;
        /**
         * Carrega a view e a retorna.
         * @param  string  $view_name        Identificador da view.
         * @param  mixed[] $view_args        Argumentos que serão transferidos a view como variáveis.
         * @param  boolean $view_avoid_print Se deve impedir que a view seja impressa automaticamente.
         */
        public function load($view_name, $view_args = null, $view_avoid_print = null) {
            $this->name = $view_name;
            $this->path = $this->module->get_path() . "/views/{$view_name}.php";
            $this->exists = is_readable($this->path);

            // Carrega o arquivo, armazenando sua saída.
            if($this->exists) {
                ob_start();
                $this->return = $this->module->include_clean($this->path, $view_args);
                $this->contents = ob_get_clean();
            }

            // Imprime a view, se for necessário.
            if($view_avoid_print !== true) {
                $this->print_contents();
            }
        }

        /**
         * Imprime a view.
         *
         * Nota: este método deve ser usado quando for necessário imprimir o conteúdo,
         * exceto quando for necessário uma impressão transparente.
         */
        public function print_contents() {
            $this->printed = true;
            echo $this->contents;
        }

        /**
         * Retorna se a view já foi impressa.
         * @return boolean
         */
        public function was_printed() {
            return $this->printed;
        }

        /**
         * Obtém o valor retornado pela view (via `return`).
         * @return mixed
         */
        public function get_return() {
            return $this->return;
        }

        /**
         * Retorna o conteúdo gerado pela view.
         * @return string
         */
        public function get_contents() {
            return (string) $this->contents;
        }

        /**
         * Aliases para `get_contents()`.
         * @return string
         */
        public function __toString() {
            return $this->get_contents();
        }

        /**
         * Retorna se o arquivo da view existe.
         * @return boolean
         */
        public function exists() {
            return $this->exists;
        }
    }
