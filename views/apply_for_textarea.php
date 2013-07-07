<script>
jQuery(function($) {
	var config = <?php echo $this->Options->toJSON('textarea'); ?>,
		textarea = emmet.require('textarea');

	// Set variables
	emmet.require('resources').setVocabulary({variables: config.variables}, 'user');

	// Set options
	textarea.setup(config.options);
<?php if ($this->Options->get('override_shortcuts')): ?>

	// Set shortcuts
<?php foreach ($shortcuts as $label => $keystroke): ?>
	textarea.addShortcut('<?php echo $keystroke; ?>', '<?php echo str_replace(array(' ', '/', '.'), array('_', '_', ''), strtolower($label)); ?>');
<?php endforeach; ?>
<?php endif; ?>
});
</script>
