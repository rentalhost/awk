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
			// Verifica se há um fallback.
			// Se houver, ele será executado com o driver e finalizará a resolução.
			$router_fallback = $this->current_router->get_fallback();
			if($router_fallback) {
				$this->call($router_fallback);
				return;
			}
		}

		/** CALLBACK CALLER */
		// Chama o callback com o driver.
		private function call($callback) {
			return call_user_func($callback, $this);
		}
	}
