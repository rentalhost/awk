<?php

    /**
     * @covers Awk_Syntax_Object
     */
    class Awk_SyntaxTest extends PHPUnit_Framework_TestCase {
        /**
         * Gera um padrão de teste.
         */
        private function checkObjectDefinition($object_definition, $replacement_array) {
            $module      = Awk_Module::get("awk_suite");
            $test_object = Awk_Syntax_Object::create($module, $object_definition);

            // Valores esperados, por padrão.
            // É substituído pelo replacement.
            $replacement_array = array_replace([
                "module"            => $module,
                "method"            => null,
                "method_module"     => null,
                "name"              => null,
                "name_group"        => null,
                "name_alias"        => null,
                "type"              => null,
                "type_module"       => null,
                "repeat"            => null,
                "repeat_min"        => null,
                "repeat_max"        => null,
                "optional"          => null,
                "arguments"         => null,
            ], $replacement_array);

            $this->assertSame($replacement_array["module"],         $test_object->module);
            $this->assertSame($replacement_array["method"],         $test_object->method);
            $this->assertSame($replacement_array["method_module"],  $test_object->method_module);
            $this->assertSame($replacement_array["name"],           $test_object->name);
            $this->assertSame($replacement_array["name_group"],     $test_object->name_group);
            $this->assertSame($replacement_array["name_alias"],     $test_object->name_alias);
            $this->assertSame($replacement_array["type"],           $test_object->type);
            $this->assertSame($replacement_array["type_module"],    $test_object->type_module);
            $this->assertSame($replacement_array["repeat"],         $test_object->repeat);
            $this->assertSame($replacement_array["repeat_min"],     $test_object->repeat_min);
            $this->assertSame($replacement_array["repeat_max"],     $test_object->repeat_max);
            $this->assertSame($replacement_array["optional"],       $test_object->optional);
            $this->assertEquals($replacement_array["arguments"],    $test_object->arguments);
        }

        /**
         * Testa a criação dos objetos.
         * @dataProvider providerObjectDefinitions
         */
        public function testCreate($definition, $expected_array) {
            $this->checkObjectDefinition($definition, $expected_array);
        }

        /**
         * Provedor de objetos.
         */
        public function providerObjectDefinitions() {
            // Define um módulo base.
            $module = Awk_Module::get("awk_suite");

            // Define um argumento simples.
            $object_argument_id = new Awk_Syntax_Object;
            $object_argument_id->module = $module;
            $object_argument_id->name = "id";
            $object_argument_id->name_type = "object";

            // Define um argumento com aliases.
            $object_argument_id_alias = clone $object_argument_id;
            $object_argument_id_alias->name_alias = "alias";

            // Define um objeto para user.
            $object_argument_user = clone $object_argument_id;
            $object_argument_user->name = "user";

            // Define um argumento name com aliases.
            $object_argument_user_alias = clone $object_argument_user;
            $object_argument_user_alias->name_alias = "alias";

            // Define um argumento coringa.
            $object_argument_asterisk = new Awk_Syntax_Object;
            $object_argument_asterisk->module = $module;
            $object_argument_asterisk->name = "*";
            $object_argument_asterisk->name_type = "object";

            // Define um argumento tipado.
            $object_argument_typed = new Awk_Syntax_Object;
            $object_argument_typed->module = $module;
            $object_argument_typed->name = "id";
            $object_argument_typed->name_type = "object";
            $object_argument_typed->type = "date";
            $object_argument_typed->type_module = "module";

            return [
                # Teste de comentários.
                100 =>  [ "::Hello World!",      [ "method" => "comment", "arguments" => "Hello World!" ] ],
                        [ "::Hello \\\n World!", [ "method" => "comment", "arguments" => "Hello World!" ] ],

                # Teste de variáveis simples.
                200 =>  [ ":{@test}",             [ "name" => "test", "name_type" => "variable" ] ],
                        [ ":{int @test}",         [ "name" => "test", "name_type" => "variable", "type" => "int" ] ],
                        [ ":{module->int @test}", [ "name" => "test", "name_type" => "variable", "type" => "int", "type_module" => "module" ] ],
                        [ ":{@test as @test2}",   [ "name" => "test", "name_type" => "variable", "name_alias" => "test2" ] ],
                        [ ":{@test AS @test2}",   [ "name" => "test", "name_type" => "variable", "name_alias" => "test2" ] ],
                        [ ":{module->int @test as @test2}", [ "name" => "test", "name_type" => "variable", "name_alias" => "test2", "type" => "int", "type_module" => "module" ] ],

                # Teste de objetos simples.
                300 =>  [ ":{test}",   [ "name" => "test", "name_type" => "object" ] ],
                        [ ":{test#1}", [ "name" => "test", "name_type" => "object", "name_group" => "1" ] ],

                # Teste de argumento simples.
                400 =>  [ ":{test.id}",   [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],
                        [ ":{test .id}",  [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],
                        [ ":{test. id}",  [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],
                        [ ":{test . id}", [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],
                        [ ":{test: id}",  [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],
                        [ ":{test :id}",  [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id ] ] ],

                # Teste de argumentos com aliases.
                500 =>  [ ":{test.id as alias}",   [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id_alias ] ] ],
                        [ ":{test#1.id as alias}", [ "name" => "test", "name_type" => "object", "name_group" => "1", "arguments" => [ $object_argument_id_alias ] ] ],

                # Teste com múltiplos argumentos.
                600 =>  [ ":{test: id, user}",                     [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_id, $object_argument_user ] ] ],
                        [ ":{test#1: id, user}",                   [ "name" => "test", "name_type" => "object", "name_group" => "1", "arguments" => [ $object_argument_id, $object_argument_user ] ] ],
                        [ ":{test#1: id, user as alias}",          [ "name" => "test", "name_type" => "object", "name_group" => "1", "arguments" => [ $object_argument_id, $object_argument_user_alias ] ] ],
                        [ ":{test#1: id as alias, user as alias}", [ "name" => "test", "name_type" => "object", "name_group" => "1", "arguments" => [ $object_argument_id_alias, $object_argument_user_alias ] ] ],

                # Teste com coringas.
                700 =>  [ ":{test.*}",                       [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_asterisk ] ] ],
                        [ ":{test#1: *, id as alias, user}", [ "name" => "test", "name_type" => "object", "name_group" => "1", "arguments" => [ $object_argument_asterisk, $object_argument_id_alias, $object_argument_user ] ] ],

                # Teste com argumento tipado.
                800 =>  [ ":{test: module->date id}", [ "name" => "test", "name_type" => "object", "arguments" => [ $object_argument_typed ] ] ],

                # Teste de repetidores.
                900 =>  [ ":{int}?",        [ "name" => "int", "name_type" => "object", "optional" => true ] ],
                        [ ":{int}*",        [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 0 ] ],
                        [ ":{int}+",        [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 1 ] ],
                        [ ":{int}*?",       [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 0, "optional" => true ] ],
                        [ ":{int}+?",       [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 1, "optional" => true ] ],
                        [ ":{int}{3}",      [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 3, "repeat_max" => 3 ] ],
                        [ ":{int}{3,}",     [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 3 ] ],
                        [ ":{int}{3,}?",    [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 3, "optional" => true ] ],
                        [ ":{int}{,3}",     [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_max" => 3 ] ],
                        [ ":{int}{2,3}",    [ "name" => "int", "name_type" => "object", "repeat" => true, "repeat_min" => 2, "repeat_max" => 3 ] ],
            ];
        }

        /**
         * Teste de métodos.
         * @dataProvider providerMethodDefinitions
         */
        public function testCreateMethod($definition, $expected_array) {
            $this->checkObjectDefinition($definition, $expected_array);
        }

        /**
         * Provedor de métodos.
         */
        public function providerMethodDefinitions() {
            return [
                // Teste de statement.
                100 =>  [ ":statement",         [ "method" => "statement", "method_type" => "statement" ] ],

                // Teste de statement com argumentos.
                200 =>  [ ":statement test",    [ "method" => "statement", "method_type" => "statement", "arguments" => "test" ] ],

                // Teste de método sem parâmetros.
                300 =>  [ ":method{}",          [ "method" => "method", "method_type" => "method", "arguments" => [] ] ],

                // Teste de método de outro módulo.
                400 =>  [ ":module->method{}",  [ "method" => "method", "method_type" => "method", "method_module" => "module", "arguments" => [] ] ],

                // Teste de método com argumento.
                500 =>  [ ":method{123}",       [ "method" => "method", "method_type" => "method", "arguments" => [ "123" ] ] ],
                        [ ":method{true}",      [ "method" => "method", "method_type" => "method", "arguments" => [ "true" ] ] ],
                        [ ":method{false}",     [ "method" => "method", "method_type" => "method", "arguments" => [ "false" ] ] ],
                        [ ":method{null}",      [ "method" => "method", "method_type" => "method", "arguments" => [ "null" ] ] ],
                        [ ":method{\"Hello\"}", [ "method" => "method", "method_type" => "method", "arguments" => [ "\"Hello\"" ] ] ],
                        [ ":method{'Hello'}",   [ "method" => "method", "method_type" => "method", "arguments" => [ "'Hello'" ] ] ],
                        [ ":method{[]}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "[]" ] ] ],
                        [ ":method{[1=>0]}",    [ "method" => "method", "method_type" => "method", "arguments" => [ "[1=>0]" ] ] ],
                        [ ":method{!1}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "!1" ] ] ],
                        [ ":method{CONSTANT}",  [ "method" => "method", "method_type" => "method", "arguments" => [ "CONSTANT" ] ] ],
                        [ ":method{long 123}",  [ "method" => "method", "method_type" => "method", "arguments" => [ "long 123" ] ] ],

                // Teste de método com múltiplos argumentos.
                600 =>  [ ":method{1, 2, 3}",               [ "method" => "method", "method_type" => "method", "arguments" => [ "1", "2", "3" ] ] ],
                        [ ":method{'a', 'b', 'c'}",         [ "method" => "method", "method_type" => "method", "arguments" => [ "'a'", "'b'", "'c'" ] ] ],
                        [ ":method{true, false}",           [ "method" => "method", "method_type" => "method", "arguments" => [ "true", "false" ] ] ],

                // Teste de método com sub-métodos simples.
                700 =>  [ ":method{is_string(\"hello\")}",  [ "method" => "method", "method_type" => "method", "arguments" => [ "is_string(\"hello\")" ] ] ],
                        [ ":method{is_string(\"hello\"), is_int(1)}", [ "method" => "method", "method_type" => "method", "arguments" => [ "is_string(\"hello\")", "is_int(1)" ] ] ],

                // Teste de método com sub-métodos syntax.
                800 =>  [ ":method{:method2{\"hello\"}}",   [ "method" => "method", "method_type" => "method", "arguments" => [ ":method2{\"hello\"}" ] ] ],

                // Teste de método com sub-métodos complexo.
                900 =>  [ ":method{test(1, 2)}",            [ "method" => "method", "method_type" => "method", "arguments" => [ "test(1, 2)" ] ] ],
                        [ ":method{:test{1, 2}}",           [ "method" => "method", "method_type" => "method", "arguments" => [ ":test{1, 2}" ] ] ],

                // Teste de método com escapamento de caracteres.
                1000 => [ ":method{\,}",            [ "method" => "method", "method_type" => "method", "arguments" => [ "," ] ] ],
                        [ ":method{()}",            [ "method" => "method", "method_type" => "method", "arguments" => [ "()" ] ] ],
                        [ ":method{\{\}}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "{}" ] ] ],
                        [ ":method{(,)}",           [ "method" => "method", "method_type" => "method", "arguments" => [ "(,)" ] ] ],
                        [ ":method{(\,)}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "(\,)" ] ] ],
                        [ ":method{(, )}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "(, )" ] ] ],
                        [ ":method{\{, \}}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "{", "}" ] ] ],
                        [ ":method{{,}}",           [ "method" => "method", "method_type" => "method", "arguments" => [ "{,}" ] ] ],
                        [ ":method{(\"\")}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "(\"\")" ] ] ],
                        [ ":method{\"{}\"}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "\"{}\"" ] ] ],
                        [ ":method{\"{\\\"}\"}",    [ "method" => "method", "method_type" => "method", "arguments" => [ "\"{\\\"}\"" ] ] ],
                        [ ":method{\",\"}",         [ "method" => "method", "method_type" => "method", "arguments" => [ "\",\"" ] ] ],
                        [ ":method{\"\\\"\"}",      [ "method" => "method", "method_type" => "method", "arguments" => [ "\"\\\"\"" ] ] ],
                        [ ":method{''}",            [ "method" => "method", "method_type" => "method", "arguments" => [ "''" ] ] ],
                        [ ":method{'\"'}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "'\"'" ] ] ],
                        [ ":method{'\''}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "'\''" ] ] ],
                        [ ":method{','}",           [ "method" => "method", "method_type" => "method", "arguments" => [ "','" ] ] ],
                        [ ":method{`exec`}",        [ "method" => "method", "method_type" => "method", "arguments" => [ "`exec`" ] ] ],
                        [ ":method{'\,'}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "'\,'" ] ] ],
                        [ ":method{\\\\}",          [ "method" => "method", "method_type" => "method", "arguments" => [ "\\" ] ] ],
                        [ ":method{\\b}",           [ "method" => "method", "method_type" => "method", "arguments" => [ "\\b" ] ] ],
            ];
        }

        /**
         * Exceção quando uma definição de objeto é inválida.
         * @dataProvider providerUnsupportedFormats
         */
        public function testAwk_Syntax_InvalidFormat_Exception($object_definition) {
            $this->setExpectedException(
                "Awk_Syntax_UnsupportedFormat_Exception",
                "A definição \"{$object_definition}\" possui um formato não suportado."
            );

            $module      = Awk_Module::get("awk_suite");
            $test_object = Awk_Syntax_Object::create($module, $object_definition);
        }

        /**
         * Formatos não suportados.
         */
        public function providerUnsupportedFormats() {
            return [
                [ ":method{} invalid" ],
                [ ":{test.}" ],
                [ ":{test.id,test}" ],
                [ ":{test:}" ],
                [ ":{test: unsupported definition format}" ],
                [ ":{test}{,}" ],
                [ ":{test}{}" ],
                [ ":{test}{-1}" ],
                [ ":{test}{1}*" ],
                [ ":{test}{1}+" ],
                [ ":method{,}" ],
                [ ":method{1,}" ],
                [ ":method{1, }" ],
                [ ":method{,1}" ],
                [ ":method{ ,1}" ],
                [ ":method{\\}" ],
                [ ":method{{}" ],
                [ ":method{{[}}" ],
                [ ":method{{[()]}" ],
                [ ":method{\"}" ],
                [ ":method{'}" ],
                [ ":method{'\\}" ],
            ];
        }
    }
