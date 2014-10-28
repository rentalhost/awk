<?php

    /**
     * @covers Awk_Router_Driver
     * @covers Awk_Router_Driver_Stack
     */
    class Awk_Router_DriverTest extends PHPUnit_Framework_TestCase {
        /**
         * Testa as rotas.
         * @dataProvider providerRoutes
         */
        public function testRoutes($route, $expected_output) {
            $test_driver = new Awk_Router_Driver($route, Awk_Module::get("awk_suite"));
            $test_driver->redirect("test1_basic");

            $this->expectOutputString($expected_output);
        }

        /**
         * Provedor de rotas.
         */
        public function providerRoutes() {
            return [
                [ "",                       "root" ],
                [ "simple_route",           "tunnel->simple_route" ],
                [ "get_router",             "tunnel->Awk_Router" ],
                [ "router_view",            "tunnel->Hello World!" ],
                [ "router_router",          "tunnel->tunnel[test3_router]" ],
                [ "router_controller",      "tunnel->router_controller" ],
                [ "arg/hello world!",       "tunnel->captured[hello world!]" ],
                [ "preserve/simple_route",  "tunnel->preserved[]->simple_route_preserved" ],
                [ "simple_other",           "tunnel->redirected[test2_router]->simple_other" ],
                [ "fail",                   "tunnel->redirected[test2_router]->" ],
            ];
        }
    }
