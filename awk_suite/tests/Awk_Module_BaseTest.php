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
         */
        public function testClass() {
            $library_instance = self::$module->library("test1_valid_autoinit");

            $this->assertInstanceOf("Awk_Module",          $library_instance->module);
            $this->assertInstanceOf("Awk_Library_Feature", $library_instance->parent);
            $this->assertInstanceOf("Awk_Module",          $library_instance->parent->module);

            $this->assertSame("test1_valid_autoinit", $library_instance->name);

            $this->assertTrue(strpos($library_instance->path->get(), "..") !== false);
            $this->assertSame(str_replace("\\", "/", realpath($library_instance->path->get())), $library_instance->path->get_normalized());
        }

        /**
         * Testa a obtenção de identificadores.
         */
        public function testGetId() {
            $this->assertSame("controller@awk_suite->test1_valid",       self::$module->controller("test1_valid")->get_id());
            $this->assertSame("helper@awk_suite->test1",                 self::$module->helper("test1")->get_id());
            $this->assertSame("library@awk_suite->test1_valid_autoinit", self::$module->library("test1_valid_autoinit")->get_id());
            $this->assertSame("model@awk_suite->test1_base",             self::$module->model("test1_base")->get_id());
            $this->assertSame("private@awk_suite->test1_file",           self::$module->private("test1_file")->get_id());
            $this->assertSame("public@awk_suite->test1_hello",           self::$module->public("test1_hello")->get_id());
            $this->assertSame("router@awk_suite->test1_basic",           self::$module->router("test1_basic")->get_id());
            $this->assertSame("type@awk_suite->test1_complete", self::$module->type("test1_complete")->get_id());
            $this->assertSame("view@awk_suite->test1",                   self::$module->view("test1", null, true)->get_id());
        }

        /**
         * O recurso Database não suporta a obtenção do identificador.
         * @expectedException           Awk_Module_GetIdIsNotSupported_Exception
         * @expectedExceptionMessage    O recurso Database não possui suporte a obtenção de identificador.
         */
        public function testAwk_Database_GetIdIsNotSupported_Exception() {
            self::$module->database()->get_id();
        }

        /**
         * O recurso Settings não suporta a obtenção do identificador.
         * @expectedException           Awk_Module_GetIdIsNotSupported_Exception
         * @expectedExceptionMessage    O recurso Settings não possui suporte a obtenção de identificador.
         */
        public function testAwk_Settings_GetIdIsNotSupported_Exception() {
            self::$module->settings()->get_id();
        }
    }
