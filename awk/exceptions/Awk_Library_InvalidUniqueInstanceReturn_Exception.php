<?php

    /**
     * A instância única retornada não é válida para a classe registrada.
     */
    class Awk_Library_InvalidUniqueInstanceReturn_Exception extends Awk_Library_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $library_name    Nome da Library que recebeu a falha.
         */
        public function __construct($module, $library_name) {
            parent::__construct("O método \"library_unique()\" da Library \"{$library_name}\" " .
                "do módulo \"" . $module->get_name() . "\" não retornou uma instância da classe registrada.");
        }
    }
