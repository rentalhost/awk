<?php

    /**
     * Responsável pelo controle da feature public.
     */
    class Awk_Public_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Carrega uma instância imediatamente.
         * @param  string     $public_name Identificador do arquivo público.
         * @return Awk_Public
         */
        public function load($public_name) {
            $public_instance = new Awk_Public($this->module, $this);
            $public_instance->load($public_name);

            return $public_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "publics", false);
        }
    }
