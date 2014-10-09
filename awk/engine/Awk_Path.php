<?php

	/**
	 * Responsável pela resolução e controle de paths.
	 */
	class Awk_Path {
		/**
		 * Normaliza um caminho absoluto quando o `php:realpath()` não for capaz.
		 * @param  string $path Caminho a ser normalizado.
		 * @return string
		 */
		static private function normalize_unreal($path) {
			$original_parts = preg_split("/[\\\\\\/]+/", $path);

			// Dados do resultado.
			$result_parts = [];
			$result_offset = 0;

			// Valida cada parte do caminho.
			foreach($original_parts as $original_offset => $original_part) {
				// Se for [dot], basta ignorar.
				if($original_part === ".") {
					continue;
				}
				else
				// Se for [dot][dot] e houver offset válido, então remove e avança.
				if($original_part === ".."
				&& $result_offset > 0) {
					array_pop($result_parts);
					$result_offset--;
					continue;
				}

				// Em último caso, adiciona a parte.
				$result_parts[] = $original_part;

				// Avança o offset válido, apenas quando não for [dot][dot].
				$result_offset+= $original_part !== "..";
			}

			return rtrim(join("/", $result_parts), "/");
		}

		/**
		 * Normaliza um caminho de arquivo ou pasta.
		 * @param  string $path Caminho a ser normalizado.
		 * @return string
		 */
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
