<script>
!function($) {
	var options = <?php echo $this->Options->toJSON('options'); ?>,
		variables = <?php echo $this->Options->toJSON('variables'); ?>;

	$('textarea').each(function() {
		CodeMirror.fromTextArea(this, {
			mode : 'text/html',
			profile: options.profile,
			theme: 'twilight',

			indentUnit: 4,
			smartIndent: options.pretty_break,
			indentWithTabs: options.use_tab,
			tabSize: 4,

			electricChars: true,
			rtlMoveVisually: true,
			keyMap: 'default',
			extraKeys: {},

			lineWrapping: true,
			lineNumbers: true
		});
	});

//	emmet.require('profile').create(name, options);


	return;

<?php /*

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

 */ ?>
 }(jQuery);
</script>
