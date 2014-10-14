<?php

    /**
     * Responsável pelo controle de arquivos públicos.
     */
    class Awk_Public extends Awk_Module_Base {
        /**
         * Carrega a definição do arquivo e retorna.
         * @param  string $public_name Identificador do arquivo público.
         */
        public function load($public_name) {
            $this->name = $public_name;
            $this->path = new Awk_Path($this->module->path->get() . "/publics/" . strtok($public_name, "?"));
        }

        /**
         * Obtém uma URL de acesso ao arquivo.
         * @param  boolean $include_baseurl Se deve incluir a URL base.
         * @return string
         */
        public function get_url($include_baseurl = null) {
            return ( $include_baseurl === true ? Awk_Router::get_baseurl()  : null )
                . "{$this->module->name}/publics/{$this->name}";
        }
    }
