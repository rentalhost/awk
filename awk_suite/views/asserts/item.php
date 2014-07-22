<div class="assert-item <?php echo $status; ?>">
	<div class="assert-item-line"><?php echo $line; ?></div>
	<div class="assert-item-title"><?php echo $title; ?></div>
	<?php if(!empty($description)): ?>
	<div class="assert-item-info"><?php echo $description; ?></div>
	<?php endif; ?>
	<?php if(!empty($fail_message)): ?>
	<div class="assert-item-fail-message"><?php echo $fail_message; ?></div>
	<?php endif; ?>
</div>