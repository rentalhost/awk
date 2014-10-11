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
        private $base_module;

        /**
         * Define o módulo base.
         * @param Awk_Module $base_module Módulo base que será definido.
         */
        public function set_base_module($base_module) {
            $this->base_module = $base_module;
        }

        /**
         * Obtém o módulo base.
         * @return Awk_Module Retorna o módulo base definido.
         */
        public function get_base_module() {
            return $this->base_module;
        }
    }
