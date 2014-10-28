<?php

    /**
     * @covers Awk_Module
     * @covers Awk_Module_Feature
     * @covers Awk_Module_Identifier
     */
    class Awk_ModuleTest extends PHPUnit_Framework_TestCase {
        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$module = Awk_Module::get("awk_suite");
        }

        /**
         * Testa os indicadores de modo de desenvolvimento.
         */
        public function testDevelopmentMode() {
            // Verifica se estamos em localhost.
            // Para isso, é necessário criar um arquivo específico.
            $_SERVER["DOCUMENT_ROOT"] = getcwd();
            file_put_contents(getcwd() . "/awk.localhost", "Hello World!");

            $this->assertTrue(self::$module->is_localhost());

            unlink(getcwd() . "/awk.localhost");

            // Verifica se estamos em modo desenvolvimento.
            // É verificado a configuração do próprio framework.
            $this->assertTrue(self::$module->is_development());
        }

        /**
         * Verifica se um módulo responde com todas as features disponíveis.
         */
        public function testModuleFeatures() {
            $this->assertInstanceOf("Awk_Router_Feature",     self::$module->routers);
            $this->assertInstanceOf("Awk_Controller_Feature", self::$module->controllers);
            $this->assertInstanceOf("Awk_Library_Feature",    self::$module->libraries);
            $this->assertInstanceOf("Awk_Helper_Feature",     self::$module->helpers);
            $this->assertInstanceOf("Awk_View_Feature",       self::$module->views);
            $this->assertInstanceOf("Awk_Database_Feature",   self::$module->databases);
            $this->assertInstanceOf("Awk_Settings_Feature",   self::$module->settings);
            $this->assertInstanceOf("Awk_Module_Feature",     self::$module->modules);
            $this->assertInstanceOf("Awk_Type_Feature",       self::$module->types);
            $this->assertInstanceOf("Awk_Public_Feature",     self::$module->publics);
            $this->assertInstanceOf("Awk_Private_Feature",    self::$module->privates);
            $this->assertInstanceOf("Awk_Session_Feature",    self::$module->sessions);
            $this->assertInstanceOf("Awk_Model_Feature",      self::$module->models);
        }

        /**
         * Testa os verificadores de existência de objetos nos módulos suportados.
         * @covers Awk_Router_Feature::exists
         * @covers Awk_Controller_Feature::exists
         * @covers Awk_Library_Feature::exists
         * @covers Awk_Helper_Feature::exists
         * @covers Awk_View_Feature::exists
         * @covers Awk_Type_Feature::exists
         * @covers Awk_Model_Feature::exists
         * @covers Awk_Public_Feature::exists
         * @covers Awk_Private_Feature::exists
         * @covers Awk_Type_Feature::load_index
         */
        public function testModuleExists() {
            $this->assertTrue(self::$module->routers->exists("test1_basic"));
            $this->assertTrue(self::$module->controllers->exists("test1_valid"));
            $this->assertTrue(self::$module->libraries->exists("test1_valid_autoinit"));
            $this->assertTrue(self::$module->helpers->exists("test1"));
            $this->assertTrue(self::$module->views->exists("test1"));
            $this->assertTrue(self::$module->types->exists("test1_complete"));
            $this->assertTrue(self::$module->models->exists("test1_base"));

            // Situações especiais.
            $this->assertTrue(self::$module->publics->exists("test1_hello.php"));
            $this->assertTrue(self::$module->privates->exists("test1_file.php"));
        }

        /**
         * Testa o acesso ao exists onde não é suportado.
         * @expectedException           Awk_Module_ExistsNotSupported_Exception
         * @expectedExceptionMessage    O recurso Database não possui suporte a verificação de existência de objetos.
         * @covers Awk_Database_Feature::exists
         */
        public function testAwk_Module_ExistsNotSupported_Exception1() {
            self::$module->databases->exists("default");
        }

        /**
         * Testa o acesso ao exists onde não é suportado.
         * @expectedException           Awk_Module_ExistsNotSupported_Exception
         * @expectedExceptionMessage    O recurso Settings não possui suporte a verificação de existência de objetos.
         * @covers Awk_Settings_Feature::exists
         */
        public function testAwk_Module_ExistsNotSupported_Exception2() {
            self::$module->settings->exists("default");
        }

        /**
         * Testa o acesso ao exists onde não é suportado.
         * @expectedException           Awk_Module_ExistsNotSupported_Exception
         * @expectedExceptionMessage    O recurso Session não possui suporte a verificação de existência de objetos.
         * @covers Awk_Session_Feature::exists
         */
        public function testAwk_Module_ExistsNotSupported_Exception3() {
            self::$module->sessions->exists("default");
        }

        /**
         * Testa o módulo.
         */
        public function testModuleLoad() {
            $current_directory = getcwd();
            chdir("{$current_directory}/..");

            $this->assertSame("awk_suite", self::$module->name);
            $this->assertSame(self::$module, self::$module->modules->module);
            $this->assertTrue(Awk_Module::exists(self::$module->name));

            chdir($current_directory);
        }

        /**
         * Testa o sistema de identificação.
         */
        public function testIdentifier() {
            // Identificação simples.
            $identify_data = self::$module->identify("library@test6_valid_unique::test")->get_callable();

            $this->assertCount(2, $identify_data);
            $this->assertInstanceOf("Awk_Library", $identify_data[0]);
            $this->assertSame("test6_valid_unique", $identify_data[0]->name);
            $this->assertSame("test", $identify_data[1]);
            $this->assertFalse(is_callable($identify_data));

            // Verifica todas as propriedades do identificador.
            $identify_data = self::$module->identify("library@test6_valid_unique::test");
            $this->assertSame("library", $identify_data->feature);
            $this->assertSame(self::$module, $identify_data->module);
            $this->assertSame("test6_valid_unique", $identify_data->name);
            $this->assertSame("test", $identify_data->method);

            // Testes de identificação de todas as features.
            // Teste em Library.
            $identified_instance = self::$module->identify("library@awk_suite->test6_valid_unique")->get_instance();

            $this->assertInstanceOf("Awk_Library", $identified_instance);
            $this->assertSame("library@awk_suite->test6_valid_unique", $identified_instance->get_id());

            // Omissão do módulo.
            $identified_instance = self::$module->identify("library@test6_valid_unique")->get_instance();

            $this->assertInstanceOf("Awk_Library", $identified_instance);
            $this->assertSame("library@awk_suite->test6_valid_unique", $identified_instance->get_id());

            // Teste em Controller.
            $identified_instance = self::$module->identify("controller@test1_valid")->get_instance();

            $this->assertInstanceOf("AwkSuite_Valid_Controller", $identified_instance);
            $this->assertSame("controller@awk_suite->test1_valid", $identified_instance->get_id());

            // Teste em Helper.
            $identified_instance = self::$module->identify("helper@test1")->get_instance();

            $this->assertInstanceOf("Awk_Helper", $identified_instance);
            $this->assertSame("helper@awk_suite->test1", $identified_instance->get_id());

            // Teste em Model.
            $identified_instance = self::$module->identify("model@test1_base")->get_instance();

            $this->assertInstanceOf("Awk_Model", $identified_instance);
            $this->assertSame("model@awk_suite->test1_base", $identified_instance->get_id());

            // Teste em Private.
            $identified_instance = self::$module->identify("private@test1_file")->get_instance();

            $this->assertInstanceOf("Awk_Private", $identified_instance);
            $this->assertSame("private@awk_suite->test1_file", $identified_instance->get_id());

            $identified_instance = self::$module->identify("private@unexistent")->get_instance();

            $this->assertInstanceOf("Awk_Private", $identified_instance);
            $this->assertSame("private@awk_suite->unexistent", $identified_instance->get_id());

            // Teste em Public.
            $identified_instance = self::$module->identify("public@test1_hello")->get_instance();

            $this->assertInstanceOf("Awk_Public", $identified_instance);
            $this->assertSame("public@awk_suite->test1_hello", $identified_instance->get_id());

            $identified_instance = self::$module->identify("public@unexistent")->get_instance();

            $this->assertInstanceOf("Awk_Public", $identified_instance);
            $this->assertSame("public@awk_suite->unexistent", $identified_instance->get_id());

            // Teste em Router.
            $identified_instance = self::$module->identify("router@test1_basic")->get_instance();

            $this->assertInstanceOf("Awk_Router", $identified_instance);
            $this->assertSame("router@awk_suite->test1_basic", $identified_instance->get_id());

            // Teste em Type.
            $identified_instance = self::$module->identify("type@test1_complete")->get_instance();

            $this->assertInstanceOf("Awk_Type", $identified_instance);
            $this->assertSame("type@awk_suite->test1_complete", $identified_instance->get_id());
        }

        /**
         * Testa a identificação de uma View.
         * Não deve possuir saída.
         */
        public function testIdentifierView() {
            $identified_instance = self::$module->identify("view@test1")->get_instance();

            $this->assertInstanceOf("Awk_View", $identified_instance);
            $this->assertSame("view@awk_suite->test1", $identified_instance->get_id());

            $this->expectOutputString("");
        }

        /**
         * Testa um módulo inexistente.
         * @expectedException           Awk_Module_NotExists_Exception
         * @expectedExceptionMessage    O módulo "unexistent" não foi encontrado.
         */
        public function testUnexistentModuleException() {
            Awk_Module::get("unexistent");
        }

        /**
         * Cria um módulo inválido, pois não possui um arquivo de configuração.
         * @expectedException           Awk_Module_WithoutSettings_Exception
         * @expectedExceptionMessage    O módulo "unexistent" não possui um arquivo de configurações.
         */
        public function testNotSettingsModuleException() {
            $tmp_module = getcwd() . "/../unexistent";
            mkdir($tmp_module, 0777);

            Awk_Module::get("unexistent");
        }

        /**
         * Limpeza do teste anterior.
         */
        public function testNotSettingsModuleExceptionCleanup() {
            $this->assertEmpty(null);
            rmdir(getcwd() . "/../unexistent");
        }

        /**
         * Testa uma feature inexistente para um módulo.
         * @expectedException           Awk_Module_UnsupportedFeature_Exception
         * @expectedExceptionMessage    O recurso "unexistent_features" não é suportado.
         */
        public function testUnexistentModuleFeatureException() {
            self::$module->unexistent_feature();
        }

        /**
         * Tenta uma identificação que não define um módulo.
         * @expectedException           Awk_Module_IdRequiresModule_Exception
         * @expectedExceptionMessage    Falha ao identificar "test". A definição de um módulo é obrigatória.
         */
        public function testFeatureIdentifyNoModuleException() {
            self::$module->identify("test", null, null, true);
        }

        /**
         * Tenta uma identificação que não define uma feature.
         * @expectedException           Awk_Module_IdRequiresFeature_Exception
         * @expectedExceptionMessage    Falha ao identificar "test". A definição de um recurso é obrigatório.
         */
        public function testFeatureIdentifyNoFeatureException() {
            self::$module->identify("test");
        }

        /**
         * Tenta uma identificação que possui uma feature obrigatória.
         * @expectedException           Awk_Module_IdFeatureExpected_Exception
         * @expectedExceptionMessage    Falha ao identificar "router@test". O recurso "type" era esperado.
         */
        public function testFeatureIdentifyFeatureBlockedException() {
            self::$module->identify("router@test", "type", true);
        }

        /**
         * Tenta uma identificação inválida.
         * @expectedException           Awk_Module_IdUnsupportedFormat_Exception
         * @expectedExceptionMessage    Falha ao identificar "%invalid%". O formato utilizado não é suportado.
         */
        public function testFeatureIdentifyInvalidException() {
            self::$module->identify("%invalid%", "type", true);
        }

        /**
         * Recarrega um módulo.
         */
        public function testModuleReload() {
            // Limpa as informações do módulo do cache.
            $property_reflection = new ReflectionProperty("Awk_Module", "modules");
            $property_reflection->setAccessible(true);

            // Copia a propriedade para fins de teste.
            $property_value = $property_reflection->getValue();
            $property_value_copy = $property_value["awk_suite"];
            $property_value["awk_suite"] = null;
            $property_reflection->setValue($property_value);

            // E então, a recarrega.
            self::$module = Awk_Module::get("awk_suite");

            $this->assertInstanceOf("Awk_Module", self::$module);

            // Depois recupera o módulo antigo.
            $property_value["awk_suite"] = $property_value_copy;
            $property_reflection->setValue($property_value);
            $property_reflection->setAccessible(false);
        }
    }
