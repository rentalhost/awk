<?php

    /**
     * Responsável por gerenciar os módulos e suas propriedades.
     */
    class Awk_Module {
        /**
         * Armazena as instâncias dos módulos carregados.
         * @var self
         */
        static private $modules = [];

        /**
         * Nome do módulo.
         * @var string
         */
        public $name;

        /**
         * Caminho absoluto do módulo.
         * @var Awk_Path
         */
        public $path;

        /**
         * Caminho dos dados globais.
         * @var Awk_Data
         */
        public $globals;

        /**
         * Constrói uma nova instância de módulo.
         * @param string $module_name Identificador do módulo.
         * @throws Awk_Module_NotExists_Exception       Caso o módulo não exista.
         * @throws Awk_Module_WithoutSettings_Exception
         *         Caso o módulo não tenha definido seu arquivo de configuração.
         */
        private function __construct($module_name) {
            $this->name = $module_name;
            $this->path = new Awk_Path(__DIR__ . "/../../{$module_name}");

            // Inicia a variável global do módulo.
            $this->globals = new Awk_Data;

            // Se o caminho informado não existir, gera um erro.
            if(!$this->path->is_dir()
            || !$this->path->is_readable()) {
                throw new Awk_Module_NotExists_Exception($module_name);
            }

            // Se o arquivo de configuração (settings.php) não existe, gera um erro.
            // Módulos devem possuir este arquivo para indicar um módulo valido.
            $module_settings_path = new Awk_Path($this->path->get() . "/settings.php");
            if(!$module_settings_path->is_file()
            || !$module_settings_path->is_readable()) {
                throw new Awk_Module_WithoutSettings_Exception($module_name);
            }
        }

        /**
         * Define um mapa de features, ligando a sua classe.
         * Os dados são informados pluralizados.
         * @var string[]
         */
        static private $features_mapper = [
            "routers"       => "Awk_Router_Feature",
            "controllers"   => "Awk_Controller_Feature",
            "libraries"     => "Awk_Library_Feature",
            "helpers"       => "Awk_Helper_Feature",
            "views"         => "Awk_View_Feature",
            "databases"     => "Awk_Database_Feature",
            "settings"      => "Awk_Settings_Feature",
            "modules"       => "Awk_Module_Feature",
            "types"         => "Awk_Type_Feature",
            "publics"       => "Awk_Public_Feature",
            "privates"      => "Awk_Private_Feature",
            "caches"        => "Awk_Cache_Feature",
            "sessions"      => "Awk_Session_Feature",
            "models"        => "Awk_Model_Feature",
        ];

        /**
         * Armazena definições de plurais não linear.
         * @example "library" -> "libraries"
         * @var string[]
         */
        static private $features_normalizers = [
            "library"   => "libraries",
            "settings"  => "settings"
        ];

        /**
         * Armazena as classes de features mapeadas do módulo.
         * @var object[]
         */
        private $features_instances = [];

        /**
         * Carrega uma feature através do seu nome singular.
         * @param  string $name Identificador do recurso.
         * @throws Awk_Module_UnsupportedFeature_Exception Caso o recurso não seja suportado.
         * @return object
         */
        private function load_feature($name) {
            // Se a feature já foi carregada, a retorna.
            // Caso contrário será necessário carregá-la.
            if(isset($this->features_instances[$name])) {
                return $this->features_instances[$name];
            }

            // Verifica se é uma feature mapeada.
            // Se não for, será necessário lançar um erro.
            if(!isset(self::$features_mapper[$name])) {
                throw new Awk_Module_UnsupportedFeature_Exception($name);
            }

            // Gera e retorna a instância da feature.
            $feature_reflection = new ReflectionClass(self::$features_mapper[$name]);
            return $this->features_instances[$name] = $feature_reflection->newInstance($this);
        }

        /**
         * Carrega e retorna a resposta da instância de uma feature via método.
         * @param  string  $method      Identificador da feature.
         * @param  mixed[] $method_args Argumentos que serão enviados.
         * @return mixed
         */
        public function __call($method, $method_args) {
            // Determina a pluralização do método para acessar o mapper.
            // Exemplo: view -> views;
            $method_plural =
                isset(self::$features_normalizers[$method])
                ? self::$features_normalizers[$method]
                : "{$method}s";

            // Carrega a feature através do nome informado.
            $feature_instance = $this->load_feature($method_plural);

            // Executa o método load() passando os parâmetros recebidos.
            return call_user_func_array([ $feature_instance, "load" ], $method_args);
        }

        /**
         * Carrega e retorna a instância de uma feature via propriedade.
         * @param  string $key Identificador da feature.
         * @return object
         */
        public function __get($key) {
            return $this->load_feature($key);
        }

        /**
         * Identifica uma string e retorna o callback.
         * @param  string  $id              Informação que será identificada.
         * @param  string  $feature_type    Tipo de recurso padrão, quando não informado.
         * @param  boolean $feature_blocked Se o recurso padrão não deve ser alterado.
         * @param  boolean $module_required Se o módulo deve ser explicito na informação.
         * @return object|mixed[]
         */
        public function identify($id, $feature_type = null, $feature_blocked = null, $module_required = null) {
            $identifier_instance = new Awk_Module_Identifier;
            $identifier_instance->feature = $feature_type;

            // Indica se haverá o bloqueio da feature.
            if($feature_blocked === true) {
                $identifier_instance->set_feature_blocked();
            }

            // Indica se um módulo é esperado.
            if($module_required === true) {
                $identifier_instance->set_module_required();
            }
            // Caso contrário, aplica o próprio módulo.
            else {
                $identifier_instance->module = $this;
            }

            return $identifier_instance->identify($id);
        }

        /**
         * Carrega e retorna um módulo.
         * @param  string $module_id Identificador do módulo.
         * @return Awk_Module
         */
        static public function get($module_id) {
            // Se o módulo já foi carregado, retorna sua instância.
            if(isset(self::$modules[$module_id])){
                return self::$modules[$module_id];
            }

            // Caso contrário, cria sua instância.
            self::$modules[$module_id] = new self($module_id);
            self::$modules[$module_id]->settings();

            return self::$modules[$module_id];
        }

        /**
         * Inclui um arquivo com referência no módulo.
         * Nota: o nome dos parâmetros estarão disponível também no arquivo.
         * @param  string  $include_file Caminho do arquivo a ser incluído.
         * @param  mixed[] $include_args Argumentos que serão enviados ao arquivo (como variáveis).
         * @param  boolean $include_once Se deve limitar a inclusão do arquivo.
         * @return mixed
         */
        public function include_clean($include_file, $include_args = null, $include_once = null) {
            // Define algumas variáveis básicas.
            $include_args = $include_args ?: [];
            $include_args["awk"] = self::$modules["awk"];
            $include_args["module"] = $this;

            // Extrai os argumentos para o arquivo.
            extract($this->globals->get_array(), EXTR_REFS);
            extract($include_args);

            // Inclui e retorna o valor do arquivo.
            return $include_once !== true
                ? include $include_file
                : include_once $include_file;
        }

        /**
         * Retorna se está em um ambiente local de desenvolvimento.
         * @return boolean
         */
        public function is_localhost() {
            return is_readable($_SERVER["DOCUMENT_ROOT"] . "/awk.localhost");
        }

        /**
         * Verifica se está em um ambiente de desenvolvimento.
         * @return boolean
         */
        public function is_development() {
            return Awk::$module->settings()->project_development_mode === true;
        }

        /**
         * Retorna se um determinado módulo existe.
         * @param  string $module_id Identificador do módulo.
         * @return boolean
         */
        static public function exists($module_id) {
            return is_readable(getcwd() . "/{$module_id}/settings.php");
        }
    }
