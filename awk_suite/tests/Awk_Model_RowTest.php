<?php

	/**
	 * @covers Awk_Model_Row
	 * @covers Awk_Model_Query
	 */
	class Awk_Model_RowText extends PHPUnit_Framework_TestCase {
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
		 * Carrega um model.
		 * @return void
		 */
		public function testModelLoad() {
			$model_instance = self::$module->model("test3_extends");

			$this->assertInstanceOf("Awk_Model", $model_instance);

			return $model_instance;
		}

		/**
		 * Testa uma query anexada ao model.
		 * @depends testModelLoad
		 * @return void
		 */
		public function testModelQueryInstance($model_instance) {
			$result_row_instance = $model_instance->load_test();

			// Teste de obtenção de resultado.
			$this->assertSame([ "1" => "1" ], $result_row_instance->get_array());
			$this->assertSame("1", $result_row_instance->__get("1"));
			$this->assertTrue($result_row_instance->__isset("1"));

			// Testa remoções.
			$result_row_instance->__unset("1");
			$this->assertFalse($result_row_instance->__isset("1"));

			// Testa inclusões.
			$result_row_instance->test = "test";
			$this->assertSame("test", $result_row_instance->test);
			$this->assertTrue(isset($result_row_instance->test));
		}

		/**
		 * Testa uma exceção quando um tipo não suportado é vinculado ao model.
		 * @depends testModelLoad
		 * @expectedException Awk_Exception
		 * @expectedExceptionMessage Atualmente, não há suporte para a query do tipo "unsupported_type" em um model.
		 * @return void
		 */
		public function testModelUnsupportedTypeException($model_instance) {
			$model_instance->add_query("unsupported_type", "unsupported_type", null);
		}

		/**
		 * Testa uma exceção há uma falha ao carregar uma query.
		 * @depends testModelLoad
		 * @expectedException Awk_Exception
		 * @expectedExceptionMessage Falha ao executar a query.
		 * @return void
		 */
		public function testModelQueryFailException($model_instance) {
			$model_instance->load_fail();
		}
	}
