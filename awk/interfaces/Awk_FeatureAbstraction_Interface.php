<?php

    /**
     * Responsável pela abstração do recurso.
     */
    interface Awk_FeatureAbstraction_Interface {
        /**
         * Verifica se um objeto do recurso existe.
         * @param  string  $object_name Nome do objeto a ser verificado.
         * @return boolean
         */
        public function exists($object_name);
    }
