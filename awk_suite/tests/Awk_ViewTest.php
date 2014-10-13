<?php

    /**
     * @covers Awk_View
     * @covers Awk_View_Feature
     */
    class Awk_ViewTest extends PHPUnit_Framework_TestCase {
        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Armazena a view que será testada.
         * @var Awk_View
         */
        static private $view_instance;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$module = Awk_Module::get("awk_suite");
        }

        /**
         * Carrega a View que será testada.
         */
        public function testViewLoad() {
            self::$view_instance = self::$module->view("test1", null, true);
            $this->assertInstanceOf("Awk_View", self::$view_instance);
        }

        /**
         * Testa as propriedades que podem ser obtidas da view.
         * @depends testViewLoad
         */
        public function testGetProperties() {
            $this->assertFalse(self::$view_instance->was_printed());
            $this->assertSame(1, self::$view_instance->get_return());
            $this->assertSame("Hello World!", self::$view_instance->get_contents());
            $this->assertSame("Hello World!", (string) self::$view_instance);
            $this->assertTrue(self::$view_instance->get_path()->exists());
        }

        /**
         * Verifica a impressão da View.
         * @depends testViewLoad
         */
        public function testViewOutput() {
            self::$module->view("test1");
            $this->expectOutputString("Hello World!");
        }

        /**
         * Verifica a impressão de uma view em um sub-diretório.
         * @depends testViewLoad
         */
        public function testViewOnSubdir() {
            self::$module->view("subdir/test2");
            $this->expectOutputString("Hello Again!");
        }

        /**
         * Verifica por uma view inexistente.
         * @depends testViewLoad
         */
        public function testViewUnexistent() {
            $view_instance = self::$module->view("subdir/unexistent");
            $this->assertSame(null, $view_instance->get_return());
            $this->assertEmpty($view_instance->get_contents());
            $this->assertFalse($view_instance->get_path()->exists());
        }
    }
