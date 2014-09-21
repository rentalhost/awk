<?php

	/**
	 * @covers Awk_Helper
	 * @covers Awk_Helper_Feature
	 */
	class Awk_HelperTest extends PHPUnit_Framework_TestCase {
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
		 * Executa testes na classe.
		 * @return void
		 */
		public function testHelper() {
			$helper_instance = self::$module->helper("test1");

			$this->assertSame("Hello World!", $helper_instance->call("hello", "World"));
		}

		/**
		 * Teste de cache da classe.
		 * @return void
		 */
		public function testHelperReaload() {
			$this->testHelper();
		}

		/**
		 * Lança uma exceção quando o helper não existir.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage O módulo "awk_suite" não possui o helper "unexistent".
		 * @return void
		 */
		public function testUnexistentException() {
			self::$module->helper("unexistent");
		}
	}
