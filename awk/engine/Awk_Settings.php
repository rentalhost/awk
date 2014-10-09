<?php

    /**
     * Responsável pela definição de configurações.
     */
    class Awk_Settings extends Awk_Module_Base {
        /**
         * Define o tipo de recurso.
         * @var string
         */
        static protected $feature_type = "settings";

        /**
         * Armazena o path de sobreposição de configurações.
         * @var string
         */
        private $overwrite_path;

        /**
         * Armazena se o caminho de sobreposição existe.
         * @var boolean
         */
        private $overwrite_exists;

        /**
         * Armazena as configurações.
         * @var mixed[]
         */
        private $settings = [];

        /**
         * Carrega as settings e o retorna.
         * @codeCoverageIgnore
         */
        public function load() {
            $this->path = $this->module->get_path() . "/settings.php";

            // Para ser um módulo válido, é esperado que o arquivo "settings.php" exista,\
            // então, carrega o arquivo.
            $this->module->include_clean($this->path, [ "settings" => $this ]);

            // Define o caminho de sobreposição.
            $this->overwrite_path = $this->module->get_path() . "/../settings." . $this->module->get_name() . ".php";
            $this->overwrite_exists = is_readable($this->overwrite_path);

            // Se o arquivo de sobreposição existe, ele é executado.
            if($this->overwrite_exists) {
                $this->module->include_clean($this->overwrite_path, [ "settings" => $this ]);
            }
        }

        /**
         * Retorna o caminho do arquivo de sobreposição.
         * @return string
         */
        public function overwrite_path() {
            return Awk_Path::normalize($this->overwrite_path);
        }

        /**
         * Retorna se há um arquivo de sobreposição.
         * Não necessariamente indicará se houve alguma sobreposição de dados.
         * @return boolean
         */
        public function overwrite_exists() {
            return $this->overwrite_exists;
        }

        /**
         * Define uma configuração.
         * @param string $key   Chave de configuração que será definida.
         * @param mixed  $value Valor que será aplicado na chave.
         */
        public function __set($key, $value) {
            $this->settings[$key] = $value;
        }

        /**
         * Obtém uma configuração.
         * @param  string $key Chave de configuração que será obtida.
         * @return mixed
         */
        public function __get($key) {
            return $this->settings[$key];
        }

        /**
         * Verifica se uma configuração foi definida.
         * @param  string  $key Chave de configuração que será verificada.
         * @return boolean
         */
        public function __isset($key) {
            return array_key_exists($key, $this->settings);
        }

        /**
         * Remove a definição de uma configuração.
         * @param string $key Chave de configuração que será removida.
         */
        public function __unset($key) {
            unset($this->settings[$key]);
        }
    }
