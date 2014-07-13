<?php

	// Se estiver em um ambiente local e o módulo "awk_suite" existir, \
	// adiciona a seguinte rota.
	if($awk->is_localhost()
	&& $awk->modules->exists("awk_suite")) {
		$router->add_route("suite", function($driver) {
			$driver->redirect_module("awk_suite");
		});
	}
