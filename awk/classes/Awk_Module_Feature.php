<?php

    /**
     * Responsável pela definição das features dos módulos.
     */
    class Awk_Module_Feature {
        /**
         * Armazena o módulo responsável.
         * @var Awk_Module
         */
        public $module;

        /**
         * Mapa de features sem suporte ao recurso de verificação de existência.
         * @var string[]
         */
        static private $exists_mapper = [
            "databases" => "Database",
            "settings"  => "Settings",
            "sessions"  => "Session"
        ];

        /**
         * Constrói uma feature.
         * @param Awk_Module $module Instâncoa do módulo.
         */
        public function __construct($module) {
            $this->module = $module;
        }

        /**
         * Verifica se um objeto existe em um recurso do módulo.
         * @param string $object_name       Nome do objeto a ser testado.
         * @param string $feature_directory Nome do recurso a ser testado.
         * @param string $object_extension  Extensão do objeto, se aplicável.
         * @return boolean
         */
        protected function exists($object_name, $feature_directory, $object_extension = null) {
            // Se o diretório não for suportado, lança uma exceção.
            if(array_key_exists($feature_directory, self::$exists_mapper)) {
                throw new Awk_Module_ExistsNotSupported_Exception(self::$exists_mapper[$feature_directory]);
            }

            // Determina o path a ser verificado.
            $object_path = $this->module->path->get() . "/{$feature_directory}/{$object_name}";

            // Aplica a extensão do projeto, se aplicável.
            // Por padrão, aplica a extensão php.
            if($object_extension === null) {
                $object_path.= ".php";
            }

            // Verifica se é um arquivo e se é legível.
            return is_file($object_path)
                && is_readable($object_path);
        }
    }
