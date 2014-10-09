<?php

	/**
	 * Responsável por abstrair o modelo base das classes utilizadas pelas features.
	 */
	abstract class Awk_Module_Base {
		/**
		 * Armazena o tipo de recurso.
		 * @var string
		 */
		static protected $feature_type;

		/**
		 * Armazena uma referência direta ao módulo.
		 * @var Awk_Module
		 */
		protected $module;

		/**
		 * Armazena a classe responsável pela inicialização da base.
		 * @var object
		 */
		protected $parent;

		/**
		 * Armazena o nome da base.
		 * @var string
		 */
		protected $name;

		/**
		 * Armazena o path da base.
		 * @var string
		 */
		protected $path;

		/**
		 * Construtor.
		 * @param Awk_Module $module Instância do módulo.
		 * @param object     $parent Instância do responsável pela construção da classe.
		 */
		public function __construct($module, $parent) {
			$this->module = $module;
			$this->parent = $parent;
		}

		/**
		 * Obtém o módulo da base.
		 * @return Awk_Module
		 */
		public function get_module() {
			return $this->module;
		}

		/**
		 * Obtém o parent da base.
		 * @return object
		 */
		public function get_parent() {
			return $this->parent;
		}

		/**
		 * Obtém o nome da base.
		 * @return string
		 */
		public function get_name() {
			return $this->name;
		}

		/**
		 * Obtém o ID do recurso.
		 * @return string
		 */
		public function get_id() {
			return static::$feature_type . "@"
				. $this->module->get_name() . "->"
				. $this->name;
		}

		/**
		 * Obtém o caminho normalizado da base.
		 * @param  string $normalized Se deverá normalizar o caminho.
		 * @return string
		 */
		public function get_path($normalized = null) {
			if($normalized === false) {
				return $this->path;
			}

			return Awk_Path::normalize($this->path);
		}
	}
