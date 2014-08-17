<?php

	// Carrega um modelo base.
	$suite_model = $module->model("tests/suite");

	// Verifica os dados do modelo.
	$asserts->expect_equal($suite_model->get_table(), null);
	$asserts->expect_equal($suite_model->get_prefix(), "suite_");

	// Carrega um modelo direto (sem base).
	$direct_model = $module->model("tests/suite_test");

	// Verifica os dados do modelo.
	$asserts->expect_equal($direct_model->get_table(), "suite_test");
	$asserts->expect_equal($direct_model->get_prefix(), null);

	// Carrega um modelo indireto (com base).
	$indirect_model = $module->model("tests/suite/test");

	// Verifica os dados do modelo.
	$asserts->expect_equal($indirect_model->get_table(), "suite_test");
	$asserts->expect_equal($indirect_model->get_prefix(), "suite_");
