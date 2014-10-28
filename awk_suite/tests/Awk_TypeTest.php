<?php

    /**
     * @covers Awk_Type
     * @covers Awk_Type_Feature
     * @covers Awk_Type_Helper
     */
    class Awk_TypeTest extends PHPUnit_Framework_TestCase {
        /**
         * Módulo awk.
         * @var Awk_Module
         */
        static private $awk;

        /**
         * Módulo atual.
         * @var Awk_Module
         */
        static private $module;

        /**
         * Configurações antes da classe.
         */
        static public function setUpBeforeClass() {
            self::$awk    = Awk::$module;
            self::$module = Awk_Module::get("awk_suite");
        }

        /**
         * Verifica se um tipo retorna a resposta esperada.
         * @param  Awk_Type $type                Instância do controlador do tipo.
         * @param  mixed    $value               Valor a ser testado pelos métodos da instância.
         * @param  boolean  $value_will_validate Se o valor será validado corretamente.
         * @param  mixed    $value_transformed   Valor esperado após a transformação.
         */
        private function processTypeResponse($type, $value, $value_will_validate, $value_transformed) {
            $this->assertEquals($value_will_validate, $type->validate($value));
            $this->assertEquals($value_transformed, $type->transform($value));
        }

        /**
         * Executa os testes.
         * @dataProvider providerTypes
         */
        public function testTypes($type, $value, $value_will_validate, $value_transformed) {
            $type_instance = self::$awk->type($type);

            $this->processTypeResponse($type_instance, $value, $value_will_validate, $value_transformed);
        }

        /**
         * Provedor de tests.
         */
        public function providerTypes() {
            return [
                // Teste de aliases.
                0   =>  [ "bool",    true,   true,   true ],
                        [ "int",     true,   false,  1 ],
                        [ "double",  true,   false,  1.0 ],

                // Teste boolean.
                100 =>  [ "boolean", true,   true,   true ],
                        [ "boolean", false,  false,  false ],
                        [ "boolean", "on",   true,   true ],
                        [ "boolean", "yes",  true,   true ],
                        [ "boolean", "1",    true,   true ],
                        [ "boolean", "0",    false,  false ],
                        [ "boolean", "",     false,  false ],
                        [ "boolean", "-1",   false,  false ],
                        [ "boolean", " ",    false,  false ],
                        [ "boolean", 1,      true,   true ],
                        [ "boolean", 1.5,    false,  false ],
                        [ "boolean", 0,      false,  false ],
                        [ "boolean", -1,     false,  false ],
                        [ "boolean", null,   false,  false ],
                        [ "boolean", [],     false,  false ],
                        [ "boolean", [true], false,  false ],

                // Teste null.
                200 =>  [ "null",    true,   false,  null ],
                        [ "null",    false,  false,  null ],
                        [ "null",    "on",   false,  null ],
                        [ "null",    "yes",  false,  null ],
                        [ "null",    "1",    false,  null ],
                        [ "null",    "0",    false,  null ],
                        [ "null",    "",     false,  null ],
                        [ "null",    "-1",   false,  null ],
                        [ "null",    " ",    false,  null ],
                        [ "null",    1,      false,  null ],
                        [ "null",    1.5,    false,  null ],
                        [ "null",    0,      false,  null ],
                        [ "null",    -1,     false,  null ],
                        [ "null",    null,   true,   null ],
                        [ "null",    [],     false,  null ],
                        [ "null",    [true], false,  null ],

                // Teste empty.
                300 =>  [ "empty",   true,   false,  null ],
                        [ "empty",   false,  true,   null ],
                        [ "empty",   "on",   false,  null ],
                        [ "empty",   "yes",  false,  null ],
                        [ "empty",   "1",    false,  null ],
                        [ "empty",   "0",    true,   null ],
                        [ "empty",   "",     true,   null ],
                        [ "empty",   "-1",   false,  null ],
                        [ "empty",   " ",    false,  null ],
                        [ "empty",   1,      false,  null ],
                        [ "empty",   1.5,    false,  null ],
                        [ "empty",   0,      true,   null ],
                        [ "empty",   -1,     false,  null ],
                        [ "empty",   null,   true,   null ],
                        [ "empty",   [],     true,   null ],
                        [ "empty",   [true], false,  null ],

                // Teste integer.
                400 =>  [ "integer", true,   false,  1 ],
                        [ "integer", false,  false,  0 ],
                        [ "integer", "on",   false,  0 ],
                        [ "integer", "yes",  false,  0 ],
                        [ "integer", "1",    true,   1 ],
                        [ "integer", "0",    true,   0 ],
                        [ "integer", "",     false,  0 ],
                        [ "integer", "-1",   true,  -1 ],
                        [ "integer", " ",    false,  0 ],
                        [ "integer", 1,      true,   1 ],
                        [ "integer", 1.5,    false,  1 ],
                        [ "integer", 0,      true,   0 ],
                        [ "integer", -1,     true,  -1 ],
                        [ "integer", null,   false,  0 ],
                        [ "integer", [],     false,  0 ],
                        [ "integer", [true], false,  0 ],

                // Teste float.
                500 =>  [ "float",   true,   false,  1.0 ],
                        [ "float",   false,  false,  0.0 ],
                        [ "float",   "on",   false,  0.0 ],
                        [ "float",   "yes",  false,  0.0 ],
                        [ "float",   "1",    true,   1.0 ],
                        [ "float",   "0",    true,   0.0 ],
                        [ "float",   "",     false,  0.0 ],
                        [ "float",   "-1",   true,  -1.0 ],
                        [ "float",   " ",    false,  0.0 ],
                        [ "float",   1,      true,   1.0 ],
                        [ "float",   1.5,    true,   1.5 ],
                        [ "float",   0,      true,   0.0 ],
                        [ "float",   -1,     true,  -1.0 ],
                        [ "float",   null,   false,  0.0 ],
                        [ "float",   [],     false,  0.0 ],
                        [ "float",   [true], false,  0.0 ],

                // Teste string.
                600 =>  [ "string",  true,   true,   "1" ],
                        [ "string",  false,  true,   "" ],
                        [ "string",  "on",   true,   "on" ],
                        [ "string",  "yes",  true,   "yes" ],
                        [ "string",  "1",    true,   "1" ],
                        [ "string",  "0",    true,   "0" ],
                        [ "string",  "",     true,   "" ],
                        [ "string",  "-1",   true,   "-1" ],
                        [ "string",  " ",    true,   " " ],
                        [ "string",  1,      true,   "1" ],
                        [ "string",  1.5,    true,   "1.5" ],
                        [ "string",  0,      true,   "0" ],
                        [ "string",  -1,     true,   "-1" ],
                        [ "string",  null,   false,  "" ],
                        [ "string",  [],     false,  "" ],
                        [ "string",  [true], false,  "" ],
            ];
        }

        /**
         * Testa se um tipo existe.
         */
        public function testExists() {
            $this->assertTrue(self::$module->types->exists("index2"));
            $this->assertTrue(self::$module->types->exists("index"));
        }

        /**
         * Testa um tipo de index.
         * @depends testExists
         */
        public function testIndexType() {
            $type_instance = self::$module->type("index");

            $this->assertInstanceOf("Awk_Type", $type_instance);
            $this->assertSame("index", $type_instance->name);
            $this->assertSame(self::$module->path->get_normalized() . "/types/index.php", $type_instance->path->get_normalized());

            $this->assertTrue($type_instance->validate("ok"));
            $this->assertSame("ok", $type_instance->transform("ok"));
        }

        /**
         * Executa testes em um tipo exclusivo do módulo.
         */
        public function testTypeFromModule() {
            $type_instance = self::$module->type("test1b_complete");

            $this->assertInstanceOf("Awk_Type",  $type_instance);
            $this->assertSame("test1b_complete", $type_instance->name);

            $this->processTypeResponse($type_instance, true, true, "ok");
        }

        /**
         * Testa a criação de um novo tipo.
         */
        public function testCreateRuntime() {
            $type_instance = self::$module->types->create("new", "is_string", "strval");

            $this->assertInstanceOf("Awk_Type", $type_instance);
            $this->assertSame("new", $type_instance->name);
            $this->assertSame(Awk_Path::normalize(__FILE__), $type_instance->path->get_normalized());

            $this->assertTrue($type_instance->validate("ok"));
            $this->assertSame("ok", $type_instance->transform("ok"));
        }

        /**
         * Obtém um mesmo tipo, novamente, para testar o cache.
         * @depends testTypeFromModule
         */
        public function testTypeReload() {
            $this->testTypeFromModule();
        }

        /**
         * @expectedException        Awk_Type_NotExists_Exception
         * @expectedExceptionMessage O tipo "unexistent" não existe no módulo "awk_suite".
         */
        public function testUnexistentException() {
            self::$module->type("unexistent");
        }

        /**
         * @expectedException        Awk_Type_WithoutValidateCallback_Exception
         * @expectedExceptionMessage O tipo "test2_without_validate" do módulo "awk_suite" não definiu um método de validação.
         */
        public function testAwk_Type_WithoutValidateCallback_Exception() {
            self::$module->type("test2_without_validate");
        }

        /**
         * @expectedException        Awk_Type_InvalidValidateCallback_Exception
         * @expectedExceptionMessage O tipo "test3_invalid_validate" do módulo "awk_suite" definiu um método de validação inválido.
         */
        public function testAwk_Type_InvalidValidateCallback_Exception() {
            self::$module->type("test3_invalid_validate");
        }

        /**
         * @expectedException        Awk_Type_WithoutTransformCallback_Exception
         * @expectedExceptionMessage O tipo "test4_without_transform" do módulo "awk_suite" não definiu um método de transformação.
         */
        public function testAwk_Type_WithoutTransformCallback_Exception() {
            self::$module->type("test4_without_transform");
        }

        /**
         * @expectedException        Awk_Type_InvalidTransformCallback_Exception
         * @expectedExceptionMessage O tipo "test5_invalid_transform" do módulo "awk_suite" definiu um método de transformação inválido.
         */
        public function testAwk_Type_InvalidTransformCallback_Exception() {
            self::$module->type("test5_invalid_transform");
        }

        /**
         * Teste de exceção.
         */
        public function testAwk_Type_AlreadyExists_Exception() {
            $this->setExpectedException(
                "Awk_Type_AlreadyExists_Exception",
                "O tipo \"created\" do módulo \"awk_suite\" já foi definido em \"" . Awk_Path::normalize(__FILE__) . "\"."
            );

            // A primeira vez define, a segunda lançará a exceção.
            self::$module->types->create("created");
            self::$module->types->create("created");
        }
    }
