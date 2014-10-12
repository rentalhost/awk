<?php

    /**
     * Responsável pela definição de configurações.
     */
    class Awk_Settings extends Awk_Module_Base implements Awk_PropertyAccess_Interface {
        /**
         * Determina o nome da instância.
         * Atualmente, todas as instâncias são chamadas de "default".
         * @var string
         */
        protected $name = "default";

        /**
         * Armazena o path de sobreposição de configurações.
         * @var Awk_Path
         */
        private $overwrite_path;

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
            $module_path = $this->module->get_path()->get();
            $this->path = new Awk_Path("{$module_path}/settings.php");

            // Para ser um módulo válido, é esperado que o arquivo "settings.php" exista,
            // então, carrega o arquivo.
            $this->module->include_clean($this->path->get(), [ "settings" => $this ]);

            // Define o caminho de sobreposição.
            $this->overwrite_path = new Awk_Path("{$module_path}/../settings." . $this->module->get_name() . ".php");

            // Se o arquivo de sobreposição existe, ele é executado.
            if($this->overwrite_path->is_file()
            && $this->overwrite_path->is_readable()) {
                $this->module->include_clean($this->overwrite_path->get(), [ "settings" => $this ]);
            }
        }

        /**
         * Retorna o caminho do arquivo de sobreposição.
         * @return Awk_Path
         */
        public function get_overwrited_path() {
            return $this->overwrite_path;
        }

        /**
         * Define várias configurações.
         * @param mixed[] $keys Configurações que serão definidas.
         */
        public function set_array($keys) {
            $this->settings = array_replace($this->settings, $keys);
        }

        /**
         * Retorna todas as configurações.
         * @return mixed[]
         */
        public function get_array() {
            return $this->settings;
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

        /**
         * Remove todas as definições definidas.
         */
        public function clear() {
            $this->settings = [];
        }
    }
