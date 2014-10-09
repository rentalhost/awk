<?php

    /**
     * Responsável pela inicialização do motor e alguns recursos essenciais.
     */
    class Awk {
        /**
         * Instância do módulo do próprio motor.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Mapa de classes que podem ser carregadas através do motor.
         * @var string[]
         */
        static private $class_mapper = [
            // Classes view.
            "Awk_View_Feature",
            "Awk_View",

            // Classes controller.
            "Awk_Controller_Feature",
            "Awk_Controller",

            // Classes library.
            "Awk_Library_Feature",
            "Awk_Library",

            // Classes helper.
            "Awk_Helper_Feature",
            "Awk_Helper",

            // Classes type.
            "Awk_Type_Feature",
            "Awk_Type",

            // Classes database.
            "Awk_Database_Feature",
            "Awk_Database",

            // Classes model.
            "Awk_Model_Feature",
            "Awk_Model_Query",
            "Awk_Model",
            "Awk_Model_Row",

            // Classes public.
            "Awk_Public_Feature",
            "Awk_Public",

            // Classes private.
            "Awk_Private_Feature",
            "Awk_Private",

            // Classes session.
            "Awk_Session_Feature",

            // Classes error.
            "Awk_Error",
            "Awk_Exception",

            // Classes diversas.
            "Awk_Base",
            "Awk_Data",
            "Awk_Path",

            // Classes auto-inicializáveis.
            // Usado no PHPUnit.
            "Awk_Module_Base",
            "Awk_Module_Feature",
            "Awk_Module",
            "Awk_Router_Feature",
            "Awk_Router_Driver_Stack",
            "Awk_Router_Driver",
            "Awk_Router_Route_Part",
            "Awk_Router_Route",
            "Awk_Router",
            "Awk_Settings_Feature",
            "Awk_Settings",
        ];

        /**
         * Carrega as classes do motor via SPL.
         * @codeCoverageIgnore
         * @param  string $classname Nome da classe esperada.
         * @return void
         */
        static private function load_class($classname) {
            // Se localizar a classe no mapa, então será possível carregá-la.
            // Caso contrário, deixará que o PHP verifique em outro SPL, se houver.
            if(in_array($classname, self::$class_mapper)) {
                require_once __DIR__ . "/{$classname}.php";
            }
        }

        /**
         * Registra o motor.
         * @codeCoverageIgnore
         * @return void
         */
        static public function register() {
            // Registra o método de autoloader.
            spl_autoload_register("self::load_class");

            // Inicia o módulo do próprio motor.
            self::$module = Awk_Module::get("awk");
        }

        /**
         * Responsável pelo processo inicial de rota.
         * @return void
         */
        static public function init() {
            // Carrega as configurações do motor.
            $engine_settings = self::$module->settings();

            // Por definição é usado o "index" como roteador padrão, porém,\
            // quando vem de um arquivo público é necessário utilizar o roteador "index.file".
            $router_id = isset($_SERVER["REDIRECT_PUBLICS"])
                ? $engine_settings->router_file_default
                : $engine_settings->router_default;

            // Identifica a rota.
            // Se o roteador existir, ele será utilizado.
            $router_identify = self::$module->identify($router_id, "router", null, true, true);
            if($router_identify["module"]->routers->exists($router_identify["name"])) {
                $router_driver = new Awk_Router_Driver(Awk_Router::get_url(), $router_identify["module"], true);
                $router_driver->redirect($router_id);
                return;
            }

            // Caso contrário, será forçado um erro de página (404).
            Awk_Error::force_404();
        } // @codeCoverageIgnore
    }
