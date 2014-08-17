<?php

	// Verifica se a sessão está vazia.
	$asserts->expect_equal($module->session(), []);

	// Define uma sessão.
	$module->session("number", 123);
	$asserts->expect_equal($module->session("number"), 123);

	$module->session("text", "hello");
	$asserts->expect_equal($module->session(), [ "number" => 123, "text" => "hello" ]);

	$module->session("number", 456);
	$asserts->expect_equal($module->session(), [ "number" => 456, "text" => "hello" ]);

	// Verifica se uma sessão existe.
	$asserts->expect_equal(isset($module->sessions->text), true);
	$asserts->expect_equal(isset($module->sessions->unknow), false);

	// Apaga uma sessão e verifica se existe.
	unset($module->sessions->text);
	$asserts->expect_equal(isset($module->sessions->text), false);

	// Verifica uma sessão em outro módulo.
	$site = Awk_Module::get("site");
	$site->session("awk_hello", "world");

	$asserts->expect_equal(isset($module->sessions->awk_hello), false);
	$asserts->expect_equal(isset($site->sessions->awk_hello), true);
	$asserts->expect_equal($module->session(), [ "number" => 456 ]);

	unset($site->sessions->awk_hello);

	// Limpa a sessão e verifica.
	$module->sessions->clear();
	$asserts->expect_equal($module->session(), []);

	// Elimina o bloco de sessão.
	unset($_SESSION[getcwd() . DIRECTORY_SEPARATOR . $module->get_name()]);
