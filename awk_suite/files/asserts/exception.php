<?php

	// Responsável por ativar todas as linhas de exceção.
	// AwkModule.php
	$asserts->expect_exception(function() {
		AwkModule::get("unexistent");
	}, "AwkErrorException", "O módulo \"unexistent\" não existe.");

	$tmp_module = getcwd() . "/unexistent";
	mkdir($tmp_module, 0777);
	$asserts->expect_exception(function() use($tmp_module) {
		AwkModule::get("unexistent");
	}, "AwkErrorException", "O módulo \"unexistent\" não definiu o arquivo de configuração.");
	rmdir($tmp_module);

	$asserts->expect_exception(function() use($module) {
		$module->unexistent_feature();
	}, "AwkErrorException", "O recurso \"unexistent_features\" não está disponível.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test", null, null, true);
	}, "AwkErrorException", "Não foi possível identificar \"test\". A definição do módulo é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("test");
	}, "AwkErrorException", "Não foi possível identificar \"test\". A definição do recurso é obrigatória.");

	$asserts->expect_exception(function() use($module) {
		$module->identify("router@test", "type", true);
	}, "AwkErrorException", "Não foi possível identificar \"router@test\". O recurso deve ser \"type\".");

	$asserts->expect_exception(function() use($module) {
		$module->identify("%invalid%", "type", true);
	}, "AwkErrorException", "Não foi possível identificar \"%invalid%\".");

	// AwkController.php
	$asserts->expect_exception(function() use($module) {
		$module->controller("unexistent");
	}, "AwkErrorException", "O módulo \"awk_suite\" não possui o controller \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/unregistered");
	}, "AwkErrorException", "O controller \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/invalid1");
	}, "AwkErrorException", "O controller \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");

	// AwkType.php
	$asserts->expect_exception(function() use($module) {
		$module->type("unexistent");
	}, "AwkErrorException", "O módulo \"awk_suite\" não possui o tipo \"unexistent\".");

	// AwkLibrary.php
	$asserts->expect_exception(function() use($module) {
		$module->library("unexistent");
	}, "AwkErrorException", "O módulo \"awk_suite\" não possui a library \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/unregistered");
	}, "AwkErrorException", "A library \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid1");
	}, "AwkErrorException", "A library \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid2")->unique();
	}, "AwkErrorException", "O método \"library_unique\" da library \"AwkSuite_Invalid2_Test\" do módulo \"awk_suite\" não retornou " .
		"uma instância da classe \"AwkSuite_Invalid2_Test\", ao invés disso, retornou \"stdClass\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid3")->unique();
	}, "AwkErrorException", "A instância única da library \"AwkSuite_Invalid3_Test\" do módulo \"awk_suite\" não pôde ser criada pois " .
		"seu construtor requer parâmetros. Considere definir o método \"library_unique\".");

	// AwkRouter.php
	$asserts->expect_exception(function() use($module) {
		$module->router("unexistent");
	}, "AwkErrorException", "O módulo \"awk_suite\" não possui o roteador \"unexistent\".");

	// AwkHelper.php
	$asserts->expect_exception(function() use($module) {
		$module->helper("unexistent");
	}, "AwkErrorException", "O módulo \"awk_suite\" não possui o helper \"unexistent\".");
