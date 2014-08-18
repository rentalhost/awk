<?php

	// Carrega um modelo indireto (com base).
	$indirect_model = $module->model("tests/suite/test");

	// Executa uma query simples, e verifica seu resultado.
	$result_row = $indirect_model->load_test();
	$asserts->expect_equal($result_row->get_array(), [ "1" => "1" ]);

	// Controla os dados de forma mágica.
	$asserts->expect_equal($result_row->__get("1"), "1");
	$asserts->expect_equal($result_row->__isset("1"), true);

	$result_row->__unset("1");
	$asserts->expect_equal($result_row->__isset("1"), false);

	$result_row->test = "test";
	$asserts->expect_equal($result_row->test, "test");
	$asserts->expect_equal(isset($result_row->test), true);

	// Exceções.
	$asserts->expect_exception(function() use($indirect_model) {
		$indirect_model->add_query("unsupported_type", "unsupported_type", null);
	}, "Awk_Error_Exception", "Atualmente, não há suporte para a query do tipo \"unsupported_type\" em um model.");

	$asserts->expect_exception(function() use($indirect_model) {
		$indirect_model->load_fail();
	}, "Awk_Error_Exception", "Falha ao executar a query.");
