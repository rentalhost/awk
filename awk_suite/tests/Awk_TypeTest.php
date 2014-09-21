<?php

	/**
	 * @covers Awk_Type
	 * @covers Awk_Type_Feature
	 */
	class Awk_TypeTest extends PHPUnit_Framework_TestCase {
		/**
		 * Módulo awk.
		 * @var Awk_Module
		 */
		static private $awk;

		/**
		 * Módulo atual.
		 * @var Awk_Module
		 */
		static private $module;

		/**
		 * Configurações antes da classe.
		 */
		static public function setUpBeforeClass() {
			self::$awk    = Awk_Module::get("awk");
			self::$module = Awk_Module::get("awk_suite");
		}

		/**
		 * Verifica se um tipo retorna a resposta esperada.
		 * @param  Awk_Type $type             Instância do controlador do tipo.
		 * @param  mixed    $value            Valor a ser testado pelos métodos da instância.
		 * @param  boolean  $valueValidate    Se o valor será validado corretamente.
		 * @param  mixed    $valueTransformed Valor esperado após a transformação.
		 * @return void
		 */
		private function processTypeResponse($type, $value, $valueValidate, $valueTransformed) {
			$this->assertEquals($valueValidate, $type->validate($value));
			$this->assertEquals($valueTransformed, $type->transform($value));
		}

		/**
		 * Executa testes no tipo padrão boolean.
		 * @return void
		 */
		public function testTypeBoolean() {
			$type_instance = self::$awk->type("boolean");

			$this->processTypeResponse($type_instance, true, true, true);
			$this->processTypeResponse($type_instance, false, false, false);
			$this->processTypeResponse($type_instance, "on", true, true);
			$this->processTypeResponse($type_instance, "yes", true, true);
			$this->processTypeResponse($type_instance, "1", true, true);
			$this->processTypeResponse($type_instance, "0", false, false);
			$this->processTypeResponse($type_instance, "", false, false);
			$this->processTypeResponse($type_instance, "-1", false, false);
			$this->processTypeResponse($type_instance, " ", false, false);
			$this->processTypeResponse($type_instance, 1, true, true);
			$this->processTypeResponse($type_instance, 1.5, false, false);
			$this->processTypeResponse($type_instance, 0, false, false);
			$this->processTypeResponse($type_instance, -1, false, false);
			$this->processTypeResponse($type_instance, null, false, false);
			$this->processTypeResponse($type_instance, [], false, false);
			$this->processTypeResponse($type_instance, [true], false, false);
		}

		/**
		 * Executa testes no tipo padrão null.
		 * @return void
		 */
		public function testTypeNull() {
			$type_instance = self::$awk->type("null");

			$this->processTypeResponse($type_instance, true, false, null);
			$this->processTypeResponse($type_instance, false, false, null);
			$this->processTypeResponse($type_instance, "on", false, null);
			$this->processTypeResponse($type_instance, "yes", false, null);
			$this->processTypeResponse($type_instance, "1", false, null);
			$this->processTypeResponse($type_instance, "0", false, null);
			$this->processTypeResponse($type_instance, "", false, null);
			$this->processTypeResponse($type_instance, "-1", false, null);
			$this->processTypeResponse($type_instance, " ", false, null);
			$this->processTypeResponse($type_instance, 1, false, null);
			$this->processTypeResponse($type_instance, 1.5, false, null);
			$this->processTypeResponse($type_instance, 0, false, null);
			$this->processTypeResponse($type_instance, -1, false, null);
			$this->processTypeResponse($type_instance, null, true, null);
			$this->processTypeResponse($type_instance, [], false, null);
			$this->processTypeResponse($type_instance, [true], false, null);
		}

		/**
		 * Executa testes no tipo padrão empty.
		 * @return void
		 */
		public function testTypeEmpty() {
			$type_instance = self::$awk->type("empty");

			$this->processTypeResponse($type_instance, true, false, null);
			$this->processTypeResponse($type_instance, false, true, null);
			$this->processTypeResponse($type_instance, "on", false, null);
			$this->processTypeResponse($type_instance, "yes", false, null);
			$this->processTypeResponse($type_instance, "1", false, null);
			$this->processTypeResponse($type_instance, "0", true, null);
			$this->processTypeResponse($type_instance, "", true, null);
			$this->processTypeResponse($type_instance, "-1", false, null);
			$this->processTypeResponse($type_instance, " ", false, null);
			$this->processTypeResponse($type_instance, 1, false, null);
			$this->processTypeResponse($type_instance, 1.5, false, null);
			$this->processTypeResponse($type_instance, 0, true, null);
			$this->processTypeResponse($type_instance, -1, false, null);
			$this->processTypeResponse($type_instance, null, true, null);
			$this->processTypeResponse($type_instance, [], true, null);
			$this->processTypeResponse($type_instance, [true], false, null);
		}

		/**
		 * Executa testes no tipo padrão int.
		 * @return [type] [description]
		 */
		public function testTypeInt() {
			$type_instance = self::$awk->type("int");

			$this->processTypeResponse($type_instance, true, false, 1);
			$this->processTypeResponse($type_instance, false, false, 0);
			$this->processTypeResponse($type_instance, "on", false, 0);
			$this->processTypeResponse($type_instance, "yes", false, 0);
			$this->processTypeResponse($type_instance, "1", true, 1);
			$this->processTypeResponse($type_instance, "0", true, 0);
			$this->processTypeResponse($type_instance, "", false, 0);
			$this->processTypeResponse($type_instance, "-1", true, -1);
			$this->processTypeResponse($type_instance, " ", false, 0);
			$this->processTypeResponse($type_instance, 1, true, 1);
			$this->processTypeResponse($type_instance, 1.5, false, 1);
			$this->processTypeResponse($type_instance, 0, true, 0);
			$this->processTypeResponse($type_instance, -1, true, -1);
			$this->processTypeResponse($type_instance, null, false, 0);
			$this->processTypeResponse($type_instance, [], false, 0);
			$this->processTypeResponse($type_instance, [true], false, 0);
		}

		/**
		 * Executa testes no tipo padrão float.
		 * @return void
		 */
		public function testTypeFloat() {
			$type_instance = self::$awk->type("float");

			$this->processTypeResponse($type_instance, true, false, 1.0);
			$this->processTypeResponse($type_instance, false, false, 0.0);
			$this->processTypeResponse($type_instance, "on", false, 0.0);
			$this->processTypeResponse($type_instance, "yes", false, 0.0);
			$this->processTypeResponse($type_instance, "1", true, 1.0);
			$this->processTypeResponse($type_instance, "0", true, 0.0);
			$this->processTypeResponse($type_instance, "", false, 0.0);
			$this->processTypeResponse($type_instance, "-1", true, -1.0);
			$this->processTypeResponse($type_instance, " ", false, 0.0);
			$this->processTypeResponse($type_instance, 1, true, 1.0);
			$this->processTypeResponse($type_instance, 1.5, true, 1.5);
			$this->processTypeResponse($type_instance, 0, true, 0.0);
			$this->processTypeResponse($type_instance, -1, true, -1.0);
			$this->processTypeResponse($type_instance, null, false, 0.0);
			$this->processTypeResponse($type_instance, [], false, 0.0);
			$this->processTypeResponse($type_instance, [true], false, 0.0);
		}

		/**
		 * Executa testes no tipo padrão string.
		 * @return void
		 */
		public function testTypeString() {
			$type_instance = self::$awk->type("string");

			$this->processTypeResponse($type_instance, true, true, "1");
			$this->processTypeResponse($type_instance, false, true, "");
			$this->processTypeResponse($type_instance, "on", true, "on");
			$this->processTypeResponse($type_instance, "yes", true, "yes");
			$this->processTypeResponse($type_instance, "1", true, "1");
			$this->processTypeResponse($type_instance, "0", true, "0");
			$this->processTypeResponse($type_instance, "", true, "");
			$this->processTypeResponse($type_instance, "-1", true, "-1");
			$this->processTypeResponse($type_instance, " ", true, " ");
			$this->processTypeResponse($type_instance, 1, true, "1");
			$this->processTypeResponse($type_instance, 1.5, true, "1.5");
			$this->processTypeResponse($type_instance, 0, true, "0");
			$this->processTypeResponse($type_instance, -1, true, "-1");
			$this->processTypeResponse($type_instance, null, false, "");
			$this->processTypeResponse($type_instance, [], false, "");
			$this->processTypeResponse($type_instance, [true], false, "");
		}

		/**
		 * Executa testes em um tipo exclusivo do módulo.
		 * @return void
		 */
		public function testTypeFromModule() {
			$type_instance = self::$module->type("test");

			$this->assertInstanceOf("Awk_Type", $type_instance);
			$this->processTypeResponse($type_instance, true, false, null);
		}

		/**
		 * Obtém um mesmo tipo, novamente, para testar o cache.
		 * @depends testTypeFromModule
		 * @return void
		 */
		public function testTypeReload() {
			$type_instance = self::$module->type("test");

			$this->assertInstanceOf("Awk_Type", $type_instance);
		}

		/**
		 * Uma exceção deve ser lançada quando um teste não existe no módulo.
		 * @expectedException        Awk_Error_Exception
		 * @expectedExceptionMessage O módulo "awk_suite" não possui o tipo "unexistent".
		 * @return void
		 */
		public function testUnexistentException() {
			self::$module->type("unexistent");
		}
	}
