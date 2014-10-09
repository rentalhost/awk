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
        private $name;

        /**
         * Caminho absoluto do módulo.
         * @var string
         */
        private $path;

        /**
         * Caminho dos dados globais.
         * @var Awk_Data
         */
        private $globals;

        /**
         * Retorna o identificador do módulo.
         * @return string
         */
        public function get_name() {
            return $this->name;
        }

        /**
         * Retorna o caminho absoluto do módulo.
         * @return string
         */
        public function get_path() {
            return $this->path;
        }

        /**
         * Constrói uma nova instância de módulo.
         * @param string $module_name Identificador do módulo.
         */
        private function __construct($module_name) {
            $this->name = $module_name;
            $this->path = __DIR__ . "/../../{$module_name}";

            // Inicia a variável global do módulo.
            $this->globals = new Awk_Data;

            // Se o caminho informado não existir, gera um erro.
            if(!is_dir($this->path)) {
                Awk_Error::create([
                    "message" => "O módulo \"{$module_name}\" não existe."
                ]);
            } // @codeCoverageIgnore

            // Se o arquivo de configuração (settings.php) não existe, gera um erro.
            // Módulos devem possuir este arquivo para indicar um módulo valido.
            $module_settings_path = "{$this->path}/settings.php";
            if(!is_file($module_settings_path)) {
                Awk_Error::create([
                    "message" => "O módulo \"{$module_name}\" não definiu o arquivo de configuração."
                ]);
            } // @codeCoverageIgnore
        } // @codeCoverageIgnore

        /**
         * Define um mapa de features, ligando a sua classe.
         * Os dados são informados pluralizados.
         * @var string[]
         */
        static private $features_mapper = [
            "routers" => "Awk_Router_Feature",
            "controllers" => "Awk_Controller_Feature",
            "libraries" => "Awk_Library_Feature",
            "helpers" => "Awk_Helper_Feature",
            "views" => "Awk_View_Feature",
            "databases" => "Awk_Database_Feature",
            "settings" => "Awk_Settings_Feature",
            "modules" => "Awk_Module_Feature",
            "types" => "Awk_Type_Feature",
            "publics" => "Awk_Public_Feature",
            "privates" => "Awk_Private_Feature",
            "sessions" => "Awk_Session_Feature",
            "models" => "Awk_Model_Feature",
        ];

        /**
         * Armazena definições de plurais não linear.
         * @example "library" -> "libraries"
         * @var string[]
         */
        static private $features_normalizers = [
            "library" => "libraries",
            "settings" => "settings"
        ];

        /**
         * Armazena as classes de features mapeadas do módulo.
         * @var object[]
         */
        private $features_instances = [];

        /**
         * Carrega uma feature através do seu nome singular.
         * @param  string $name Identificador do recurso.
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
                Awk_Error::create([
                    "message" => "O recurso \"{$name}\" não está disponível."
                ]);
            } // @codeCoverageIgnore

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

            // Executa a `feature_call()` passando os parâmetros recebidos.
            return call_user_func_array([ $feature_instance, "feature_call" ], $method_args);
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
         * @param  string  $id                   Informação que será identificada.
         * @param  string  $feature_type         Tipo de recurso padrão, quando não informado.
         * @param  boolean $feature_type_blocked Se o recurso padrão não deve ser alterado.
         * @param  boolean $module_required      Se o módulo deve ser explicito na informação.
         * @param  boolean $return_parts         Se as partes devem ser retornadas como um array, ao invés de um objeto.
         * @return object|mixed[]
         */
        public function identify($id, $feature_type = null, $feature_type_blocked = null, $module_required = null, $return_parts = null) {
            // Executa a tarefa de identificação, separando cada parte.
            $id_validate = preg_match("/^
                (?<feature>\w+\@)?
                (?<module>\w+\-\>)?
                (?<name>[\w\/\.]+)
                (?<method>::\w+)?
            $/x", $id, $id_match);

            if($id_validate) {
                // Módulo que será utilizado. Por padrão, o próprio módulo.
                // @type Awk_Module;
                $module_instance = $this;
                if(!empty($id_match["module"])) {
                    $module_instance = self::get(substr($id_match["module"], 0, -2));
                }
                else
                if($module_required === true) {
                    Awk_Error::create([
                        "message" => "Não foi possível identificar \"{$id}\". A definição do módulo é obrigatória."
                    ]);
                } // @codeCoverageIgnore

                // Define a feature a ser utilizada.
                // Se uma feature é obrigatória (null), mas não foi definida, gera um erro.
                if($feature_type === null
                && empty($id_match["feature"])) {
                    Awk_Error::create([
                        "message" => "Não foi possível identificar \"{$id}\". A definição do recurso é obrigatória."
                    ]);
                } // @codeCoverageIgnore
                // Se a feature foi definida, mas há necessidade de um bloqueio, gera um erro.
                if($feature_type_blocked === true
                && $feature_type !== null
                && !empty($id_match["feature"])
                && $feature_type !== substr($id_match["feature"], 0, -1)) {
                    Awk_Error::create([
                        "message" => "Não foi possível identificar \"{$id}\". O recurso deve ser \"{$feature_type}\"."
                    ]);
                } // @codeCoverageIgnore
                // Em último caso, define a feature que será utilizada.
                else
                if(!empty($id_match["feature"])) {
                    $feature_type = substr($id_match["feature"], 0, -1);
                }

                // Identifica um método.
                $method_name = null;
                if(!empty($id_match["method"])) {
                    $method_name = substr($id_match["method"], 2);
                }

                // Retorna as instâncias das partes identificadas.
                if($return_parts === true) {
                    return [
                        "feature" => $feature_type,
                        "module" => $module_instance,
                        "name" => $id_match["name"],
                        "method" => $method_name
                    ];
                }

                // Após coletar todos os dados necessários, carrega o objeto.
                $object_instance = $module_instance->__call($feature_type, [ $id_match["name"] ]);

                // Se um método foi informado, retorna um callable.
                if($method_name !== null) {
                    return [ $object_instance, $method_name ];
                }

                // Caso contrário, apenas retorna o objeto.
                return $object_instance;
            }

            // Se não foi possível validar, lança uma exceção.
            Awk_Error::create([
                "message" => "Não foi possível identificar \"{$id}\"."
            ]);
        } // @codeCoverageIgnore

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
         * Obtém os dados globais do módulo.
         * @return Awk_Data
         */
        public function &get_globals() {
            return $this->globals;
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
            extract($this->globals->get_all(), EXTR_REFS);
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
            return Awk_Module::get("awk")->settings()->project_development_mode === true;
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
