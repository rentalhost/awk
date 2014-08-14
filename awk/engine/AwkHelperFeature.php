<?php

	// Responsável pelo controle da feature helper.
	class AwkHelperFeature extends AwkModuleFeature {
		// Armazena as instâncias das helpers.
		// @type array<string, AwkHelper>;
		private $helpers = [
		];

		/** FEATURE CALL */
		// Retorna o controle da helper.
		public function feature_call($helper_id) {
			// Se já foi registrado, retorna.
			// Caso contrário será necessário carregá-lo.
			if(isset($this->helpers[$helper_id])) {
				return $this->helpers[$helper_id];
			}

			// Carrega e retorna.
			$helper_instance = new AwkHelper($this->module, $this);
			$helper_instance->load($helper_id);

			return $this->helpers[$helper_id] = $helper_instance;
		}
	}
