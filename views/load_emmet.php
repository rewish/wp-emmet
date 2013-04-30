<?php if ($this->isLoadUnderscore()): ?>
<script src="<?php echo $this->getJavaScriptURL('underscore.js'); ?>"></script>
<?php endif; ?>
<script src="<?php echo $this->getJavaScriptURL('emmet.js'); ?>"></script>
<script>
(function(emmet) {
	var textarea = emmet.require('textarea');

	// Set variables
	emmet.require('resources').setVocabulary({
		variables: <?php echo $this->Options->toJSON('variables') . PHP_EOL; ?>
	}, 'user');

	// Set options
	textarea.setup(<?php echo $this->Options->toJSON('options'); ?>);
<?php if ($this->Options->get('override_shortcuts')): ?>

	// Set shortcuts
<?php foreach ($shortcuts as $label => $keystroke): ?>
	textarea.addShortcut('<?php echo $keystroke; ?>', '<?php echo str_replace(array(' ', '/', '.'), array('_', '_', ''), strtolower($label)); ?>');
<?php endforeach; ?>
<?php endif; ?>
}(emmet));
</script>