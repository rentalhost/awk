<?php

    /**
     * Responsável pelo controle da feature cache.
     */
    class Awk_Cache_Feature extends Awk_Module_Feature implements Awk_FeatureAbstraction_Interface {
        /**
         * Retorna o hash com base em um objeto.
         * @param  mixed $object_value Valor do objeto.
         * @return string
         */
        static public function get_object_hash($object_value) {
            // Se o nome do objeto não foi informado, então gera um nome aleatório.
            if($object_value === null) {
                $object_value = uniqid(mt_rand(), true);
            }
            else
            // Se o objeto não for uma string, serializa.
            // Isso permite que um array ou objeto sejam armazenado apropriadamente.
            if(!is_string($object_value)) {
                $object_value = serialize($object_value);
            }

            $object_hash = md5($object_value);
            return substr($object_hash, 0, 2) . "/" . substr($object_hash, 2);
        }

        /**
         * @see Awk_Cache::load
         */
        public function load($object_name = null) {
            $cache_instance = new Awk_Cache($this->module, $this);
            $cache_instance->load($object_name);

            return $cache_instance;
        }

        /**
         * @see Awk_FeatureAbstraction_Interface::exists()
         */
        public function exists($object_name) {
            return parent::exists(self::get_object_hash($object_name), "caches", false);
        }
    }
