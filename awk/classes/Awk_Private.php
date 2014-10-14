<?php

    /**
     * Responsável pelo controle de arquivos privados.
     */
    class Awk_Private extends Awk_Module_Base {
        /**
         * Carrega a definição do arquivo e retorna.
         * @param  string $private_name Identificador do arquivo privado.
         */
        public function load($private_name) {
            $this->name = $private_name;
            $this->path = new Awk_Path($this->module->path->get() . "/privates/" . $private_name);
        }
    }
