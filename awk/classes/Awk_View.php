<?php

    /**
     * Responsável pelo modelo de dados da view.
     */
    class Awk_View extends Awk_Module_Base {
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

        /**
         * Armazena se a View já foi processada.
         * @var boolean
         */
        private $processed = false;

        /**
         * Carrega a view e a retorna.
         * @param  string  $view_name               Identificador da view.
         * @param  mixed[] $view_args               Argumentos que serão transferidos a view como variáveis.
         * @param  boolean $view_avoid_print        Se deve impedir que a view seja impressa automaticamente.
         * @param  boolean $view_avoid_processing   Se deve impedir que a view seja processada automaticamente.
         */
        public function load($view_name, $view_args = null, $view_avoid_print = null, $view_avoid_processing = null) {
            $this->name = $view_name;
            $this->path = new Awk_Path($this->module->get_path()->get() . "/views/{$view_name}.php");

            // Processa a view.
            if($view_avoid_processing !== true) {
                $this->process($view_args, $view_avoid_print);
            }
        }

        /**
         * Processa os parâmetros de uma view.
         * @param  mixed[] $view_args        Argumentos que serão transferidos a view como variáveis.
         * @param  boolean $view_avoid_print Se deve impedir que a view seja impressa automaticamente.
         */
        public function process($view_args = null, $view_avoid_print = null) {
            // Reseta algumas informações.
            $this->return = null;
            $this->contents = null;
            $this->printed = false;

            // Indica que a View foi processada.
            $this->processed = true;

            // Carrega o arquivo, armazenando sua saída.
            if($this->path->is_file()
            && $this->path->is_readable()) {
                ob_start();
                $this->return = $this->module->include_clean($this->path->get(), $view_args);
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
         * Retorna se a View já foi processada.
         * @return boolean
         */
        public function was_processed() {
            return $this->processed;
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
    }
