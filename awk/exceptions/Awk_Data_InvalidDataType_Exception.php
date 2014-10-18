<?php

    /**
     * O tipo de dado informado não é suportado.
     */
    class Awk_Data_InvalidDataType_Exception extends Awk_Data_Exception {
        /**
         * Constrói a exceção.
         * @param mixed $object Objeto que gerou a falha.
         */
        public function __construct($object) {
            $object_type = is_object($object) ? get_class($object) : gettype($object);
            parent::__construct("O recurso Data não suporta múltiplas definições sobre {$object_type}.");
        }
    }
