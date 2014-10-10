<?php

    /**
     * Responsável pelo controle da feature helper.
     */
    class Awk_Helper_Feature extends Awk_Module_Feature {
        /**
         * Armazena as instâncias das helpers.
         * @var Awk_Helper[]
         */
        private $helpers = [];

        /**
         * Retorna o controle da helper.
         * @param  string $helper_id Identificador do helper.
         * @return Awk_Helper
         */
        public function feature_call($helper_id) {
            // Se já foi registrado, retorna.
            // Caso contrário será necessário carregá-lo.
            if(isset($this->helpers[$helper_id])) {
                return $this->helpers[$helper_id];
            }

            // Carrega e retorna.
            $helper_instance = new Awk_Helper($this->module, $this);
            $helper_instance->load($helper_id);

            return $this->helpers[$helper_id] = $helper_instance;
        }
    }
