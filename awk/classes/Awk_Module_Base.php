<?php

    /**
     * Responsável por abstrair o modelo base das classes utilizadas pelas features.
     */
    abstract class Awk_Module_Base {
        /**
         * Mapa de identificadores de recursos.
         * @var string[]
         */
        static private $feature_mapper = [
            "Awk_Controller"    => "controller",
            "Awk_Database"      => "database",
            "Awk_Helper"        => "helper",
            "Awk_Library"       => "library",
            "Awk_Model"         => "model",
            "Awk_Private"       => "private",
            "Awk_Public"        => "public",
            "Awk_Router"        => "router",
            "Awk_Settings"      => "settings",
            "Awk_Type"          => "type",
            "Awk_View"          => "view",
        ];

        /**
         * Armazena uma referência direta ao módulo.
         * @var Awk_Module
         */
        protected $module;

        /**
         * Armazena a classe responsável pela inicialização da base.
         * @var object
         */
        protected $parent;

        /**
         * Armazena o nome da base.
         * @var string
         */
        protected $name;

        /**
         * Armazena o path da base.
         * @var Awk_Path
         */
        protected $path;

        /**
         * Construtor.
         * @param Awk_Module $module Instância do módulo.
         * @param object     $parent Instância do responsável pela construção da classe.
         */
        public function __construct($module, $parent) {
            $this->module = $module;
            $this->parent = $parent;
        }

        /**
         * Obtém o módulo da base.
         * @return Awk_Module
         */
        public function get_module() {
            return $this->module;
        }

        /**
         * Obtém o parent da base.
         * @return object
         */
        public function get_parent() {
            return $this->parent;
        }

        /**
         * Obtém o nome da base.
         * @return string
         */
        public function get_name() {
            return $this->name;
        }

        /**
         * Obtém o ID do recurso.
         * @throws Awk_Module_GetIdIsNotSupported_Exception Caso tente obter o identificador de um recurso sem suporte.
         * @return string
         */
        public function get_id() {
            // Lança uma exceção se o recurso não suportar o recurso.
            if($this instanceof Awk_Database
            || $this instanceof Awk_Settings) {
                throw new Awk_Module_GetIdIsNotSupported_Exception(ucfirst(self::$feature_mapper[get_class($this)]));
            }

            // Caso contrário, aceita a validação.
            return
                self::$feature_mapper[get_class($this)] .
                "@"  . $this->module->get_name() .
                "->" . $this->name;
        }

        /**
         * Obtém a instância do controlador de caminho.
         * @return Awk_Path
         */
        public function get_path($normalized = null) {
            return $this->path;
        }
    }
