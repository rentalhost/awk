<?php

    /**
     * @covers Awk_Router_Route
     * @covers Awk_Router_Route_Part
     */
    class Awk_Router_Route_PartTest extends PHPUnit_Framework_TestCase {
        /**
         * Testa o processamento de URL.
         * @dataProvider providerURLProcess
         */
        public function testURLProcess($url_route, $expected_output) {
            $test_driver = new Awk_Router_Driver($url_route, Awk_Module::get("awk_suite"));
            $test_driver->redirect("test4_parts");

            $this->expectOutputString($expected_output);
        }

        /**
         * Provedor de testes.
         */
        public function providerURLProcess() {
            return [
                // Teste de argumentos.
                [ "args/simple",                    "simple" ],
                [ "args/123",                       "123,," ],
                [ "args/123/abc",                   "123,,abc" ],
                [ "args/123/1.5/abc",               "123,1.5,abc" ],
                [ "args/1.5/abc",                   ",1.5,abc" ],
                [ "args/1.5",                       ",1.5," ],

                // Teste com repetidores.
                [ "repeat/simple-one/1/2/3/abc",    "1,2,3" ],
                [ "repeat/simple-zero/abc",         "" ],
                [ "repeat/simple-zero/1/2/3/abc",   "1,2,3" ],
                [ "repeat/exactly/1/2/3/abc",       "1,2,3" ],
                [ "repeat/min/1/2/3/abc",           "1,2,3" ],
                [ "repeat/min/1/3/abc",             "fail" ],
                [ "repeat/min-optional/1/2/abc",    ",1" ],
                [ "repeat/max/1/2/3/abc",           "1,2,3" ],
                [ "repeat/max/1/2/3/4/abc",         "1,2,3" ],
                [ "repeat/ranged/1/abc",            "fail" ],
                [ "repeat/ranged/1/2/abc",          "1,2" ],
                [ "repeat/ranged/1/2/3/abc",        "1,2,3" ],
                [ "repeat/ranged/1/2/3/4/abc",      "1,2,3" ],

                // Testes diversos.
                [ "capture/hello",                  "hello" ],
                [ "fail",                           "fail" ],
            ];
        }
    }
