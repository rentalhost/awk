<?php

	// Responsável pela definição das rotas.
	class awk_router extends awk_module_base {
		static protected $feature_type = "router";

		// Armazena as rotas gerenciáveis por este roteador.
		// @type array<string, awk_router_route>;
		private $routes = [
		];

		/** LOAD */
		// Carrega o arquivo da rota.
		public function load($router_name) {
			$this->name = $router_name;
			$this->path = $this->module->get_path() . "/routers/{$this->name}.php";

			// Se o arquivo do roteador não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"message" => "O módulo \"" . $this->module->get_name() . "\" não possui o roteador \"{$this->name}\"."
				]);
			}

			// Se a rota for um arquivo público, define o Content-type da página.
			if($this->is_file()) {
				$finfo = new finfo(FILEINFO_MIME | FILEINFO_PRESERVE_ATIME);
				header("Content-type: " . $finfo->file($this->file_path()));
			}

			// Carrega o arquivo do roteador.
			// É neste ponto que as rotas devem ser definidas no roteador.
			$this->module->include_clean($this->path, [ "router" => $this ]);
		}

		/** ROUTES */
		// Adiciona uma rota de raíz, que só é executada quando não há mais argumentos na URL Array.
		public function add_root($root_callback) {
			$this->add_route("[awk->null]", $root_callback);
		}

		// Adiciona uma nova rota de passagem.
		public function add_passage($passage_callback) {
			$this->add_route(null, $passage_callback);
		}

		// Adiciona uma nova rota ao roteador.
		public function add_route($route_definition, $route_callback) {
			$router_instance = new awk_router_route($this);
			$router_instance->set_definition($route_definition);
			$router_instance->set_callback($route_callback);

			$this->routes[] = $router_instance;
		}

		// Obtém todas as rotas definidas no roteador.
		public function get_routes() {
			return $this->routes;
		}

		/** FILE */
		// Informa se é o roteador está gerenciando um arquivo.
		// @return boolean;
		public function is_file() {
			return isset($_SERVER["REDIRECT_PUBLICS"])
				&& is_readable($this->file_path());
		}

		// Retorna o caminho do arquivo.
		// @return string;
		public function file_path() {
			return $_SERVER["DOCUMENT_ROOT"] . ltrim($_SERVER["REDIRECT_URL"], "/");
		}

		/** HELPER */
		// Verifica se a conexão utilizada com o roteador é segura (HTTPS).
		// @return boolean;
		static public function is_secure() {
			// Verificação simplificada.
			if(!empty($_SERVER["HTTPS"])
			&& $_SERVER["HTTPS"] !== "off") {
				return true;
			}

			// Caso contrário, será necessário verificar as configurações do HTTPS.
			return $_SERVER["SERVER_PORT"] === getservbyname("https", "tcp");
		}

		// Retorna a URL base.
		// @return string;
		static public function get_baseurl() {
			return ( self::is_secure() ? "https://" : "http://" )
				. $_SERVER["SERVER_NAME"]
				. dirname($_SERVER["SCRIPT_NAME"]) . "/";
		}

		// Retorna a URL acessada.
		// @return string;
		static public function get_url() {
			// Armazena a informação aqui.
			$router_url = null;

			// Se houver PATH_INFO, será utilizado.
			// Ex. /index.php/example => example
			if(isset($_SERVER["PATH_INFO"])) {
				return ltrim($_SERVER["PATH_INFO"], "/");
			}

			// Caso contrário, utilizará o método padrão, através da REQUEST_URI.
			// Ex. /example => example
			$router_url = strtok($_SERVER["REQUEST_URI"], "?") ?: $_SERVER["REQUEST_URI"];
			return trim(substr($router_url, strlen(dirname($_SERVER["SCRIPT_NAME"])) + 1), "/");
		}
	}
