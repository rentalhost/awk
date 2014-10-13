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
            // Classes module.
            "Awk_Module_Base"               => "classes",
            "Awk_Module_Feature"            => "classes",
            "Awk_Module"                    => "classes",

            // Classes router.
            "Awk_Router_Feature"            => "classes",
            "Awk_Router_Driver_Stack"       => "classes",
            "Awk_Router_Driver"             => "classes",
            "Awk_Router_Route_Part"         => "classes",
            "Awk_Router_Route"              => "classes",
            "Awk_Router"                    => "classes",

            // Classes settings.
            "Awk_Settings_Feature"          => "classes",
            "Awk_Settings"                  => "classes",

            // Classes view.
            "Awk_View_Feature"              => "classes",
            "Awk_View"                      => "classes",

            // Classes controller.
            "Awk_Controller_Feature"        => "classes",
            "Awk_Controller"                => "classes",

            // Classes library.
            "Awk_Library_Feature"           => "classes",
            "Awk_Library"                   => "classes",

            // Classes helper.
            "Awk_Helper_Feature"            => "classes",
            "Awk_Helper"                    => "classes",

            // Classes type.
            "Awk_Type_Feature"              => "classes",
            "Awk_Type"                      => "classes",

            // Classes database.
            "Awk_Database_Feature"          => "classes",
            "Awk_Database"                  => "classes",

            // Classes model.
            "Awk_Model_Feature"             => "classes",
            "Awk_Model_Query"               => "classes",
            "Awk_Model"                     => "classes",
            "Awk_Model_Row"                 => "classes",

            // Classes public.
            "Awk_Public_Feature"            => "classes",
            "Awk_Public"                    => "classes",

            // Classes private.
            "Awk_Private_Feature"           => "classes",
            "Awk_Private"                   => "classes",

            // Classes session.
            "Awk_Session_Feature"           => "classes",

            // Classes error.
            "Awk_Error"                     => "classes",

            // Classes diversas.
            "Awk_Base"                      => "classes",
            "Awk_Data"                      => "classes",
            "Awk_Path"                      => "classes",

            // Interfaces.
            "Awk_PropertyAccess_Interface"  => "interfaces",

            // Exceções.
            "Awk_Exception"                                             => "exceptions",

            // Exceções de Controller.
            "Awk_Controller_Exception"                                  => "exceptions",
            "Awk_Controller_NotExists_Exception"                        => "exceptions",
            "Awk_Controller_WasNotRegisteredClass_Exception"            => "exceptions",
            "Awk_Controller_RegisteredNotFoundClass_Exception"          => "exceptions",

            // Exceções de Error.
            "Awk_Error_Exception"                                       => "exceptions",
            "Awk_Error_NotSupportedType_Exception"                      => "exceptions",

            // Exceções de Helper.
            "Awk_Helper_Exception"                                      => "exceptions",
            "Awk_Helper_NotExists_Exception"                            => "exceptions",

            // Exceções de Library.
            "Awk_Library_Exception"                                     => "exceptions",
            "Awk_Library_NotExists_Exception"                           => "exceptions",
            "Awk_Library_WasNotRegisteredClass_Exception"               => "exceptions",
            "Awk_Library_RegisteredNotFoundClass_Exception"             => "exceptions",
            "Awk_Library_InvalidUniqueInstanceReturn_Exception"         => "exceptions",
            "Awk_Library_ConstructorRequiresParameters_Exception"       => "exceptions",

            // Exceções de Model.
            "Awk_Model_Exception"                                       => "exceptions",
            "Awk_Model_NotExists_Exception"                             => "exceptions",
            "Awk_Model_QueryAlreadyExists_Exception"                    => "exceptions",
            "Awk_Model_QueryNotExists_Exception"                        => "exceptions",
            "Awk_Model_QueryError_Exception"                            => "exceptions",
            "Awk_Model_UnsupportedQueryType_Exception"                  => "exceptions",

            // Exceções de Module.
            "Awk_Module_Exception"                                      => "exceptions",
            "Awk_Module_NotExists_Exception"                            => "exceptions",
            "Awk_Module_WithoutSettings_Exception"                      => "exceptions",
            "Awk_Module_UnsupportedFeature_Exception"                   => "exceptions",
            "Awk_Module_IdRequiresModule_Exception"                     => "exceptions",
            "Awk_Module_IdRequiresFeature_Exception"                    => "exceptions",
            "Awk_Module_IdFeatureExpected_Exception"                    => "exceptions",
            "Awk_Module_IdUnsupportedFormat_Exception"                  => "exceptions",
            "Awk_Module_GetIdIsNotSupported_Exception"                  => "exceptions",

            // Exceções de Router.
            "Awk_Router_Exception"                                      => "exceptions",
            "Awk_Router_NotExists_Exception"                            => "exceptions",

            // Exceções de Type.
            "Awk_Type_Exception"                                        => "exceptions",
            "Awk_Type_NotExists_Exception"                              => "exceptions",
            "Awk_Type_WithoutValidateCallback_Exception"                => "exceptions",
            "Awk_Type_InvalidValidateCallback_Exception"                => "exceptions",
            "Awk_Type_WithoutTransformCallback_Exception"               => "exceptions",
            "Awk_Type_InvalidTransformCallback_Exception"               => "exceptions",
        ];

        /**
         * Carrega as classes do motor via SPL.
         * @codeCoverageIgnore
         * @param  string $classname Nome da classe esperada.
         */
        static private function load_class($classname) {
            // Se localizar a classe no mapa, então será possível carregá-la.
            // Caso contrário, deixará que o PHP verifique em outro SPL, se houver.
            if(array_key_exists($classname, self::$class_mapper)) {
                require_once __DIR__ . "/../" . self::$class_mapper[$classname] . "/{$classname}.php";
            }
        }

        /**
         * Registra o motor.
         * @codeCoverageIgnore
         */
        static public function register() {
            // Registra o método de autoloader.
            spl_autoload_register("self::load_class");

            // Inicia o módulo do próprio motor.
            self::$module = Awk_Module::get("awk");
        }

        /**
         * Responsável pelo processo inicial de rota.
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
