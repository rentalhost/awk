<?php

	// Responsável pela definição das rotas.
	class awk_router extends awk_base {
		// Armazena o callback de fallback.
		// @type callback;
		private $route_fallback;

		/** LOAD */
		// Carrega o arquivo da rota.
		public function load($router_id) {
			$this->id = $router_id;
			$this->path = $this->module->get_path() . "/routers/{$this->id}.php";

			// Se o arquivo do roteador não existir, lança um erro.
			// @error generic;
			if(!is_readable($this->path)) {
				awk_error::create([
					"fatal" => true,
					"message" => "O módulo \"" . $this->module->get_id() . "\" não possui o roteador \"{$this->id}\"."
				]);
			}

			// Carrega o arquivo do roteador.
			// É neste ponto que as rotas devem ser definidas no roteador.
			$this->module->include_clean($this->path, [ "router" => $this ]);
		}

		/** ROUTES */
		// Define uma rota de fallback.
		// Este método será executado quando não for possível resolver uma rota.
		// Se /null/ for informado o fallback será desativado.
		public function set_fallback($callback) {
			$this->route_fallback = $callback;
		}

		// Retorna o fallback.
		public function get_fallback() {
			return $this->route_fallback;
		}

		/** SOLVE */
		// Resolve uma rota até a execução de seu callback.
		public function solve($url) {
			// Divide a URL em um array, eliminando espaços vazios.
			// Ex. "1//2//3" => ["1", "2", "3"]
			$url_array = array_filter(explode("/", $url), "strlen");

			// Inicia um driver que navegará sobre as rotas.
			$router_driver = new awk_router_driver();
			$router_driver->set_router($this);
			$router_driver->solve($url_array);
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
