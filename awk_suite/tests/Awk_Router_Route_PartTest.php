<?php

	/**
	 * @covers Awk_Router_Route
	 * @covers Awk_Router_Route_Part
	 */
	class Awk_Router_Route_PartTest extends PHPUnit_Framework_TestCase {
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
		 * Testa uma rota simples, de dois elementos estáticos.
		 * @return void
		 */
		public function testPartSimple() {
			$test_driver = new Awk_Router_Driver("args/simple", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("simple");
		}

		/**
		 * Testa argumentos opcionais.
		 * @return void
		 */
		public function testPartOptionalArgs1() {
			$test_driver = new Awk_Router_Driver("args/123", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("123,,");
		}

		/**
		 * Testa argumentos opcionais.
		 * @return void
		 */
		public function testPartOptionalArgs2() {
			$test_driver = new Awk_Router_Driver("args/123/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("123,,abc");
		}

		/**
		 * Testa argumentos opcionais.
		 * @return void
		 */
		public function testPartOptionalArgs3() {
			$test_driver = new Awk_Router_Driver("args/123/1.5/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("123,1.5,abc");
		}

		/**
		 * Testa argumentos opcionais.
		 * @return void
		 */
		public function testPartOptionalArgs4() {
			$test_driver = new Awk_Router_Driver("args/1.5/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString(",1.5,abc");
		}

		/**
		 * Testa argumentos opcionais.
		 * @return void
		 */
		public function testPartOptionalArgs5() {
			$test_driver = new Awk_Router_Driver("args/1.5", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString(",1.5,");
		}

		/**
		 * Testa argumentos com repetições de um-ou-mais (+).
		 * @return void
		 */
		public function testPartOneMoreRepetitions() {
			$test_driver = new Awk_Router_Driver("repeat/simple-one/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições de zero-ou-mais (*).
		 * @return void
		 */
		public function testPartZeroMoreRepetitions1() {
			$test_driver = new Awk_Router_Driver("repeat/simple-zero/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("");
		}

		/**
		 * Testa argumentos com repetições de zero-ou-mais (*).
		 * @return void
		 */
		public function testPartZeroMoreRepetitions2() {
			$test_driver = new Awk_Router_Driver("repeat/simple-zero/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições exatas (a{x}).
		 * @return void
		 */
		public function testPartExactRepetitions() {
			$test_driver = new Awk_Router_Driver("repeat/exactly/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições mínimas (a{x,}).
		 * @return void
		 */
		public function testPartMinRepetitions1() {
			$test_driver = new Awk_Router_Driver("repeat/min/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições mínimas (a{x,}).
		 * @return void
		 */
		public function testPartMinRepetitions2() {
			$test_driver = new Awk_Router_Driver("repeat/min/1/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("fail");
		}

		/**
		 * Testa argumentos com repetições mínimas e opcional (a{x,}?).
		 * @return void
		 */
		public function testPartMinOptionalRepetitions() {
			$test_driver = new Awk_Router_Driver("repeat/min-optional/1/2/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString(",1");
		}

		/**
		 * Testa argumentos com repetições máxima (a{,x}).
		 * @return void
		 */
		public function testPartMaxRepetitions1() {
			$test_driver = new Awk_Router_Driver("repeat/max/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições máxima (a{,x}).
		 * @return void
		 */
		public function testPartMaxRepetitions2() {
			$test_driver = new Awk_Router_Driver("repeat/max/1/2/3/4/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições em alcance (a{x,y}).
		 * @return void
		 */
		public function testPartRangedRepetitions1() {
			$test_driver = new Awk_Router_Driver("repeat/ranged/1/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("fail");
		}

		/**
		 * Testa argumentos com repetições em alcance (a{x,y}).
		 * @return void
		 */
		public function testPartRangedRepetitions2() {
			$test_driver = new Awk_Router_Driver("repeat/ranged/1/2/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2");
		}

		/**
		 * Testa argumentos com repetições em alcance (a{x,y}).
		 * @return void
		 */
		public function testPartRangedRepetitions3() {
			$test_driver = new Awk_Router_Driver("repeat/ranged/1/2/3/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa argumentos com repetições em alcance (a{x,y}).
		 * @return void
		 */
		public function testPartRangedRepetitions4() {
			$test_driver = new Awk_Router_Driver("repeat/ranged/1/2/3/4/abc", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("1,2,3");
		}

		/**
		 * Testa um argumento com captura por nome.
		 * @return void
		 */
		public function testPartAttributeNameCapture() {
			$test_driver = new Awk_Router_Driver("capture/hello", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("hello");
		}

		/**
		 * Testa uma falha.
		 * @return void
		 */
		public function testPartFail() {
			$test_driver = new Awk_Router_Driver("fail", self::$module);
			$test_driver->redirect("test4_parts");

			$this->expectOutputString("fail");
		}
	}
