<?php

    /**
     * @covers Awk_Router
     * @covers Awk_Router_Feature
     */
    class Awk_RouterTest extends PHPUnit_Framework_TestCase {
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
         * Carrega um router.
         * @return Awk_Router
         */
        public function testRouterLoad() {
            $router_instance = self::$module->router("test1_basic");

            $this->assertInstanceOf("Awk_Router", $router_instance);
            $this->assertCount(11, $router_instance->get_routes());

            return $router_instance;
        }

        /**
         * Teste de cache.
         */
        public function testRouterReload() {
            $this->testRouterLoad();
        }

        /**
         * Testa os métodos de caminho.
         * @depends testRouterLoad
         */
        public function testFilePath($router_instance) {
            // Teste UNIX.
            $_SERVER["DOCUMENT_ROOT"] = "/home/";
            $_SERVER["REDIRECT_URL"] = "/test/";

            $this->assertSame("/home/test/", $router_instance->file_path());

            $_SERVER["DOCUMENT_ROOT"] = "/home";
            $_SERVER["REDIRECT_URL"] = "/test/";

            $this->assertSame("/home/test/", $router_instance->file_path());

            $_SERVER["DOCUMENT_ROOT"] = "/home";
            $_SERVER["REDIRECT_URL"] = null;

            $this->assertSame("/home/", $router_instance->file_path());

            // Teste Windows.
            $_SERVER["DOCUMENT_ROOT"] = "C:/home/";
            $_SERVER["REDIRECT_URL"] = "/test/";

            $this->assertSame("C:/home/test/", $router_instance->file_path());

            $_SERVER["DOCUMENT_ROOT"] = "C:/home";
            $_SERVER["REDIRECT_URL"] = "/test/";

            $this->assertSame("C:/home/test/", $router_instance->file_path());

            $_SERVER["DOCUMENT_ROOT"] = "C:/home";
            $_SERVER["REDIRECT_URL"] = null;

            $this->assertSame("C:/home/", $router_instance->file_path());
        }

        /**
         * Testa os métodos de URL via PATH_INFO.
         * @depends testRouterLoad
         */
        public function testGetUrlViaPathInfo($router_instance) {
            $_SERVER["PATH_INFO"] = "/";

            $this->assertSame("", $router_instance->get_url());

            $_SERVER["PATH_INFO"] = "/test";

            $this->assertSame("test", $router_instance->get_url());
        }

        /**
         * Testa os métodos de URL via REQUEST_URI.
         * @depends testRouterLoad
         */
        public function testGetUrlViaRequestURI($router_instance) {
            $_SERVER["SCRIPT_NAME"] = "/test/index.php";
            $_SERVER["REQUEST_URI"] = "/test/";

            $this->assertSame("", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/?skip";

            $this->assertSame("", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/test";

            $this->assertSame("test", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/test/";

            $this->assertSame("test", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/test/?skip";

            $this->assertSame("test", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/test/?skip?skip";

            $this->assertSame("test", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/long/test/path";

            $this->assertSame("long/test/path", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/two//slashes";

            $this->assertSame("two//slashes", $router_instance->get_url());

            $_SERVER["REQUEST_URI"] = "/test/two/end/slashes//";

            $this->assertSame("two/end/slashes", $router_instance->get_url());
        }

        /**
         * Testa a referencia de um arquivo.
         */
        public function testRouterFileReference() {
            // Cria um arquivo temporário para testes.
            $_SERVER["DOCUMENT_ROOT"] = getcwd();
            $_SERVER["REDIRECT_URL"] = "/publics/test1_hello.php";
            $_SERVER["REDIRECT_PUBLICS"] = true;

            // Carrega o próprio arquivo como uma rota.
            $router_instance = self::$module->router("test1_basic.file");

            $this->assertTrue(is_readable($router_instance->file_path()));
        }

        /**
         * Testa a URL base de uma rota.
         * @depends testRouterLoad
         */
        public function testRouterBaseURL($router_instance) {
            $_SERVER["SERVER_PORT"] = 80;
            $_SERVER["SERVER_NAME"] = "localhost";
            $_SERVER["SCRIPT_NAME"] = "/test/index.php";

            $this->assertSame("http://localhost/test/", $router_instance->get_baseurl());

            // Testa a versão HTTPS.
            $_SERVER["HTTPS"] = "on";

            $this->assertSame("https://localhost/test/", $router_instance->get_baseurl());
        }

        /**
         * Verifica se uma determinada rota foi definida.
         * @depends testRouterLoad
         */
        public function testRouterExists($router_instance) {
            $this->assertTrue(self::$module->routers->exists("test1_basic"));
        }

        /**
         * Executa testes no protocolo.
         */
        public function testSecureProtocol() {
            // Verificação insegura.
            $_SERVER["SERVER_PORT"] = 80;

            $this->assertFalse(Awk_Router::is_secure());

            $_SERVER["HTTPS"] = "off";

            $this->assertFalse(Awk_Router::is_secure());

            // Verificação segura.
            $_SERVER["HTTPS"] = "on";

            $this->assertTrue(Awk_Router::is_secure());

            // Verificação por porta.
            $_SERVER["HTTPS"] = null;
            $_SERVER["SERVER_PORT"] = getservbyname("https", "tcp");

            $this->assertTrue(Awk_Router::is_secure());
        }

        /**
         * Testa a rota do módulo site.
         */
        public function testSiteRouter() {
            $site_module = Awk_Module::get("site");

            $this->assertTrue($site_module->routers->exists("index"));
            $this->assertTrue($site_module->views->exists("helloWorld"));

            // Testa a rota.
            $test_driver = new Awk_Router_Driver(null, $site_module);
            $test_driver->redirect("index");

            $this->expectOutputString("Hello World!");
        }

        /**
         * Rota inexistente.
         * @expectedException           Awk_Router_NotExists_Exception
         * @expectedExceptionMessage    O Router "unexistent" não existe no módulo "awk_suite".
         */
        public function testUnexistentRouterException() {
            self::$module->router("unexistent");
        }

        /**
         * Simula a configuração de uma rota.
         */
        public function testRouterConfigure1() {
            $router = self::$module->router("test5_empty");

            // Adiciona uma nova rota.
            $router->add_root(function() { echo "root"; });

            // Testa a rota.
            $test_driver = new Awk_Router_Driver("", self::$module);
            $test_driver->redirect("test5_empty");

            $this->expectOutputString("root");
        }

        /**
         * Simula a configuração de uma rota.
         */
        public function testRouterConfigure2() {
            $router = self::$module->router("test5_empty");

            // Adiciona uma nova rota.
            $router->add_passage(function($driver) { echo "passage->"; $driver->invalidate(); });
            $router->add_route("test", function() { echo "test"; });

            // Testa a rota.
            $test_driver = new Awk_Router_Driver("test", self::$module);
            $test_driver->redirect("test5_empty");

            $this->expectOutputString("passage->test");
        }

        /**
         * Simula a configuração de uma rota de arquivo.
         * @covers Awk_Router_Route::set_file_mode
         */
        public function testRouterConfigure3() {
            // Cria um arquivo temporário para testes.
            $_SERVER["DOCUMENT_ROOT"] = getcwd();
            $_SERVER["REDIRECT_URL"] = "/publics/test1_hello.php";
            $_SERVER["REDIRECT_PUBLICS"] = true;

            $router = self::$module->router("test5b_empty");

            // Adiciona uma nova rota.
            $router->add_file_passage(function() { echo "file"; });

            // Espera-se que atinja o root, e não o roteador de arquivo.
            $test_driver = new Awk_Router_Driver("", self::$module);
            $test_driver->redirect("test5b_empty");

            $this->expectOutputString("root");
            ob_clean();

            // Espera-se que atinja o roteador do arquivo.
            $test_driver = new Awk_Router_Driver("publics/test1_hello.php", self::$module);
            $test_driver->redirect("test5b_empty");

            $this->expectOutputString("file");
        }
    }
