<?php

	// Responsável pela interação com as rotas.
	class awk_router_driver {
		// Define o roteador que está sendo processado atualmente.
		private $current_router;

		/** ROUTER */
		// Define o roteador atual.
		public function set_router($router) {
			$this->current_router = $router;
		}

		/** SOLVE */
		// Inicia o processo sobre uma URL.
		public function solve($url_array) {
			// Obtém todas as rotas definidas no roteador.
			// Será necessário testar uma a uma, até encontrar uma que possa ser resolvida.
			$router_routes = $this->current_router->get_routes();
			foreach($router_routes as $router_route) {
				// Verifica se a rota atual pode ser resolvida.
				// Se puder, seu callback será executado.
				if($router_route->test($url_array)) {
					$this->call($router_route->get_callback());
					return;
				}
			}

			// Verifica se há um fallback.
			// Se houver, ele será executado com o driver e finalizará a resolução.
			$router_fallback = $this->current_router->get_fallback();
			if($router_fallback) {
				$this->call($router_fallback);
				return;
			}

			// Se o driver falhar, então força um erro de objeto não encontrado.
			awk_error::force_404();
		}

		/** CALLBACK CALLER */
		// Chama o callback com o driver.
		private function call($callback) {
			return call_user_func($callback, $this);
		}
	}
