<?php

	// Responsável pela definição das rotas.
	class awk_router extends awk_base {
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
					"type" => awk_error::TYPE_FATAL,
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
		// Adiciona uma nova rota de passagem.
		public function add_passage($passage_callback) {
			$this->routes[] = new awk_router_route([
				"router" => $this,
				"callback" => $passage_callback
			]);
		}

		// Adiciona uma nova rota ao roteador.
		public function add_route($route_definition, $route_callback) {
			$this->routes[] = new awk_router_route([
				"router" => $this,
				"definition" => $route_definition,
				"callback" => $route_callback
			]);
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
			return ltrim(substr($router_url, strlen(basename($_SERVER["SCRIPT_NAME"])) + 1), "/");
		}
	}
