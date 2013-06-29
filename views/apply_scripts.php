<script>
!function($) {
	var options = <?php echo $this->Options->toJSON(); ?>;

	$('textarea').each(function() {
		CodeMirror.fromTextArea(this, $.extend({}, options.editor, {
			mode: 'text/html'
		}));
	});
 }(jQuery);
</script>
