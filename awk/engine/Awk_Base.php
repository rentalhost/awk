<?php

    /**
     * Responsável por ser a base das classes registradas pelo usuário.
     * Se uma classe de usuário não estender esta classe, não terá acesso aos recursos do motor diretamente.
     */
    class Awk_Base {
        /**
         * Armazena o módulo responsável pelo objeto.
         * @var Awk_Module
         */
        private $module;

        /**
         * Define os dados da base.
         * @param Awk_Module $module Módulo base que será definido.
         */
        public function set_base($module) {
            $this->module = $module;
        }

        /**
         * Obtém o módulo base.
         * @return Awk_Module Retorna o módulo base definido.
         */
        public function get_module() {
            return $this->module;
        }
    }
