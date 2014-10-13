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
         * Armazena a instância do responsável pela criação da classe.
         * @var object
         */
        private $parent;

        /**
         * Define a base da classe.
         * @param Awk_Module $module Módulo base que será definido.
         * @param object     $parent Instância responsável pela criação da classe.
         */
        public function set_base($module, $parent) {
            $this->module = $module;
            $this->parent = $parent;
        }

        /**
         * Obtém o módulo base.
         * @return Awk_Module Retorna o módulo base definido.
         */
        public function get_module() {
            return $this->module;
        }

        /**
         * Obtém o parent base.
         * @return object Retorna a instância responsável pela criação da classe.
         */
        public function get_parent() {
            return $this->parent;
        }

        /**
         * Retorna o identificador de uma classe de usuário.
         * @return string
         */
        public function get_id() {
            return $this->parent->get_id();
        }
    }
