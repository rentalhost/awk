<?php

    /**
     * Responsável pelo controle da feature library.
     */
    class Awk_Library_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Armazena as instâncias das libraries.
         * @var Awk_Library[]
         */
        private $libraries = [];

        /**
         * @see self::load
         */
        public function feature_call($library_id) {
            return $this->load($library_id);
        }

        /**
         * Retorna o gerador de instâncias da library.
         * @param  string $library_id Identificador da library.
         * @return Awk_Library
         */
        public function load($library_id) {
            // Se o gerador já foi registrado, retorna.
            // Caso contrário será necessário carregá-lo.
            if(isset($this->libraries[$library_id])) {
                return $this->libraries[$library_id];
            }

            // Carrega o gerador e retorna.
            $library_instance = new Awk_Library($this->module, $this);
            $library_instance->load($library_id);

            return $this->libraries[$library_id] = $library_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "libraries");
        }
    }
