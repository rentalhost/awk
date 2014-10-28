<?php

    /**
     * @covers Awk_Path
     */
    class Awk_PathTest extends PHPUnit_Framework_TestCase {
        /**
         * Testa os métodos da instância em um diretório real.
         */
        public function testDirectoryInstance() {
            $path = new Awk_Path(getcwd());

            $this->assertTrue($path->exists());
            $this->assertTrue($path->is_dir());
            $this->assertFalse($path->is_file());
            $this->assertTrue($path->is_readable());

            $this->assertSame(getcwd(), $path->get());
        }

        /**
         * Testa os métodos da instância em um arquivo real.
         */
        public function testFileInstance() {
            $path = new Awk_Path(getcwd() . "/../index.php");

            $this->assertTrue($path->exists());
            $this->assertTrue($path->is_file());
            $this->assertFalse($path->is_dir());
            $this->assertTrue($path->is_readable());

            $this->assertSame("index.php", $path->get_basename());
            $this->assertSame("php", $path->get_extension());
        }

        /**
         * Testa os métodos em uma instância artificial.
         */
        public function testArtificialInstance() {
            $path = new Awk_Path("/fake/dir");

            $this->assertFalse($path->exists());
            $this->assertFalse($path->is_file());
            $this->assertFalse($path->is_dir());
            $this->assertFalse($path->is_readable());

            $this->assertSame("dir", $path->get_basename());
            $this->assertSame("/fake", $path->get_dirname());

            $this->assertSame("/fake/dir", $path->get_normalized());

            $this->assertNull($path->get_extension());
        }

        /**
         * Teste de normalização em arquivos artificiais.
         * @dataProvider providerArtificialNormalizations
         */
        public function testArtificialNormalizations($path, $path_normalized) {
            $path = new Awk_Path($path);
            $this->assertSame($path_normalized, $path->get_normalized());
        }

        /**
         * Provedor de paths normalizados.
         */
        public function providerArtificialNormalizations() {
            return [
                [ "abc/..",             "" ],
                [ "abc/../",            "" ],
                [ "abc/../abc",         "abc" ],
                [ "./abc/../abc",       "abc" ],
                [ "/abc/../",           "/" ],
                [ "/abc/..",            "/" ],
                [ "/abc/../abc",        "/abc" ],
                [ "/./abc/../abc",      "/abc" ],
                [ "../abc",             "../abc" ],
                [ "../abc/../abc",      "../abc" ],
                [ "/../abc",            "/../abc" ],
                [ "/../abc/../abc",     "/../abc" ],
                [ "../abc/../../abc",   "../../abc" ],
                [ "/../abc/../../abc",  "/../../abc" ],
            ];
        }

        /**
         * Teste de normalização em caminhos reais.
         */
        public function testRealNormalizations() {
            $path = new Awk_Path(getcwd());
            $this->assertSame(str_replace("\\", "/", getcwd()), $path->get_normalized());

            $path = new Awk_Path(str_replace(DIRECTORY_SEPARATOR, "//", getcwd()));
            $this->assertSame(str_replace("\\", "/", getcwd()), $path->get_normalized());
        }
    }
