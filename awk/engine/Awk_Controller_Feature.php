<?php

	// Responsável pelo controle da feature controller.
	class Awk_Controller_Feature extends Awk_Module_Feature {
		// Armazena as instâncias dos controllers.
		// @type array<string, Awk_Controller>;
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
			$controller_instance = new Awk_Controller($this->module, $this);
			$controller_instance->load($controller_id);

			$this->controllers[$controller_id] = $controller_instance;
			return $controller_instance->get_instance();
		}
	}
