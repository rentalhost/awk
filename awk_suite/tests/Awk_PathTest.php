<?php

	/**
	 * @covers Awk_Path
	 */
	class Awk_PathTest extends PHPUnit_Framework_TestCase {
		/**
		 * Testa com caminhos não reconhecidos.
		 * @return void
		 */
		public function testNotRealPath() {
			// Normalização de caminhos inexistentes.
			$this->assertSame("abc", Awk_Path::normalize("abc/../abc"));
			$this->assertSame("abc", Awk_Path::normalize("abc/../abc/"));
			$this->assertSame("", Awk_Path::normalize("abc/.."));
			$this->assertSame("", Awk_Path::normalize("abc/../"));
			$this->assertSame("abc", Awk_Path::normalize("./abc/../abc"));
		}

		/**
		 * Teste com caminhos reais.
		 * @return void
		 */
		public function testRealPath() {
			$this->assertSame(str_replace("\\", "/", getcwd()), Awk_Path::normalize(getcwd()));
			$this->assertSame(str_replace("\\", "/", getcwd()), Awk_Path::normalize(str_replace(DIRECTORY_SEPARATOR, "//", getcwd())));
		}

		/**
		 * Testa caminhos complexos, não reconhecidos.
		 * @return void
		 */
		public function testComplexPath() {
			$this->assertSame("../abc", Awk_Path::normalize("../abc"));
			$this->assertSame("../abc", Awk_Path::normalize("../abc/../abc"));
			$this->assertSame("../../abc", Awk_Path::normalize("../abc/../../abc"));
		}
	}
