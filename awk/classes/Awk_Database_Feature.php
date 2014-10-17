<?php

    /**
     * Responsável pelo controle da feature database.
     */
    class Awk_Database_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Armazena as instâncias das databases.
         * @var Awk_Database[]
         */
        private $databases = [];

        /**
         * Retorna o controle da database.
         * @param  string $database_id Identificador do database.
         * @return Awk_Database
         */
        public function load($database_id = null) {
            $database_id = $database_id ?: "default";

            // Se já foi registrado, retorna.
            // Caso contrário será necessário carregá-lo.
            if(isset($this->databases[$database_id])) {
                return $this->databases[$database_id];
            }

            // Carrega e retorna.
            return $this->databases[$database_id] = new Awk_Database($this->module, $this);
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "databases");
        }
    }
