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
		}),
		mimeTypes = {
			php: 'application/x-httpd-php',
			html: 'text/html',
			css: 'text/css',
			js: 'text/javascript',
			json: 'application/json'
		};

	setTimeout(function() {
		$('textarea:not(#wp_mce_fullscreen)').each(function() {
			var $textarea = $(this),
				file = $textarea.closest('form').find('input[name="file"]').val(),
				mode = $textarea.attr('data-cm-mode'),
				maxWidth = $textarea.attr('data-cm-max-width'),
				minHeight = $textarea.attr('data-cm-min-height');

			$textarea.codeMirror($.extend({}, options, {
				mode: mode || mimeTypes[file ? file.split('.').pop() : 'html']
			}));

			if (maxWidth) {
				$($textarea.codeMirrorEditor().display.wrapper).css({maxWidth: maxWidth});
			}

			if (minHeight) {
				$($textarea.codeMirrorEditor().display.scroller).css({minHeight: minHeight});
			}
		});

		wp_emmet.adaptCodeMirror();
	}, 0);
});
</script>
