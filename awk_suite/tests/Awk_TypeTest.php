<?php

    /**
     * @covers Awk_Type
     * @covers Awk_Type_Feature
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
            self::$awk    = Awk_Module::get("awk");
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

                // Teste int.
                400 =>  [ "int",     true,   false,  1 ],
                        [ "int",     false,  false,  0 ],
                        [ "int",     "on",   false,  0 ],
                        [ "int",     "yes",  false,  0 ],
                        [ "int",     "1",    true,   1 ],
                        [ "int",     "0",    true,   0 ],
                        [ "int",     "",     false,  0 ],
                        [ "int",     "-1",   true,  -1 ],
                        [ "int",     " ",    false,  0 ],
                        [ "int",     1,      true,   1 ],
                        [ "int",     1.5,    false,  1 ],
                        [ "int",     0,      true,   0 ],
                        [ "int",     -1,     true,  -1 ],
                        [ "int",     null,   false,  0 ],
                        [ "int",     [],     false,  0 ],
                        [ "int",     [true], false,  0 ],

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
         * Executa testes em um tipo exclusivo do módulo.
         */
        public function testTypeFromModule() {
            $type_instance = self::$module->type("test1_complete");

            $this->assertInstanceOf("Awk_Type", $type_instance);
            $this->processTypeResponse($type_instance, true, true, "ok");
        }

        /**
         * Obtém um mesmo tipo, novamente, para testar o cache.
         * @depends testTypeFromModule
         */
        public function testTypeReload() {
            $this->testTypeFromModule();
        }

        /**
         * Uma exceção deve ser lançada quando um teste não existe no módulo.
         * @expectedException        Awk_Type_NotExists_Exception
         * @expectedExceptionMessage O Type "unexistent" não existe no módulo "awk_suite".
         */
        public function testUnexistentException() {
            self::$module->type("unexistent");
        }

        /**
         * Caso não tenha definido um método de validação.
         * @expectedException        Awk_Type_WithoutValidateCallback_Exception
         * @expectedExceptionMessage O Type "test2_without_validate" do módulo "awk_suite" não definiu um método de validação.
         */
        public function testAwk_Type_WithoutValidateCallback_Exception() {
            self::$module->type("test2_without_validate");
        }

        /**
         * Caso tenha definido um método de validação inválido.
         * @expectedException        Awk_Type_InvalidValidateCallback_Exception
         * @expectedExceptionMessage O Type "test3_invalid_validate" do módulo "awk_suite" definiu um método de validação inválido.
         */
        public function testAwk_Type_InvalidValidateCallback_Exception() {
            self::$module->type("test3_invalid_validate");
        }

        /**
         * Caso não tenha definido um método de transformação.
         * @expectedException        Awk_Type_WithoutTransformCallback_Exception
         * @expectedExceptionMessage O Type "test4_without_transform" do módulo "awk_suite" não definiu um método de transformação.
         */
        public function testAwk_Type_WithoutTransformCallback_Exception() {
            self::$module->type("test4_without_transform");
        }

        /**
         * Caso tenha definido um método de transformação inválido.
         * @expectedException        Awk_Type_InvalidTransformCallback_Exception
         * @expectedExceptionMessage O Type "test5_invalid_transform" do módulo "awk_suite" definiu um método de transformação inválido.
         */
        public function testAwk_Type_InvalidTransformCallback_Exception() {
            self::$module->type("test5_invalid_transform");
        }
    }
