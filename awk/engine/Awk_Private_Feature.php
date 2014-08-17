<?php

	// Responsável pelo controle da feature private.
	class Awk_Private_Feature extends Awk_Module_Feature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($private_name) {
			return $this->load($private_name);
		}

		// Carrega uma instância diretamente.
		public function load($private_name) {
			$private_instance = new Awk_Private($this->module, $this);
			$private_instance->load($private_name);

			return $private_instance;
		}
	}
