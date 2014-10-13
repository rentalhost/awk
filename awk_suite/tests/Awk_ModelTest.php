<?php

    /**
     * @covers Awk_Model
     * @covers Awk_Model_Feature
     */
    class Awk_ModelTest extends PHPUnit_Framework_TestCase {
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
         * Testa a base de um model.
         */
        public function testModelBase() {
            $model_instance = self::$module->model("test1_base");

            $this->assertSame(null, $model_instance->get_table());
            $this->assertSame("suite_", $model_instance->get_prefix());
        }

        /**
         * Testa um model que define diretamente uma tabela.
         */
        public function testModelDirect() {
            $model_instance = self::$module->model("test2_direct");

            $this->assertSame("suite_test", $model_instance->get_table());
            $this->assertSame(null, $model_instance->get_prefix());
        }

        /**
         * Testa um módulo com uma base.
         * @return Awk_Model
         */
        public function testModelExtended() {
            $model_instance = self::$module->model("test3_extends");

            $this->assertSame("suite_test", $model_instance->get_table());
            $this->assertSame("suite_", $model_instance->get_prefix());

            return $model_instance;
        }

        /**
         * Testa um método da query.
         * @depends testModelExtended
         */
        public function testModelQueryMethod($model_instance) {
            $this->assertSame([ 1 => "1" ], $model_instance->load_test()->get_array());
        }

        /**
         * Exceção quando um model não existe.
         * @expectedException        Awk_Model_NotExists_Exception
         * @expectedExceptionMessage O Model "unexistent" não existe no módulo "awk_suite".
         */
        public function testModelUnexistentException() {
            self::$module->model("unexistent");
        }

        /**
         * Exceção quando uma query é re-definida.
         * @expectedException        Awk_Model_QueryAlreadyExists_Exception
         * @expectedExceptionMessage A Query "load_test" já foi definida no Model "test3_extends" do módulo "awk_suite".
         * @depends testModelExtended
         */
        public function testModelQueryExistsException($model_instance) {
            $model_instance->add_query("load_test", null, null);
        }

        /**
         * Exceção quando uma query não definida é executada.
         * @expectedException        Awk_Model_QueryNotExists_Exception
         * @expectedExceptionMessage A Query "load_unknow" não foi definida no Model "test3_extends" do módulo "awk_suite".
         * @depends testModelExtended
         */
        public function testModelQueryNotDefinedException($model_instance) {
            $model_instance->load_unknow();
        }
    }
