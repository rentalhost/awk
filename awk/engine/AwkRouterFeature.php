<?php

	// Responsável pelo controle da feature router.
	class AwkRouterFeature extends AwkModuleFeature {
		// Armazena os roteadores instanciados.
		// @type array<string, AwkRouter>;
		private $routers = [
		];

		/** FEATURE CALL */
		// Carrega um router imediatamente.
		public function feature_call($router_id) {
			// Se o router já foi iniciado, apenas o retorna.
			if(isset($this->routers[$router_id])) {
				return $this->routers[$router_id];
			}

			// Caso contrário, o constrói e o armazena.
			$router_instance = new AwkRouter($this->module, $this);
			$router_instance->load($router_id);

			return $this->routers[$router_id] = $router_instance;
		}

		/** EXISTS */
		// Verifica se um determinado roteador existe.
		public function exists($router_id) {
			return is_readable($this->module->get_path() . "/routers/{$router_id}.php");
		}
	}
