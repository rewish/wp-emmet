<style>
.CodeMirror {
<?php echo $this->Options->get('codemirror_style') . PHP_EOL; ?>
}
</style>

<script>
<?php if ($this->Options->get('override_shortcuts')): ?>
var emmetKeymap = {
<?php foreach ($shortcuts as $label => $keystroke): ?>
	'<?php echo $keystroke; ?>': '<?php echo str_replace(array(' ', '/', '.'), array('_', '_', ''), strtolower($label)); ?>',
<?php endforeach; ?>
	Tab: 'expand_abbreviation_with_tab',
	Enter: 'insert_formatted_line_break_only'
};
<?php endif; ?>

jQuery(function($) {
	var options = $.extend(<?php echo $this->Options->toJSON('codemirror'); ?>, {
		profile: '<?php echo $this->Options->get('profile'); ?>'
	});

	var mimeTypes = {
		php: 'application/x-httpd-php',
		html: 'text/html',
		css: 'text/css',
		js: 'text/javascript',
		json: 'application/json'
	};

	setTimeout(function() {
		$('textarea:not(#content-textarea-clone)').emmet(options, mimeTypes);
		wp_emmet.adjust();
	}, 1);
});
</script>
