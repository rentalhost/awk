<?php

	// Responsável pelo controle da feature public.
	class Awk_Public_Feature extends Awk_Module_Feature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($public_name) {
			return $this->load($public_name);
		}

		// Carrega uma instância diretamente.
		public function load($public_name) {
			$public_instance = new Awk_Public($this->module, $this);
			$public_instance->load($public_name);

			return $public_instance;
		}
	}
