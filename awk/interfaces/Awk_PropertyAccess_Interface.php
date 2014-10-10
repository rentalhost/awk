<?php

    /**
     * Define um objeto onde suas propriedades podem ser obtidas, redefinidas,
     * verificadas e removidas.
     */
    interface Awk_PropertyAccess_Interface {
        /**
         * Permite alterar um grupo de propriedades.
         * @param mixed[] $keys      Propriedades que serão alteradas.
         */
        public function set_array($keys);

        /**
         * Permite obter todas as propriedades definidas.
         * @return mixed[]
         */
        public function get_array();

        /**
         * Permite a leitura de uma propriedade do objeto.
         * @param  string $key Propriedade que será lida.
         * @return mixed
         */
        public function __get($key);

        /**
         * Permite alterar uma propriedade do objeto.
         * @param string $key   Propriedade que será redefinida.
         * @param mixed  $value Valor que será aplicado a propriedade.
         */
        public function __set($key, $value);

        /**
         * Permite verificar se a propriedade foi definida.
         * @param  string  $key Propriedade que será verificada.
         * @return boolean
         */
        public function __isset($key);

        /**
         * Permite remover uma propriedade definida.
         * @param string $key Propriedade que será removida.
         */
        public function __unset($key);

        /**
         * Permite apagar todas as propriedades definidas.
         */
        public function clear();
    }
