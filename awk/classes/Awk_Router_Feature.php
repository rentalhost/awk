<?php

    /**
     * Responsável pelo controle da feature router.
     */
    class Awk_Router_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Armazena os roteadores instanciados.
         * @var Awk_Router[]
         */
        private $routers = [];

        /**
         * Carrega um router imediatamente.
         * @param  string $router_id Identificador do roteador.
         * @return Awk_Router
         */
        public function feature_call($router_id) {
            // Se o router já foi iniciado, apenas o retorna.
            if(isset($this->routers[$router_id])) {
                return $this->routers[$router_id];
            }

            // Caso contrário, o constrói e o armazena.
            $router_instance = new Awk_Router($this->module, $this);
            $router_instance->load($router_id);

            return $this->routers[$router_id] = $router_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists($object_name, "routers");
        }
    }
