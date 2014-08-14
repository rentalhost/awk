<?php

	// Responsável pelo controle da feature settings.
	class AwkSettingsFeature extends AwkModuleFeature {
		// Armazena a instância de settings.
		// @type AwkSettings;
		private $settings;

		/** FEATURE CALL */
		// Carrega um settings imediatamente.
		public function feature_call() {
			// Se as configurações já foram iniciadas, retorna seu controlador.
			if(isset($this->settings)) {
				return $this->settings;
			}

			// Caso contrário, o constrói e o armazena.
			$settings_instance = new AwkSettings($this->module, $this);
			$settings_instance->load();

			return $this->settings = $settings_instance;
		}
	}
