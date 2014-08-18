<?php

	$asserts->expect_equal(is_bool($module->is_localhost()), true);
	$asserts->expect_equal(is_bool($module->is_development()), true);

	$asserts->expect_equal(get_class($module->libraries), "Awk_Library_Feature");

	$asserts->expect_equal(Awk_Module::exists($module->get_name()), true);

	// Identificador.
	$asserts->expect_equal(get_class($module->identify("library@awk_suite->tests/valid_unique")), "Awk_Library");
	$asserts->expect_equal(get_class($module->identify("library@tests/valid_unique")), "Awk_Library");

	$asserts->expect_equal(count($module->identify("library@tests/valid_unique::test")), 2);
	$asserts->expect_equal(count($module->identify("library@tests/valid_unique", null, null, null, true)), 4);

	// Exceções.
	$asserts->expect_exception(function() {
		Awk_Module::get("unexistent");
	}, "Awk_Error_Exception", "O módulo \"unexistent\" não existe.");

	$tmp_module = getcwd() . "/unexistent";
	mkdir($tmp_module, 0777);
	$asserts->expect_exception(function() use($tmp_module) {
		Awk_Module::get("unexistent");
	}, "Awk_Error_Exception", "O módulo \"unexistent\" não definiu o arquivo de configuração.");
	rmdir($tmp_module);

	$asserts->expect_exception(function() use($module) {
		$module->unexistent_feature();
	}, "Awk_Error_Exception", "O recurso \"unexistent_features\" não está disponível.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test", null, null, true);
	}, "Awk_Error_Exception", "Não foi possível identificar \"test\". A definição do módulo é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test");
	}, "Awk_Error_Exception", "Não foi possível identificar \"test\". A definição do recurso é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("router@test", "type", true);
	}, "Awk_Error_Exception", "Não foi possível identificar \"router@test\". O recurso deve ser \"type\".");

	$asserts->expect_exception(function() use($module) {
		$module->identify("%invalid%", "type", true);
	}, "Awk_Error_Exception", "Não foi possível identificar \"%invalid%\".");
