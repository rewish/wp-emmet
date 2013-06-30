<script>
!function($) {
	var options = <?php echo $this->Options->toJSON(); ?>,
		keymap = {
			Tab: 'expand_abbreviation_with_tab',
			Enter: 'insert_formatted_line_break_only'
		};

	if (options.override_shortcuts) {
		$.each(options.shortcuts, function(type, key) {
			keymap[key] = type.replace(/\s|\//g, '_').replace('.', '').toLowerCase();
		});
		window.emmetKeymap = keymap;
	}

	$(function() {
		$('textarea').each(function() {
			CodeMirror.fromTextArea(this, $.extend({}, options.editor, {
				mode: 'text/html'
			}));
		});
	});
}(jQuery);
</script>
