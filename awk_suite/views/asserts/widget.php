<div class="assert-widget">
	<?php echo $contents; ?>

	<div class="assert-footer">
		<?php echo $footer_message; ?>
		<?php if($coverage_path): ?>
		<br />
		<a href="<?php echo $coverage_path; ?>" target="awk_coverage">Acesso ao Code Coverage.</a>
		<?php endif; ?>
	</div>
</div>