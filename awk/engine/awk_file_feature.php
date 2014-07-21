<?php

	// Responsável pelo controle da feature file.
	class awk_file_feature extends awk_module_feature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($file_name) {
			return $this->load($file_name);
		}

		// Carrega uma instância diretamente.
		public function load($file_name) {
			$file_instance = new awk_file($this->module, $this);
			$file_instance->load($file_name);

			return $file_instance;
		}
	}
