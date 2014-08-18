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

	// Tenta adicionar uma query de mesmo nome ao model indirect.
	$asserts->expect_exception(function() use($indirect_model) {
		$indirect_model->add_query("load_test", null, null);
	}, "Awk_Error_Exception", "A query \"load_test\" já foi definida no model \"tests/suite/test\" do módulo \"awk_suite\".");

	// Tenta executar uma query não definida anteriormente.
	$asserts->expect_exception(function() use($indirect_model) {
		$indirect_model->load_unknow();
	}, "Awk_Error_Exception", "A query \"load_unknow\" não foi definida no model \"tests/suite/test\" do módulo \"awk_suite\".");

	// Exceções.
	$asserts->expect_exception(function() use($module) {
		$module->model("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o model \"unexistent\".");
