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
        public $module;

        /**
         * Armazena a instância do responsável pela criação da classe.
         * @var object
         */
        public $parent;

        /**
         * Retorna o identificador de uma classe de usuário.
         * @return string
         */
        public function get_id() {
            return $this->parent->get_id();
        }
    }
