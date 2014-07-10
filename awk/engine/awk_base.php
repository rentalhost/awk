<?php

	// Responsável por abstrair o modelo base das classes utilizadas pelas features.
	abstract class awk_base {
		// Armazena uma referência direta ao módulo.
		// @type awk_module;
		protected $module;

		// Armazena a classe responsável pela inicialização da base.
		// @type instance;
		protected $parent;

		// Armazena o ID da base.
		// @type string;
		protected $id;

		// Armazena o path da base.
		// @type string;
		protected $path;

		/** CONSTRUCT */
		// Construtor.
		// @param awk_module $module: instância do módulo.
		// @param instance $parent: instância do responsável pela construção da classe.
		public function __construct($module, $parent) {
			$this->module = $module;
			$this->parent = $parent;
		}

		/** GETTERS */
		// Obtém o módulo da base.
		// @return awk_module;
		public function get_module() {
			return $this->module;
		}

		// Obtém o parent da base.
		// @return instance;
		public function get_parent() {
			return $this->parent;
		}

		// Obtém o ID da base.
		public function get_id() {
			return $this->id;
		}

		// Obtém o caminho normalizado da base.
		// @param string $normalized [true]: se deverá normalizar automaticamente;
		// @return string;
		public function get_path($normalized = null) {
			if($normalized === false) {
				return $this->path;
			}

			return awk_path::normalize($this->path);
		}
	}
