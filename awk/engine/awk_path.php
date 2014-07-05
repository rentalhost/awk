<?php

	// Responsável pela resolução e controle de paths.
	class awk_path {
		// Armazena os paths já normalizados.
		// @type array<string, string>;
		static private $normalized_cache = [
		];

		/** HELPERS */
		// Normaliza um caminho absoluto quando o `php:realpath()` não for capaz.
		// Nota: este método não resolverá [dot].
		static private function normalize_unreal($path) {
			$path_parts = preg_split("/[\\\\\\/]+/", $path);

			// Resolve [dot][dot] do array.
			$path_filters = array_keys($path_parts, "..");
			foreach($path_filters as $k => $path_filter) {
				array_splice($path_parts, $path_filter - ( $k * 2 + 1 ), 2);
			}

			return self::$normalized_cache[$path] = str_replace("./", null, rtrim(join("/", $path_parts), "./"));
		}

		// Normaliza um caminho de arquivo ou pasta.
		static public function normalize($path) {
			// Se o caminho já foi normalizado, retorna.
			if(isset(self::$normalized_cache[$path])) {
				return self::$normalized_cache[$path];
			}

			// Se o caminho real existir, executará o processo através de um `php:preg_replace()`.
			// Caso contrário, será necessário a utilização do método de normalização de *irreais*.
			$realpath = realpath($path);
			if($realpath) {
				return self::$normalized_cache[$path] = preg_replace("/[\\\\\\/]+/", "/", $realpath);
			}

			return self::normalize_unreal($path);
		}
	}
