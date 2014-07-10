<?php

	// Responsável pela inicialização do motor e alguns recursos essenciais.
	class awk {
		/** MÓDULO */
		// Instância do módulo do próprio motor.
		static private $module;

		/** AUTOLOADER */
		// Mapa de classes que podem ser carregadas através do motor.
		// @type array<string>;
		static private $class_mapper = [
			// Classes view.
			"awk_view_feature",
			"awk_view",

			// Classes controller.
			"awk_controller_feature",
			"awk_controller",

			// Classes library.
			"awk_library_feature",
			"awk_library",

			// Classes diversas.
			"awk_path",
			"awk_error",
		];

		// Carrega as classes do motor via SPL.
		static private function load_class($classname) {
			// Se localizar a classe no mapa, então será possível carregá-la.
			// Caso contrário, deixará que o PHP verifique em outro SPL, se houver.
			if(in_array($classname, self::$class_mapper)) {
				require_once __DIR__ . "/{$classname}.php";
				return;
			}
		}

		/** INIT */
		// Inicializa o motor.
		static public function init() {
			// Registra o método de autoloader.
			spl_autoload_register("self::load_class");

			// Inicia o módulo do próprio motor.
			self::$module = awk_module::get(self::class);

			// Transfere a URL para o roteador principal.
			// @todo carregar o roteador principal através das configurações do awk.
			$boot_module = awk_module::get("debug");
			$boot_module->router("index")->solve(awk_router::get_url());
		}
	}
