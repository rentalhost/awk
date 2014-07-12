<?php

	// Responsável pela definição das features dos módulos.
	class awk_module_feature {
		// Armazena o módulo responsável.
		// @type awk_module;
		protected $module;

		/** CONSTRUCT */
		// Constrói uma feature.
		public function __construct($module) {
			$this->module = $module;
		}

		/** MODULE */
		// Retorna o módulo da feature.
		public function get_module() {
			return $this->module;
		}

		/** FEATURE CALL */
		// Este método precisa ser sobrescrito pela finalidade da feature.
		public function feature_call() {
		}

		/** EXISTS */
		// Retorna se um determinado módulo existe.
		// @param string $module_id: identificador do módulo;
		// @return boolean;
		public function exists($module_id) {
			return is_readable($this->module->get_path() . "/../{$module_id}/settings.php");
		}
	}
