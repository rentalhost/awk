<?php

	// Responsável por ativar todas as linhas de exceção.
	// Awk_Module.php
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

	// Awk_Controller.php
	$asserts->expect_exception(function() use($module) {
		$module->controller("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o controller \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/unregistered");
	}, "Awk_Error_Exception", "O controller \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->controller("tests/invalid1");
	}, "Awk_Error_Exception", "O controller \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");

	// Awk_Type.php
	$asserts->expect_exception(function() use($module) {
		$module->type("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o tipo \"unexistent\".");

	// Awk_Library.php
	$asserts->expect_exception(function() use($module) {
		$module->library("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui a library \"unexistent\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/unregistered");
	}, "Awk_Error_Exception", "A library \"tests/unregistered\" do módulo \"awk_suite\" não efetuou o registro de classe.");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid1");
	}, "Awk_Error_Exception", "A library \"tests/invalid1\" do módulo \"awk_suite\" registrou uma classe inexistente (\"Unexistent_Class\").");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid2")->unique();
	}, "Awk_Error_Exception", "O método \"library_unique\" da library \"AwkSuite_Invalid2_Test\" do módulo \"awk_suite\" não retornou " .
		"uma instância da classe \"AwkSuite_Invalid2_Test\", ao invés disso, retornou \"stdClass\".");

	$asserts->expect_exception(function() use($module) {
		$module->library("tests/invalid3")->unique();
	}, "Awk_Error_Exception", "A instância única da library \"AwkSuite_Invalid3_Test\" do módulo \"awk_suite\" não pôde ser criada pois " .
		"seu construtor requer parâmetros. Considere definir o método \"library_unique\".");

	// Awk_Router.php
	$asserts->expect_exception(function() use($module) {
		$module->router("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o roteador \"unexistent\".");

	// Awk_Helper.php
	$asserts->expect_exception(function() use($module) {
		$module->helper("unexistent");
	}, "Awk_Error_Exception", "O módulo \"awk_suite\" não possui o helper \"unexistent\".");
