!function($) {
  var wp_emmet = window.wp_emmet = {
    editorKey: 'wp-emmet-editor',

    extendForCodeMirror: function() {
      if (typeof wp !== 'undefined' &&
        typeof wp.media !== 'undefined' &&
        typeof wp.media.editor !== 'undefined') {
        this.extendMediaEditor();
      }

      if (typeof switchEditors !== 'undefined') {
        this.extendSwitchEditors();
      }

      if (typeof QTags !== 'undefined') {
        this.extendQTags();
      }

      if (typeof wpLink !== 'undefined') {
        this.extendWPLink();
      }
    },

    extendMediaEditor: function() {
      var editorKey = this.editorKey;

      wp.media.editor.insert = function(h) {
        var cursor,
            editor = $('#content').data(editorKey);
        editor.doc.replaceSelection(h);
        cursor = editor.doc.getCursor();
        editor.doc.setCursor(cursor.line, cursor.ch + h.indexOf('>'));
        editor.focus();
      };
    },

    extendSwitchEditors: function() {
      var editorKey = this.editorKey;

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
    },

    extendQTags: function() {
      var editorKey = this.editorKey;

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
    },

    extendWPLink: function() {
      var editorKey = this.editorKey;

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
  };
}(jQuery);