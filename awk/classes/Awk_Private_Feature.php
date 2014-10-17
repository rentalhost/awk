<?php

    /**
     * Responsável pelo controle da feature private.
     */
    class Awk_Private_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Carrega uma instância diretamente.
         * @param  string $private_name Identificador do arquivo privado.
         * @return Awk_Private
         */
        public function load($private_name) {
            $private_instance = new Awk_Private($this->module, $this);
            $private_instance->load($private_name);

            return $private_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "privates", false);
        }
    }
