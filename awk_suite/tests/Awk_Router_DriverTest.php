<?php

    /**
     * @covers Awk_Router_Driver
     * @covers Awk_Router_Driver_Stack
     */
    class Awk_Router_DriverTest extends PHPUnit_Framework_TestCase {
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
         * Testa uma rota de raíz.
         */
        public function testDriverRootRoute() {
            $test_driver = new Awk_Router_Driver("", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("root");
        }

        /**
         * Testa uma rota simples, com um caminho estático.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverSimpleRoute() {
            $test_driver = new Awk_Router_Driver("simple_route", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->simple_route");
        }

        /**
         * Testa uma rota simples que obtém a classe do roteador.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverGetRouterRoute() {
            $test_driver = new Awk_Router_Driver("get_router", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->Awk_Router");
        }

        /**
         * Testa uma rota simples que carrega uma View através de um identificador.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverIdentifiedViewRoute() {
            $test_driver = new Awk_Router_Driver("router_view", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->Hello World!");
        }

        /**
         * Testa uma rota simples que transfere para uma nova rota através de um identificador.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverIdentifiedRouterRoute() {
            $test_driver = new Awk_Router_Driver("router_router", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->tunnel[test3_router]");
        }

        /**
         * Testa uma rota simples que transfere para um controller através de um identificador.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverIdentifiedControllerRoute() {
            $test_driver = new Awk_Router_Driver("router_controller", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->router_controller");
        }

        /**
         * Testa uma rota com captura de argumento.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverArgumentCaptureRoute() {
            $test_driver = new Awk_Router_Driver("arg/hello world!", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->captured[hello world!]");
        }

        /**
         * Testa uma rota com preserva de URL.
         * Este caminho vai através de um tunnel.
         */
        public function testDriverPreservedRoute() {
            $test_driver = new Awk_Router_Driver("preserve/simple_route", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->preserved[]->simple_route_preserved");
        }

        /**
         * Testa uma rota com redirecionamento.
         */
        public function testDriverRedirectedRouterRoute() {
            $test_driver = new Awk_Router_Driver("simple_other", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->redirected[test2_router]->simple_other");
        }

        /**
         * Testa uma rota com falha.
         */
        public function testDriverFailRoute() {
            $test_driver = new Awk_Router_Driver("fail", self::$module);
            $test_driver->redirect("test1_basic");

            $this->expectOutputString("tunnel->redirected[test2_router]->");
        }
    }
