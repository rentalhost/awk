<?php

	// Responsável pela definição das features dos módulos.
	abstract class awk_module_feature {
		// Armazena o módulo responsável.
		// @type awk_module;
		private $module;

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
	}
