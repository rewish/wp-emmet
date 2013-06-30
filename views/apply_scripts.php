<script>
!function($) {
	var options = <?php echo $this->Options->toJSON(); ?>,
		keymap = {
			Tab: 'expand_abbreviation_with_tab',
			Enter: 'insert_formatted_line_break_only'
		},
		mimeTypes = {
			php: 'application/x-httpd-php',
			html: 'text/html',
			css: 'text/css',
			js: 'text/javascript',
			json: 'application/json'
		};

	if (options.override_shortcuts) {
		$.each(options.shortcuts, function(type, key) {
			keymap[key] = type.replace(/\s|\//g, '_').replace('.', '').toLowerCase();
		});
		window.emmetKeymap = keymap;
	}

	$(function() {
		$('textarea').each(function() {
			var file = $(this).closest('form').find('input[name="file"]').val();

			CodeMirror.fromTextArea(this, $.extend({}, options.editor, {
				mode: mimeTypes[file ? file.split('.').pop() : 'html']
			}));
		});
	});
}(jQuery);
</script>
