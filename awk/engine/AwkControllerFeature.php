<?php

	// Responsável pelo controle da feature controller.
	class AwkControllerFeature extends AwkModuleFeature {
		// Armazena as instâncias dos controllers.
		// @type array<string, AwkController>;
		private $controllers = [
		];

		/** FEATURE CALL */
		// Carrega um controller imediatamente.
		public function feature_call($controller_id) {
			// Se o controller já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->controllers[$controller_id])) {
				return $this->controllers[$controller_id]->get_instance();
			}

			// Carrega o controller e retorna.
			$controller_instance = new AwkController($this->module, $this);
			$controller_instance->load($controller_id);

			$this->controllers[$controller_id] = $controller_instance;
			return $controller_instance->get_instance();
		}
	}
