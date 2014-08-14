<?php

	// Responsável por abstrair o modelo base das classes utilizadas pelas features.
	abstract class AwkModuleBase {
		// Armazena o tipo de recurso.
		// @type string;
		static protected $feature_type;

		// Armazena uma referência direta ao módulo.
		// @type AwkModule;
		protected $module;

		// Armazena a classe responsável pela inicialização da base.
		// @type instance;
		protected $parent;

		// Armazena o nome da base.
		// @type string;
		protected $name;

		// Armazena o path da base.
		// @type string;
		protected $path;

		/** CONSTRUCT */
		// Construtor.
		// @param AwkModule $module: instância do módulo.
		// @param instance $parent: instância do responsável pela construção da classe.
		public function __construct($module, $parent) {
			$this->module = $module;
			$this->parent = $parent;
		}

		/** GETTERS */
		// Obtém o módulo da base.
		// @return AwkModule;
		public function get_module() {
			return $this->module;
		}

		// Obtém o parent da base.
		// @return instance;
		public function get_parent() {
			return $this->parent;
		}

		// Obtém o nome da base.
		public function get_name() {
			return $this->name;
		}

		// Obtém o ID do recurso.
		public function get_id() {
			return static::$feature_type . "@"
				. $this->module->get_name() . "->"
				. $this->name;
		}

		// Obtém o caminho normalizado da base.
		// @param string $normalized [true]: se deverá normalizar automaticamente;
		// @return string;
		public function get_path($normalized = null) {
			if($normalized === false) {
				return $this->path;
			}

			return AwkPath::normalize($this->path);
		}
	}
