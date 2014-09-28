<?php

	/**
	 * @covers Awk_Controller
	 * @covers Awk_Controller_Feature
	 * @covers Awk_Module_Base::__construct
	 */
	class Awk_ControllerText extends PHPUnit_Framework_TestCase {
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
		 * Testa a instância.
		 * @return void
		 */
		public function testControllerLoad() {
			$controller_instance = self::$module->controller("test1_valid");

			$this->assertInstanceOf("Awk_Base", $controller_instance);
		}

		/**
		 * Testa novamente, para cache.
		 * @return void
		 */
		public function testControllerReload() {
			$this->testControllerLoad();
		}

		/**
		 * Tenta carregar um controller inexistente.
		 * @expectedException        Awk_Exception
		 * @expectedExceptionMessage O módulo "awk_suite" não possui o controller "unexistent".
		 * @return [type] [description]
		 */
		public function testUnexistentException() {
			self::$module->controller("unexistent");
		}

		/**
		 * Tenta carregar um controller que registrou uma classe inexistente.
		 * @expectedException        Awk_Exception
		 * @expectedExceptionMessage O controller "test2_unexistent_class" do módulo "awk_suite" registrou uma classe inexistente ("Unexistent_Class").
		 * @return [type] [description]
		 */
		public function testUnexistentClassException() {
			self::$module->controller("test2_unexistent_class");
		}

		/**
		 * Tenta carregar um controller que não registrou a classe.
		 * @expectedException        Awk_Exception
		 * @expectedExceptionMessage O controller "test3_unregistered_class" do módulo "awk_suite" não efetuou o registro de classe.
		 * @return [type] [description]
		 */
		public function testUnregisteredClassException() {
			self::$module->controller("test3_unregistered_class");
		}
	}
