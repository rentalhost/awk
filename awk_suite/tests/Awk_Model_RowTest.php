<?php

    /**
     * @covers Awk_Model_Row
     * @covers Awk_Model_Query
     */
    class Awk_Model_RowTest extends PHPUnit_Framework_TestCase {
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
         */
        public function testModelLoad() {
            $model_instance = self::$module->model("test3_extends");

            $this->assertInstanceOf("Awk_Model", $model_instance);

            return $model_instance;
        }

        /**
         * Testa a testPropertyAccess.
         * @depends testModelLoad
         */
        public function testPropertyAccess($model_instance) {
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
            $this->assertSame([ "test" => "test" ], $result_row_instance->get_array());

            // Redefine e define informações.
            $result_row_instance->set_array([
                "test" => "ok2",
                "other" => "ok3"
            ]);

            $this->assertSame("ok2", $result_row_instance->test);
            $this->assertSame("ok3", $result_row_instance->other);

            // Elimina todas as definições.
            $result_row_instance->clear();
            $this->assertEmpty($result_row_instance->get_array());
        }

        /**
         * Adiciona uma nova Query.
         * @depends testModelLoad
         */
        public function testAddNewQuery($model_instance) {
            $model_instance->add_query("get_first", "one", "SELECT * FROM [this] LIMIT 1");
        }

        /**
         * Testa uma exceção quando um tipo não suportado é vinculado ao model.
         * @depends testModelLoad
         * @expectedException        Awk_Model_UnsupportedQueryType_Exception
         * @expectedExceptionMessage Não é suportado o tipo "unsupported_type" para Query.
         */
        public function testModelUnsupportedTypeException($model_instance) {
            $model_instance->add_query("unsupported_type", "unsupported_type", null);
        }

        /**
         * Testa uma exceção há uma falha ao carregar uma query.
         * @depends testModelLoad
         * @expectedException        Awk_Model_QueryError_Exception
         * @expectedExceptionMessage Falha ao executar a Query.
         */
        public function testModelQueryFailException($model_instance) {
            $model_instance->load_fail();
        }
    }
