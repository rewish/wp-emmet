<script>
!function($) {
	var editorKey = '<?php echo WP_EMMET_DOMAIN; ?>-editor',
		options = $.extend(<?php echo $this->Options->toJSON('codemirror'); ?>, {
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
		$('textarea').each(function() {
			var file = $(this).closest('form').find('input[name="file"]').val(),
				editor = CodeMirror.fromTextArea(this, $.extend({}, options, {
					mode: mimeTypes[file ? file.split('.').pop() : 'html']
				}));
			$(this).data(editorKey, editor);
		});

		if (typeof wp !== 'undefined' &&
				typeof wp.media !== 'undefined' &&
				typeof wp.media.editor !== 'undefined') {
			wp.media.editor.insert = function(h) {
				var cursor,
					editor = $('#content').data(editorKey);
				editor.doc.replaceSelection(h);
				cursor = editor.doc.getCursor();
				editor.doc.setCursor(cursor.line, cursor.ch + h.indexOf('>'));
				editor.focus();
			};
		}

		if (typeof switchEditors !== 'undefined') {
			switchEditors.switchto = function(el) {
				var params = el.id.split('-'),
					$textarea = $(tinymce.DOM.get(params[0])),
					editor = $textarea.data(editorKey),
					isHTML = params[1] === 'html';

				if (!isHTML) {
					editor.toTextArea();
					editor.disabled = true;
					$textarea.data(editorKey, editor);
				}

				if (isHTML && !editor.disabled) {
					return;
				}

				this.go(params[0], params[1]);

				if (isHTML) {
					editor = CodeMirror.fromTextArea(editor.getTextArea(), editor.options);
					editor.disabled = false;
					$textarea.data(editorKey, editor);
				}
			};
		}

		if (typeof QTags !== 'undefined') {
			QTags.TagButton.prototype.callback = function(element, canvas, ed) {
				var cursor, html,
					editor = $(canvas).data(editorKey),
					text = editor.doc.getSelection(),
					startPos = text.indexOf(this.tagStart),
					endPos = text.indexOf(this.tagEnd);

				if (startPos !== -1 && endPos !== -1) {
					html = text.substring(this.tagStart.length, endPos);
				} else {
					html = this.tagStart + text + this.tagEnd;
				}

				editor.doc.replaceSelection(html);

				if (text) {
					cursor = editor.doc.getCursor('end');
				} else {
					cursor = editor.doc.getCursor('start');
					cursor.ch += this.tagStart.length;
				}

				editor.doc.setCursor(cursor, cursor);
				editor.focus();
			};
		}

		if (typeof wpLink !== 'undefined') {
			wpLink.htmlUpdate = function() {
				var cursor,
					data = this.getAttrs(),
					editor = $(this.textarea).data(editorKey),
					tagStart = '<a',
					tagEnd = '</a>';

				$.each(data, function(name, value) {
					if (value) {
						tagStart += ' ' + name + '="' + value + '"';
					}
				});

				tagStart += '>';

				editor.replaceSelection(tagStart + editor.getSelection() + tagEnd);

				cursor = editor.doc.getCursor('start');
				editor.doc.setCursor(cursor.line, cursor.ch + tagStart.length);
				editor.focus();

				this.close();
			};
		}
	});
}(jQuery);
</script>
