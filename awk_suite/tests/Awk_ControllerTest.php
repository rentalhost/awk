<?php

    /**
     * @covers Awk_Controller
     * @covers Awk_Controller_Feature
     * @covers Awk_Module_Base::__construct
     */
    class Awk_ControllerTest extends PHPUnit_Framework_TestCase {
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
         * Testa a instância.
         */
        public function testControllerLoad() {
            $controller_instance = self::$module->controller("test1_valid");

            $this->assertInstanceOf("Awk_Base", $controller_instance);

            return $controller_instance;
        }

        /**
         * Testa Awk_Base.
         * @covers Awk_Base::get_parent
         * @covers Awk_Base::get_id
         * @depends testControllerLoad
         */
        public function testAwkBase($controller_instance) {
            $this->assertInstanceOf("Awk_Controller", $controller_instance->get_parent());
            $this->assertSame("controller@awk_suite->test1_valid", $controller_instance->get_id());
        }

        /**
         * Testa novamente, para cache.
         */
        public function testControllerReload() {
            $this->testControllerLoad();
        }

        /**
         * Tenta carregar um controller inexistente.
         * @expectedException        Awk_Controller_NotExists_Exception
         * @expectedExceptionMessage O Controller "unexistent" não existe no módulo "awk_suite".
         * @return [type] [description]
         */
        public function testUnexistentException() {
            self::$module->controller("unexistent");
        }

        /**
         * Tenta carregar um controller que não registrou a classe.
         * @expectedException        Awk_Controller_WasNotRegisteredClass_Exception
         * @expectedExceptionMessage O Controller "test3_unregistered_class" do módulo "awk_suite" não registrou uma classe.
         * @return [type] [description]
         */
        public function testUnregisteredClassException() {
            self::$module->controller("test3_unregistered_class");
        }

        /**
         * Tenta carregar um controller que registrou uma classe inexistente.
         * @expectedException        Awk_Controller_RegisteredNotFoundClass_Exception
         * @expectedExceptionMessage O Controller "test2_unexistent_class" do módulo "awk_suite" registrou a classe "Unexistent_Class", mas ela não foi encontrada.
         * @return [type] [description]
         */
        public function testUnexistentClassException() {
            self::$module->controller("test2_unexistent_class");
        }
    }
