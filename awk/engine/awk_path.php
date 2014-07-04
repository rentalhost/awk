<?php

	// Responsável pela resolução e controle de paths.
	class awk_path {
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

			return str_replace("./", null, rtrim(join("/", $path_parts), "./"));
		}

		// Normaliza um caminho de arquivo ou pasta.
		static public function normalize($path) {
			// Se o caminho real existir, executará o processo através de um `php:preg_replace()`.
			// Caso contrário, será necessário a utilização do método de normalização de *irreais*.
			$realpath = realpath($path);
			if($realpath) {
				return preg_replace("/[\\\\\\/]+/", "/", $realpath);
			}

			return self::normalize_unreal($path);
		}
	}
