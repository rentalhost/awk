<?php

    /**
     * @covers Awk_Module_Base
     */
    class Awk_Module_BaseTest extends PHPUnit_Framework_TestCase {
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
         * Testa os métodos da classe.
         * @return void
         */
        public function testClass() {
            $library_instance = self::$module->library("test1_valid_autoinit");

            $this->assertInstanceOf("Awk_Module", $library_instance->get_module());
            $this->assertInstanceOf("Awk_Library_Feature", $library_instance->get_parent());
            $this->assertInstanceOf("Awk_Module", $library_instance->get_parent()->get_module());

            $this->assertSame("test1_valid_autoinit", $library_instance->get_name());
            $this->assertSame("library@awk_suite->test1_valid_autoinit", $library_instance->get_id());

            $this->assertTrue(strpos($library_instance->get_path(false), "..") !== false);
            $this->assertSame(str_replace("\\", "/", realpath($library_instance->get_path()->get())), $library_instance->get_path()->get_normalized());
        }
    }
