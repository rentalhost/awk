<?php

    /**
     * Responsável pelo controle da feature view.
     */
    class Awk_View_Feature extends Awk_Module_Feature {
        /**
         * Carrega uma view imediatamente.
         * @param  string $view_id                  Identificador da view.
         * @param  mixed[] $view_args               Argumentos que serão enviados a view como variáveis.
         * @param  boolean $view_avoid_print        Se deverá impedir a impressão automática da view.
         * @param  boolean $view_avoid_processing   Se deve impedir que a view seja processada automaticamente.
         * @return Awk_View
         */
        public function feature_call($view_id, $view_args = null, $view_avoid_print = null, $view_avoid_processing = null) {
            return $this->load($view_id, $view_args, $view_avoid_print, $view_avoid_processing);
        }

        /**
         * Carrega uma view imediatamente.
         * @param  string $view_id                  Identificador da view.
         * @param  mixed[] $view_args               Argumentos que serão enviados a view como variáveis.
         * @param  boolean $view_avoid_print        Se deverá impedir a impressão automática da view.
         * @param  boolean $view_avoid_processing   Se deve impedir que a view seja processada automaticamente.
         * @return Awk_View
         */
        public function load($view_id, $view_args = null, $view_avoid_print = null, $view_avoid_processing = null) {
            $view_instance = new Awk_View($this->module, $this);
            $view_instance->load($view_id, $view_args, $view_avoid_print, $view_avoid_processing);

            return $view_instance;
        }

        /**
         * Verifica se uma determinada View existe.
         * @param  string $view_name Nome da View.
         * @return boolean
         */
        public function exists($view_name) {
            return is_readable($this->module->get_path()->get() . "/views/{$view_name}.php");
        }
    }
