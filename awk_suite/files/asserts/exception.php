<?php

	// Responsável por ativar todas as linhas de exceção.
	// awk_module.php
	$asserts->expect_exception(function() {
		awk_module::get("unexistent");
	}, "awk_error_exception", "O módulo \"unexistent\" não existe.");

	$tmp_module = getcwd() . "/unexistent";
	mkdir($tmp_module, 0777);
	$asserts->expect_exception(function() use($tmp_module) {
		awk_module::get("unexistent");
	}, "awk_error_exception", "O módulo \"unexistent\" não definiu o arquivo de configuração.");
	rmdir($tmp_module);

	$asserts->expect_exception(function() use($module) {
		$module->unexistent_feature();
	}, "awk_error_exception", "O recurso \"unexistent_features\" não está disponível.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test", null, null, true);
	}, "awk_error_exception", "Não foi possível identificar \"test\". A definição do módulo é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test");
	}, "awk_error_exception", "Não foi possível identificar \"test\". A definição do recurso é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("router@test", "type", true);
	}, "awk_error_exception", "Não foi possível identificar \"router@test\". O recurso deve ser \"type\".");

	$asserts->expect_exception(function() use($module) {
		$module->identify("%invalid%", "type", true);
	}, "awk_error_exception", "Não foi possível identificar \"%invalid%\".");

	// awk_controller.php
	$asserts->expect_exception(function() use($module) {
		$module->controller("unexistent");
	}, "awk_error_exception", "O módulo \"awk_suite\" não possui o controller \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/unregistered");
	}, "awk_error_exception", "O controller \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/invalid1");
	}, "awk_error_exception", "O controller \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"unexistent_class\").");

	// awk_type.php
	$asserts->expect_exception(function() use($module) {
		$module->type("unexistent");
	}, "awk_error_exception", "O módulo \"awk_suite\" não possui o tipo \"unexistent\".");

	// awk_library.php
	$asserts->expect_exception(function() use($module) {
		$module->library("unexistent");
	}, "awk_error_exception", "O módulo \"awk_suite\" não possui a library \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/unregistered");
	}, "awk_error_exception", "A library \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid1");
	}, "awk_error_exception", "A library \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"unexistent_class\").");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid2")->unique();
	}, "awk_error_exception", "O método \"library_unique\" da library \"awk_suite_invalid2_test\" do módulo \"awk_suite\" não retornou " .
		"uma instância da classe \"awk_suite_invalid2_test\", ao invés disso, retornou \"stdClass\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid3")->unique();
	}, "awk_error_exception", "A instância única da library \"awk_suite_invalid3_test\" do módulo \"awk_suite\" não pôde ser criada pois " .
		"seu construtor requer parâmetros. Considere definir o método \"library_unique\".");

	// awk_router.php
	$asserts->expect_exception(function() use($module) {
		$module->router("unexistent");
	}, "awk_error_exception", "O módulo \"awk_suite\" não possui o roteador \"unexistent\".");

	// awk_helper.php
	$asserts->expect_exception(function() use($module) {
		$module->helper("unexistent");
	}, "awk_error_exception", "O módulo \"awk_suite\" não possui o helper \"unexistent\".");
