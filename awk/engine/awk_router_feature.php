<?php

	// Responsável pelo controle da feature router.
	class awk_router_feature extends awk_module_feature {
		// Armazena os roteadores instanciados.
		// @type array<string, awk_router>;
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
			$router_instance = new awk_router($this->module, $this);
			$router_instance->load($router_id);

			return $this->routers[$router_id] = $router_instance;
		}
	}
