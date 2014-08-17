<?php

	// Responsável pelo controle da feature settings.
	class Awk_Settings_Feature extends Awk_Module_Feature {
		// Armazena a instância de settings.
		// @type Awk_Settings;
		private $settings;

		/** FEATURE CALL */
		// Carrega um settings imediatamente.
		public function feature_call() {
			// Se as configurações já foram iniciadas, retorna seu controlador.
			if(isset($this->settings)) {
				return $this->settings;
			}

			// Caso contrário, o constrói e o armazena.
			$settings_instance = new Awk_Settings($this->module, $this);
			$settings_instance->load();

			return $this->settings = $settings_instance;
		}
	}
