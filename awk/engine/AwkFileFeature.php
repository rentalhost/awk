<?php

	// Responsável pelo controle da feature file.
	class AwkFileFeature extends AwkModuleFeature {
		/** FEATURE CALL */
		// Carrega uma instância imediatamente.
		public function feature_call($file_name) {
			return $this->load($file_name);
		}

		// Carrega uma instância diretamente.
		public function load($file_name) {
			$file_instance = new AwkFile($this->module, $this);
			$file_instance->load($file_name);

			return $file_instance;
		}
	}
