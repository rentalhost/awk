<?php

	// Define o nome da tabela, de forma indireta.
	$model->set_base("tests/suite");
	$model->set_table("test");

	// Adiciona uma nova query ao model.
	$model->add_query("load_test", "one", "SELECT 1;");
	$model->add_query("load_fail", "one", "SELECT FAIL();");
