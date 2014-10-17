<?php

    /**
     * Responsável pelo controle da feature settings.
     */
    class Awk_Settings_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Armazena a instância de settings.
         * @var Awk_Settings
         */
        private $settings;

        /**
         * @see self::load
         */
        public function feature_call() {
            return $this->load();
        }

        /**
         * Carrega um settings imediatamente.
         * @codeCoverageIgnore
         * @return Awk_Settings
         */
        public function load() {
            // Se as configurações já foram iniciadas, retorna seu controlador.
            if(isset($this->settings)) {
                return $this->settings;
            }

            // Caso contrário, o constrói e o armazena.
            $settings_instance = new Awk_Settings($this->module, $this);
            $settings_instance->load();

            return $this->settings = $settings_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "settings");
        }
    }
