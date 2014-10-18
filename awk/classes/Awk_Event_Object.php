<?php

    /**
     * Responsável pelo controle do objeto de evento.
     */
    class Awk_Event_Object {
        /**
         * Armazena os dados do objeto.
         * @var Awk_Data
         */
        public $data;

        /**
         * Objeto atingido pelo evento.
         * @var object
         */
        public $target;

        /**
         * Armazena o tipo do evento.
         * @var string
         */
        public $type;

        /**
         * Armazena o resultado retornado pelo evento anterior.
         * @var mixed|null
         */
        public $result;
    }
