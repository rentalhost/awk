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
         */
        public function testHelper() {
            $helper_instance = self::$module->helper("test1");

            $this->assertSame("Hello World!", $helper_instance->call("hello", "World"));
        }

        /**
         * Teste de cache da classe.
         */
        public function testHelperReaload() {
            $this->testHelper();
        }

        /**
         * Lança uma exceção quando o helper não existir.
         * @expectedException        Awk_Helper_NotExists_Exception
         * @expectedExceptionMessage O Helper "unexistent" não existe no módulo "awk_suite".
         */
        public function testUnexistentException() {
            self::$module->helper("unexistent");
        }
    }
