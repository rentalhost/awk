<?php

	/**
	 * @covers Awk_Module
	 * @covers Awk_Module_Feature
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
		 * @return void
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
		 * @return void
		 */
		public function testModuleFeatures() {
			// Testa as features como propriedades.
			$this->assertInstanceOf("Awk_Router_Feature", self::$module->routers);
			$this->assertInstanceOf("Awk_Controller_Feature", self::$module->controllers);
			$this->assertInstanceOf("Awk_Library_Feature", self::$module->libraries);
			$this->assertInstanceOf("Awk_Helper_Feature", self::$module->helpers);
			$this->assertInstanceOf("Awk_View_Feature", self::$module->views);
			$this->assertInstanceOf("Awk_Database_Feature", self::$module->databases);
			$this->assertInstanceOf("Awk_Settings_Feature", self::$module->settings);
			$this->assertInstanceOf("Awk_Module_Feature", self::$module->modules);
			$this->assertInstanceOf("Awk_Type_Feature", self::$module->types);
			$this->assertInstanceOf("Awk_Public_Feature", self::$module->publics);
			$this->assertInstanceOf("Awk_Private_Feature", self::$module->privates);
			$this->assertInstanceOf("Awk_Session_Feature", self::$module->sessions);
			$this->assertInstanceOf("Awk_Model_Feature", self::$module->models);
		}

		/**
		 * Testa o módulo.
		 * @return void
		 */
		public function testModuleLoad() {
			$current_directory = getcwd();
			chdir("{$current_directory}/..");

			$this->assertSame("awk_suite", self::$module->get_name());
			$this->assertSame(self::$module, self::$module->modules->get_module());
			$this->assertTrue(Awk_Module::exists(self::$module->get_name()));

			chdir($current_directory);
		}

		/**
		 * Testa o sistema de identificação.
		 * @return void
		 */
		public function testIdentifier() {
			$this->assertInstanceOf("Awk_Library", self::$module->identify("library@awk_suite->test6_valid_unique"));
			$this->assertInstanceOf("Awk_Library", self::$module->identify("library@test6_valid_unique"));

			// Identificação simples.
			$identify_data = self::$module->identify("library@test6_valid_unique::test");
			$this->assertCount(2, $identify_data);
			$this->assertInstanceOf("Awk_Library", $identify_data[0]);
			$this->assertSame("test6_valid_unique", $identify_data[0]->get_name());
			$this->assertSame("test", $identify_data[1]);

			// Identificação avançada.
			$identify_data = self::$module->identify("library@test6_valid_unique::test", null, null, null, true);
			$this->assertCount(4, $identify_data);
			$this->assertSame("library", $identify_data["feature"]);
			$this->assertSame(self::$module, $identify_data["module"]);
			$this->assertSame("test6_valid_unique", $identify_data["name"]);
			$this->assertSame("test", $identify_data["method"]);
		}

		/**
		 * Testa um módulo inexistente.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage O módulo "unexistent" não existe.
		 * @return void
		 */
		public function testUnexistentModuleException() {
			Awk_Module::get("unexistent");
		}

		/**
		 * Cria um módulo inválido, pois não possui um arquivo de configuração.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage O módulo "unexistent" não definiu o arquivo de configuração.
		 * @return void
		 */
		public function testNotSettingsModuleException() {
			$tmp_module = getcwd() . "/../unexistent";
			mkdir($tmp_module, 0777);

			Awk_Module::get("unexistent");
		}

		/**
		 * Limpeza do teste anterior.
		 * @return void
		 */
		public function testNotSettingsModuleExceptionCleanup() {
			$this->assertEmpty(null);
			rmdir(getcwd() . "/../unexistent");
		}

		/**
		 * Testa uma feature inexistente para um módulo.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage O recurso "unexistent_features" não está disponível.
		 * @return void
		 */
		public function testUnexistentModuleFeatureException() {
			self::$module->unexistent_feature();
		}

		/**
		 * Tenta uma identificação que não define um módulo.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage Não foi possível identificar "test". A definição do módulo é obrigatória.
		 * @return void
		 */
		public function testFeatureIdentifyNoModuleException() {
			self::$module->identify("test", null, null, true);
		}

		/**
		 * Tenta uma identificação que não define uma feature.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage Não foi possível identificar "test". A definição do recurso é obrigatória.
		 * @return void
		 */
		public function testFeatureIdentifyNoFeatureException() {
			self::$module->identify("test");
		}

		/**
		 * Tenta uma identificação que possui uma feature obrigatória.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage Não foi possível identificar "router@test". O recurso deve ser "type".
		 * @return void
		 */
		public function testFeatureIdentifyFeatureBlockedException() {
			self::$module->identify("router@test", "type", true);
		}

		/**
		 * Tenta uma identificação inválida.
		 * @expectedException Awk_Error_Exception
		 * @expectedExceptionMessage Não foi possível identificar "%invalid%".
		 * @return void
		 */
		public function testFeatureIdentifyInvalidException() {
			self::$module->identify("%invalid%", "type", true);
		}

		/**
		 * Recarrega um módulo.
		 * @return void
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
