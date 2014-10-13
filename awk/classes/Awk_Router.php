<?php

    /**
     * Responsável pela definição das rotas.
     */
    class Awk_Router extends Awk_Module_Base {
        /**
         * Armazena as rotas gerenciáveis por este roteador.
         * @var Awk_Router_Route[]
         */
        private $routes = [];

        /**
         * Carrega o arquivo da rota.
         * @param  string $router_name Identificador do roteador.
         * @throws Awk_Router_NotExists_Exception Caso o Router não exista no módulo.
         */
        public function load($router_name) {
            $this->name = $router_name;
            $this->path = new Awk_Path($this->module->get_path()->get() . "/routers/{$this->name}.php");

            // Se o arquivo do roteador não existir, lança um erro.
            if(!$this->path->is_file()
            || !$this->path->is_readable()) {
                throw new Awk_Router_NotExists_Exception($this->module, $this->name);
            }

            // Se a rota for um arquivo público, define o Content-type da página.
            if($this->is_file()) {
                $finfo = new finfo(FILEINFO_MIME | FILEINFO_PRESERVE_ATIME);
                header("Content-type: " . $finfo->file($this->file_path()));
            }

            // Carrega o arquivo do roteador.
            // É neste ponto que as rotas devem ser definidas no roteador.
            $this->module->include_clean($this->path->get(), [ "router" => $this ]);
        }

        /**
         * Adiciona uma rota de raíz, que só é executada quando não há mais argumentos na URL Array.
         * @param callable|string $root_callback Definição do callable ou de um identificador.
         */
        public function add_root($root_callback) {
            $this->add_route("[awk->null]", $root_callback);
        }

        /**
         * Adiciona uma nova rota de passagem.
         * @param callable|string $passage_callback Definição do callable ou de um identificador.
         */
        public function add_passage($passage_callback) {
            $this->add_route(null, $passage_callback);
        }

        /**
         * Adiciona uma rota exclusiva para arquivos.
         * @param callable|string $route_callback Definição do callable ou de um identificador.
         */
        public function add_file_passage($route_callback) {
            $router_instance = new Awk_Router_Route($this);
            $router_instance->set_callback($route_callback);
            $router_instance->set_file_mode();

            $this->routes[] = $router_instance;
        }

        /**
         * Adiciona uma nova rota ao roteador.
         * @param string   $route_definition Definição da rota.
         * @param callable|string $route_callback   Definição do callable ou de um identificador.
         */
        public function add_route($route_definition, $route_callback) {
            $router_instance = new Awk_Router_Route($this);
            $router_instance->set_definition($route_definition);
            $router_instance->set_callback($route_callback);

            $this->routes[] = $router_instance;
        }

        /**
         * Obtém todas as rotas definidas no roteador.
         * @return Awk_Router_Route[]
         */
        public function get_routes() {
            return $this->routes;
        }

        /**
         * Informa se é o roteador está gerenciando um arquivo.
         * @return boolean
         */
        public function is_file() {
            return isset($_SERVER["REDIRECT_PUBLICS"])
                && is_readable($this->file_path());
        }

        /**
         * Retorna o caminho do arquivo.
         * @return string
         */
        public function file_path() {
            $result = rtrim($_SERVER["DOCUMENT_ROOT"], "/") . "/";

            if(!empty($_SERVER["REDIRECT_URL"])) {
                $result.= ltrim($_SERVER["REDIRECT_URL"], "/");
            }

            return $result;
        }

        /**
         * Verifica se a conexão utilizada com o roteador é segura (HTTPS).
         * @return boolean
         */
        static public function is_secure() {
            // Verificação simplificada.
            if(!empty($_SERVER["HTTPS"])
            && $_SERVER["HTTPS"] !== "off") {
                return true;
            }

            // Caso contrário, será necessário verificar as configurações do HTTPS.
            return $_SERVER["SERVER_PORT"] === getservbyname("https", "tcp");
        }

        /**
         * Retorna a URL base.
         * @return string
         */
        static public function get_baseurl() {
            return ( self::is_secure() ? "https://" : "http://" )
                . $_SERVER["SERVER_NAME"]
                . ltrim(dirname($_SERVER["SCRIPT_NAME"]), DIRECTORY_SEPARATOR) . "/";
        }

        /**
         * Retorna a URL acessada.
         * @return string
         */
        static public function get_url() {
            // Armazena a informação aqui.
            $router_url = null;

            // Se houver PATH_INFO, será utilizado.
            // Ex. /index.php/example/ => example
            if(isset($_SERVER["PATH_INFO"])) {
                return trim($_SERVER["PATH_INFO"], "/");
            }

            // Caso contrário, utilizará o método padrão, através da REQUEST_URI.
            // Ex. /example => example
            $router_url = strtok($_SERVER["REQUEST_URI"], "?") ?: $_SERVER["REQUEST_URI"];
            return trim(substr($router_url, strlen(dirname($_SERVER["SCRIPT_NAME"])) + 1), "/");
        }
    }
