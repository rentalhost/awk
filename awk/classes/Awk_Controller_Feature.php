<?php

    /**
     * Responsável pelo controle da feature controller.
     */
    class Awk_Controller_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Armazena as instâncias dos controllers.
         * @var Awk_Controller[]
         */
        private $controllers = [];

        /**
         * @see self::load
         */
        public function feature_call($controller_id) {
            return $this->load($controller_id);
        }

        /**
         * Carrega um controller imediatamente.
         * @param  string $controller_id Identificador do controller.
         * @return Awk_Controller
         */
        public function load($controller_id) {
            // Se o controller já foi registrado, retorna.
            // Caso contrário será necessário carregá-lo.
            if(isset($this->controllers[$controller_id])) {
                return $this->controllers[$controller_id]->get_instance();
            }

            // Carrega o controller e retorna.
            $controller_instance = new Awk_Controller($this->module, $this);
            $controller_instance->load($controller_id);

            $this->controllers[$controller_id] = $controller_instance;
            return $controller_instance->get_instance();
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "controllers");
        }
    }
