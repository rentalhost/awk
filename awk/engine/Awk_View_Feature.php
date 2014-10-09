<?php

    /**
     * Responsável pelo controle da feature view.
     */
    class Awk_View_Feature extends Awk_Module_Feature {
        /**
         * Carrega uma view imediatamente.
         * @param  string $view_id          Identificador da view.
         * @param  mixed[] $view_args        Argumentos que serão enviados a view como variáveis.
         * @param  boolean $view_avoid_print Se deverá impedir a impressão automática da view.
         * @return Awk_View
         */
        public function feature_call($view_id, $view_args = null, $view_avoid_print = null) {
            return $this->load($view_id, $view_args, $view_avoid_print);
        }

        /**
         * Carrega uma view imediatamente.
         * @param  string $view_id          Identificador da view.
         * @param  mixed[] $view_args        Argumentos que serão enviados a view como variáveis.
         * @param  boolean $view_avoid_print Se deverá impedir a impressão automática da view.
         * @return Awk_View
         */
        public function load($view_id, $view_args = null, $view_avoid_print = null) {
            $view_instance = new Awk_View($this->module, $this);
            $view_instance->load($view_id, $view_args, $view_avoid_print);

            return $view_instance;
        }
    }
