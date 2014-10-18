<?php

    /**
     * @covers Awk_Event
     * @covers Awk_Event_Handlers
     * @covers Awk_Event_Object
     */
    class Awk_EventTest extends PHPUnit_Framework_TestCase {
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
         * Teste de atribuição, desatribuição e verificação.
         */
        public function testOnOffHas() {
            $event = new Awk_Event(null);

            // Verifica se um evento existe.
            $this->assertFalse($event->has("create"));

            // Então cria um evento e verifica se ele passou a existir.
            $event->on("test1", function() { });
            $this->assertTrue($event->has("test1"));

            // Remove a atribuição e verifica.
            $event->off("test1");
            $this->assertFalse($event->has("test1"));

            // Tenta remover novamente (no side effect).
            $event->off("test1");
            $this->assertFalse($event->has("test1"));

            // Atribui dois eventos ao mesmo tempo.
            $event->on("test2 test3", function() { });
            $this->assertTrue($event->has("test2"));
            $this->assertTrue($event->has("test3"));

            // Remove as atribuições e verifica.
            $event->off("test2");
            $this->assertFalse($event->has("test2"));
            $this->assertTrue($event->has("test3"));

            $event->off("test2 test3");
            $this->assertFalse($event->has("test2"));
            $this->assertFalse($event->has("test3"));

            // Atribui um evento não-normalizado.
            $event->on("  test4  ", function() { });
            $this->assertTrue($event->has("test4"));

            // Não atribiu o evento por nada definir.
            $event->on("", function() { });
            $this->assertFalse($event->has(""));
        }

        /**
         * Teste de desatribuição específica por callable.
         */
        public function testOffCallable() {
            $event = new Awk_Event(null);

            // Armazena uma função anônima.
            $stored_anonymous = function() { };

            // Atribui vários métodos diferentes.
            // Nota: uma função anônima nunca é comparável.
            //       Porém, se há uma referência direta (variável), indica a mesma.
            $event->on("test1", function() { });
            $event->on("test2", "is_string");
            $event->on("test3", "self::testOffCallable");
            $event->on("test4", [ get_class($this), "testOffCallable" ]);
            $event->on("test5", [ $this, "testOffCallable" ]);
            $event->on("test6", $stored_anonymous);

            // Desatribui incorretamente todas as definições anteriores.
            $event->off("test1", function() { });
            $event->off("test2 test3 test4 test5 test6", "is_null");

            // Verifica que tudo continua normal.
            $this->assertTrue($event->has("test1"));
            $this->assertTrue($event->has("test2"));
            $this->assertTrue($event->has("test3"));
            $this->assertTrue($event->has("test4"));
            $this->assertTrue($event->has("test5"));
            $this->assertTrue($event->has("test6"));

            // Desatribui corretamente todas as definições.
            $event->off("test2", "is_string");
            $event->off("test3", "self::testOffCallable");
            $event->off("test4", [ get_class($this), "testOffCallable" ]);
            $event->off("test5", [ $this, "testOffCallable" ]);
            $event->off("test6", $stored_anonymous);

            // E verifica.
            $this->assertFalse($event->has("test2"));
            $this->assertFalse($event->has("test3"));
            $this->assertFalse($event->has("test4"));
            $this->assertFalse($event->has("test5"));
            $this->assertFalse($event->has("test6"));
        }

        /**
         * Atribui vários callables a um único evento.
         */
        public function testManyOn() {
            $event = new Awk_Event(null);

            // Atribui mais a um mesmo evento, e desatribui apenas alguns callables.
            $event->on("test1", "is_string");
            $event->on("test1", "is_int");
            $event->on("test1", "is_bool");

            // O evento passa a existir.
            $this->assertTrue($event->has("test1"));

            // Então é removido apenas um dos callables.
            $event->off("test1", "is_string");

            $this->assertTrue($event->has("test1"));

            // Novamente.
            $event->off("test1", "is_int");

            $this->assertTrue($event->has("test1"));

            // Novamente, mas dessa vez, o evento deixa de existir.
            $event->off("test1", "is_bool");

            $this->assertFalse($event->has("test1"));
        }

        /**
         * Teste de execução dos eventos.
         */
        public function testTrigger() {
            $event_target = "targetObject";
            $event = new Awk_Event($event_target);

            // Adiciona o evento.
            $event_object = null;
            $event->on("test1", function($event) use(&$event_object) { $event_object = $event; });

            // Inicia o evento.
            $event->trigger("test1");

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame("test1", $event_object->type);
            $this->assertSame($event_target, $event_object->target);
            $this->assertSame([], $event_object->data->get_array());
            $this->assertSame(null, $event_object->result);

            // Remove o evento.
            $event->off("test1");
        }

        /**
         * Testa um cancelamento de evento.
         */
        public function testTriggerCancel() {
            $event = new Awk_Event(null);

            // Armazena o objeto contendo os dados obtidos.
            $event_object = null;

            // Adiciona os eventos.
            $event->on("test1", function($event) { return "firstTest"; });
            $event->on("test1", $event_function_canceller = function($event) use(&$event_object) { $event_object = $event; return false; });
            $event->on("test1", $event_function2 = function($event) use(&$event_object) { $event_object = $event; return "lastTest"; });

            // Inicia o evento.
            $event->trigger("test1");

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame(false, $event_object->result);

            // Remove o callable cancelador.
            $event->off("test1", $event_function_canceller);

            // Inicia o evento novamente.
            $event->trigger("test1");

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame("lastTest", $event_object->result);
        }

        /**
         * Testa o envio de dados para o evento.
         */
        public function testData() {
            $event = new Awk_Event(null);

            // Adiciona o evento.
            $event_object = null;
            $event->on("test1", function($event) use(&$event_object) {
                $event_object = $event;

                // Atribui novas informações.
                $event->data->test2 = true;
            });

            // Inicia o evento.
            $event->trigger("test1", [ "test1" => true ]);

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame([ "test1" => true, "test2" => true ], $event_object->data->get_array());
        }

        /**
         * Testa o enviuo de dados por argumentos.
         */
        public function testDataArguments() {
            $event = new Awk_Event(null);

            // Adiciona o evento.
            $test1_value = null;
            $test2_value = null;
            $event->on("test1", function($event, $test1, $test2) use(&$test1_value, &$test2_value) {
                $test1_value = $test1;
                $test2_value = $test2 . ".ok";
            });

            // Inicia o evento.
            $event->trigger("test1", [ "test1", "test2" ]);

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame("test1", $test1_value);
            $this->assertSame("test2.ok", $test2_value);
        }

        /**
         * Testa o enviuo de dados por argumentos com referência.
         */
        public function testDataArgumentsReference() {
            $event = new Awk_Event(null);

            // Adiciona o evento.
            $event->on("test1", function($event, &$test1) {
                $test1.= ".ok";
            });

            // Inicia o evento.
            $test1 = "test1";
            $event->trigger("test1", [ &$test1 ]);

            // Testa os dados obtidos no objeto de evento.
            $this->assertSame("test1.ok", $test1);
        }
    }
