<?php

	/**
	 * @covers Awk_Library
	 * @covers Awk_Library_Feature
	 */
	class Awk_LibraryTest extends PHPUnit_Framework_TestCase {
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
		 * Carrega uma library válida.
		 * @covers Awk_Base::set_base
		 * @return void
		 */
		public function testLibraryLoad() {
			$this->assertEmpty(null);

			return self::$module->library("test1_valid_autoinit");
		}

		/**
		 * Testa o cache.
		 * @return void
		 */
		public function testLibraryReload() {
			$this->testLibraryLoad();
		}

		/**
		 * Testa as instancializações da library.
		 * @depends testLibraryLoad
		 * @return void
		 */
		public function testLibraryInstancing($library_instance) {
			$library_classname = $library_instance->get_registered_classname();

			// Cria e testa uma instância única.
			$class_instance = $library_instance->unique();
			$this->assertInstanceOf($library_classname, $class_instance);
			$this->assertSame(1, $class_instance->init_number);

			// Cria uma nova instância e verifica.
			$class_instance = $library_instance->create();
			$this->assertInstanceOf($library_classname, $class_instance);
			$this->assertSame(2, $class_instance->init_number);

			// Testa novamente a instância única.
			$class_instance = $library_instance->unique();
			$this->assertSame(1, $class_instance->init_number);

			// E mais uma vez a nova instância.
			$class_instance = $library_instance->create();
			$this->assertSame(3, $class_instance->init_number);
		}

		/**
		 * Testa uma library com o método library_unique válido.
		 * @return void
		 */
		public function testValidUniqueLibrary() {
			$library_instance = self::$module->library("test6_valid_unique");

			$this->assertInstanceOf($library_instance->get_registered_classname(), $library_instance->unique());
		}

		/**
		 * Verifica a exceção de library inexistente.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage O módulo "awk_suite" não possui a library "unexistent".
		 * @return void
		 */
		public function testUnexistentLibraryException() {
			self::$module->library("unexistent");
		}

		/**
		 * Verifica a exceção de classe não registrada.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage A library "test2_unregistered_class" do módulo "awk_suite" não efetuou o registro de classe.
		 * @return void
		 */
		public function testUnregisteredClassException() {
			self::$module->library("test2_unregistered_class");
		}

		/**
		 * Verifica a exceção de classe registrada inexistente.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage A library "test3_inexistent_class" do módulo "awk_suite" registrou uma classe inexistente ("Unexistent_Class").
		 * @return void
		 */
		public function testInexistentRegisteredClass() {
			self::$module->library("test3_inexistent_class");
		}

		/**
		 * Verifica a exceção de library quando library_unique não retorna um objeto da mesma classe.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage O método "library_unique" da library "AwkSuite_Invalid2_Test" do módulo "awk_suite"
		 *                           não retornou uma instância da classe "AwkSuite_Invalid2_Test", ao invés disso, retornou "stdClass".
		 * @return void
		 */
		public function testInvalidUniqueInstanceException() {
			self::$module->library("test4_invalid_unique")->unique();
		}

		/**
		 * Verifica a exceção de library quando chamado via unique, mas seu construtor requer parâmetros.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage A instância única da library "AwkSuite_Invalid3_Test" do módulo "awk_suite" não pôde ser criada
		 *                           pois seu construtor requer parâmetros. Considere definir o método "library_unique".
		 * @return void
		 */
		public function testWithoutUniqueException() {
			self::$module->library("test5_without_unique")->unique();
		}
	}
