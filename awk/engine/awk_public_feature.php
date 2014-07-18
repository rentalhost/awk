<?php

	// Responsável pelo controle da feature public.
	class awk_public_feature extends awk_module_feature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($public_name) {
			return $this->load($public_name);
		}

		// Carrega uma instância diretamente.
		public function load($public_name) {
			$public_instance = new awk_public($this->module, $this);
			$public_instance->load($public_name);

			return $public_instance;
		}
	}
