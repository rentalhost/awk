<?php

	/**
	 * Responsável pela definição das features dos módulos.
	 */
	class Awk_Module_Feature {
		/**
		 * Armazena o módulo responsável.
		 * @var Awk_Module
		 */
		protected $module;

		/**
		 * Constrói uma feature.
		 * @param Awk_Module $module Instâncoa do módulo.
		 */
		public function __construct($module) {
			$this->module = $module;
		}

		/**
		 * Retorna o módulo da feature.
		 * @return Awk_Module
		 */
		public function get_module() {
			return $this->module;
		}

		/**
		 * Este método precisa ser sobrescrito pela finalidade da feature.
		 */
		public function feature_call() {
		} // @codeCoverageIgnore
	}
