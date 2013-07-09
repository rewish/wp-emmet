!function($) {
  var editorID = 'wp-emmet-editor';

  $.fn.codeMirror = function(options) {
    return this.each(function() {
      var hidden = $(this).is(':hidden'),
          display = this.style.display,
          editor = CodeMirror.fromTextArea(this, options);

      if (hidden) {
        editor.toTextArea();
        this.style.display = display;
      }

      $.data(this, editorID, editor);
    });
  };

  $.fn.codeMirrorEditor = function(editor) {
    if (editor) {
      return $.data(this[0], editorID, editor);
    }
    return $.data(this[0], editorID);
  };

  window.wp_emmet = {
    adaptCodeMirror: function() {
      if (typeof wp !== 'undefined' &&
        typeof wp.media !== 'undefined' &&
        typeof wp.media.editor !== 'undefined') {
        this.adaptMediaEditor();
      }

      if (typeof switchEditors !== 'undefined') {
        this.adaptSwitchEditors();
      }

      if (typeof QTags !== 'undefined') {
        this.adaptQTags();
      }

      if (typeof wpLink !== 'undefined') {
        this.adaptWPLink();
      }

      if (typeof fullscreen !== 'undefined') {
        this.adaptFullScreen();
      }
    },

    adaptMediaEditor: function() {
      var originalInsert = wp.media.editor.insert;

      wp.media.editor.insert = function(h) {
        var cursor,
            editor = $('#' + wpActiveEditor).codeMirrorEditor();

        if (!editor) {
          return originalInsert.call(this, h);
        }

        editor.doc.replaceSelection(h);
        cursor = editor.doc.getCursor();
        editor.doc.setCursor(cursor.line, cursor.ch + h.indexOf('>'));
        editor.focus();
      };
    },

    adaptSwitchEditors: function() {
      switchEditors.switchto = function(el) {
        var params = el.id.split('-'),
            $wrap = $('#wp-' + params[0] + '-wrap'),
            $textarea = $(tinymce.DOM.get(params[0])),
            editor = $textarea.codeMirrorEditor(),
            toHTML = params[1] === 'html',
            fromHTML = $wrap.hasClass('html-active');

        if ((toHTML && fromHTML) || (!toHTML && !fromHTML)) {
          return;
        }

        if (!toHTML) {
          editor.toTextArea();
        }

        this.go(params[0], params[1]);

        if (toHTML) {
          editor = CodeMirror.fromTextArea(editor.getTextArea(), editor.options);
          editor.disabled = false;
          $textarea.codeMirrorEditor(editor);
        }
      };
    },

    adaptQTags: function() {
      QTags.TagButton.prototype.callback = function(element, canvas, ed) {
        var cursor, html,
            editor = $(canvas).codeMirrorEditor(),
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

    adaptWPLink: function() {
      wpLink.htmlUpdate = function() {
        var cursor,
            data = this.getAttrs(),
            editor = $(this.textarea).codeMirrorEditor(),
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
    },

    adaptFullScreen: function() {
      var $fullScreen = $('#wp_mce_fullscreen'),
          originalOff = fullscreen.off,
          originalSwitchMode = fullscreen.switchmode,
          originalSaveContent = fullscreen.savecontent;

      fullscreen.pubsub.subscribe('showing', function() {
        var editor = $('#' + wpActiveEditor).codeMirrorEditor();
        $fullScreen.codeMirror(editor.options);
      });

      fullscreen.off = function() {
        var $content = $('#' + fullscreen.settings.editor_id),
            editor = $fullScreen.codeMirrorEditor();

        originalOff.call(this);

        if ($content.is(':visible')) {
          $content.val(editor.doc.getValue());
          $content.codeMirror(editor.options);
        }

        if (fullscreen.settings.mode === 'html') {
          editor.toTextArea();
        }
      };

      fullscreen.switchmode = function(to) {
        if (fullscreen.settings.mode === to) {
          return;
        }

        var editor = $fullScreen.codeMirrorEditor(),
            mainEditor = $('#' + fullscreen.settings.editor_id).codeMirrorEditor();

        if (to === 'html') {
          originalSwitchMode.call(this, to);
          $fullScreen.codeMirror(mainEditor.options);
        } else {
          editor && editor.toTextArea();
          originalSwitchMode.call(this, to);
        }
      };

      fullscreen.savecontent = function() {
        if (fullscreen.settings.mode === 'html') {
          $fullScreen.codeMirrorEditor().save();
        }
        originalSaveContent.call(this);
      };

      QTags.FullscreenButton.prototype.callback = function(e, c) {
        if (!c.id) { return; }
        $(c).codeMirrorEditor().toTextArea();
        fullscreen.on();
      };
    }
  };
}(jQuery);