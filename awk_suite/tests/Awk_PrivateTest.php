<?php

    /**
     * @covers Awk_Private
     * @covers Awk_Private_Feature
     */
    class Awk_PrivateTest extends PHPUnit_Framework_TestCase {
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
         * Testa um arquivo privado.
         */
        public function testPrivateFile() {
            $private_instance = self::$module->private("test1_file.php");

            $this->assertTrue($private_instance->get_path()->exists());
        }
    }
