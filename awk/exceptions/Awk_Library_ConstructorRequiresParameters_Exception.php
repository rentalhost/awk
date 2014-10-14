<?php

    /**
     * Não é possível construir uma instância única quando o construtor requer parêmtros.
     */
    class Awk_Library_ConstructorRequiresParameters_Exception extends Awk_Library_Exception {
        /**
         * Constrói a exceção.
         * @param Awk_Module $module          Instância do módulo de referência.
         * @param string     $library_name    Nome da Library que recebeu a falha.
         */
        public function __construct($module, $library_name) {
            parent::__construct("A instância única da Library \"{$library_name}\" do módulo \"{$module->name}\" não pôde ser criada pois seu construtor possui parâmetros obrigatórios.");
        }
    }
