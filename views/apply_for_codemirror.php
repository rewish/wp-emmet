<script>
!function($) {
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

<?php if ($this->Options->get('override_shortcuts')): ?>
	window.emmetKeymap = {
<?php foreach ($shortcuts as $label => $keystroke): ?>
		'<?php echo $keystroke; ?>': '<?php echo str_replace(array(' ', '/', '.'), array('_', '_', ''), strtolower($label)); ?>',
<?php endforeach; ?>
		Tab: 'expand_abbreviation_with_tab',
		Enter: 'insert_formatted_line_break_only'
	};
<?php endif; ?>

	$(function() {
		setTimeout(function() {
			$('textarea:not(#wp_mce_fullscreen)').each(function() {
				var file = $(this).closest('form').find('input[name="file"]').val();
				$(this).codeMirror($.extend({}, options, {
					mode: mimeTypes[file ? file.split('.').pop() : 'html']
				}));
			});

			wp_emmet.adaptCodeMirror();
		}, 0);
	});
}(jQuery);
</script>
