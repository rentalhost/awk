<?php

    /**
     * Responsável pelo controle de arquivos públicos.
     */
    class Awk_Public extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "public";

        /**
         * Armazena se o caminho do arquivo remete a um arquivo acessível.
         * @var boolean
         */
        private $exists = false;

        /**
         * Carrega a definição do arquivo e retorna.
         * @param  string $public_name Identificador do arquivo público.
         */
        public function load($public_name) {
            $this->name = $public_name;
            $this->path = $this->module->get_path() . "/publics/" . strtok($public_name, "?");
            $this->exists = is_readable($this->path);
        }

        /** URL */
        //
        // return @;
        /**
         * Obtém uma URL de acesso ao arquivo.
         * @param  boolean $include_baseurl Se deve incluir a URL base.
         * @return string
         */
        public function get_url($include_baseurl = null) {
            return ( $include_baseurl === true ? Awk_Router::get_baseurl()  : null )
                . $this->module->get_name() . "/publics/" . $this->name;
        }

        /**
         * Retorna se o arquivo existe.
         * @return boolean
         */
        public function exists() {
            return $this->exists;
        }
    }
