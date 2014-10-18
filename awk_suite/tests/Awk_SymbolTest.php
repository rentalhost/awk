<?php

    /**
     * @covers Awk_Symbol
     */
    class Awk_SymbolTest extends PHPUnit_Framework_TestCase {
        /**
         * Testa um símbolo.
         */
        public function testSymbol() {
            $test1 = new Awk_Symbol("symbol");
            $test2 = new Awk_Symbol("symbol");

            // Dois simbolos nunca são idênticos.
            $this->assertFalse($test1->is($test2));
            $this->assertFalse($test2->is($test1));

            // Mas um símbolo sempre aceita a própria instância.
            $this->assertTrue($test1->is($test1));

            // Ou a mesma mensagem.
            $this->assertTrue($test1->is_similar($test2));
            $this->assertTrue($test2->is_similar($test1));

            // Verifica por um identificador.
            $this->assertTrue($test1->is("symbol"));
        }

        /**
         * Testa um símbolo em um objeto complexo.
         */
        public function testSymbolAsKey() {
            $array = [];
            $array[Awk_Symbol::create("user")] = "John Doe";
            $array[Awk_Symbol::create("user")] = "Jones Doe";

            foreach(array_keys($array) as $array_key) {
                $this->assertSame("user", Awk_Symbol::get($array_key)->message);
            }
        }

        /**
         * Testa um símbolo armazenando dados em sua mensagem.
         */
        public function testSymbolAsContainer() {
            $test1 = new Awk_Symbol([ "test2" => true ]);

            $this->assertInternalType("array", $test1->message);
            $this->assertSame([ "test2" => true ], $test1->message);
        }

        /**
         * Testa um símbolo inexistente.
         * @expectedException           Awk_Symbol_NotConstructed_Exception
         * @expectedExceptionMessage    O símbolo "unexistent" nunca foi construído.
         */
        public function testSymbolUnexistent() {
            Awk_Symbol::get("unexistent");
        }
    }
