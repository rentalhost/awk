<?php

	// Carrega as configurações do motor.
	$awk_settings = $awk->settings();
	$framework_version = join(".", $awk_settings->framework_version);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Awk Suite :: <?php echo $framework_version; ?></title>
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css" />
		<link href="<?php echo $module->public("app/home.css")->get_url(); ?>" rel="stylesheet" type="text/css" />
		<script src="<?php echo $module->public("vendor/jquery.js")->get_url(); ?>"></script>
	</head>
	<body>
		<div class="base-widget">
			<div class="base-page">
				<div class="header-widget">
					<h1>Suite de Testes</h1>
				</div>
				<div class="controller-widget">
					<a href="run">Executar</a>
					<span class="long-separator"></span>
					<span class="text"><?php echo "{$awk_settings->framework_name} {$framework_version}"; ?></span>
				</div>
				<div class="content-widget">
				</div>
			</div>
		</div>
	</body>
</html>