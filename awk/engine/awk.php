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

			// Classes helper.
			"awk_helper_feature",
			"awk_helper",

			// Classes type.
			"awk_type_feature",
			"awk_type",

			// Classes database.
			"awk_database_feature",
			"awk_database",

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

			// Por definição é usado o "index" como roteador padrão, porém,\
			// quando vem de um arquivo público é necessário utilizar o roteador "index.file".
			$router_id = isset($_SERVER["REDIRECT_PUBLICS"])
				? "index.file"
				: "index";

			// Se o roteador existir, ele será utilizado.
			$boot_module_id = self::$module->settings()->route_default;
			$boot_module_instance = awk_module::get($boot_module_id);
			if($boot_module_instance->routers->exists($router_id)) {
				$router_driver = new awk_router_driver(awk_router::get_url());
				$router_driver->redirect_module($boot_module_id, $router_id);
				return;
			}

			// Caso contrário, será forçado um erro de página (404).
			awk_error::force_404();
		}
	}
