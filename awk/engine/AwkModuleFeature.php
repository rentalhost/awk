<?php

	// Responsável pela definição das features dos módulos.
	class AwkModuleFeature {
		// Armazena o módulo responsável.
		// @type AwkModule;
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
		} // @codeCoverageIgnore
	}
