!function($) {
  'use strict';

  // Switch editor
  if (window.switchEditors && switchEditors.switchto) {
    switchEditors.switchto = function (el) {
      var params = el.id.split('-'),
          $wrap = $('#wp-' + params[0] + '-wrap'),
          $textarea = $(tinymce.DOM.get(params[0])),
          editor = $textarea.codeMirrorEditor(),
          toHTML = params[1] === 'html',
          fromHTML = $wrap.hasClass('html-active');

      if ((toHTML && fromHTML) || (!toHTML && !fromHTML)) {
        return;
      }

      if (!toHTML && editor) {
        editor.toTextArea();
      }

      this.go(params[0], params[1]);

      if (toHTML) {
        editor = CodeMirror.fromTextArea(editor.getTextArea(), editor.options);
        editor.disabled = false;
        $textarea.codeMirrorEditor(editor);
        wp_emmet.adjust();
      }
    };
  }

  // Tag button
  if (window.QTags && QTags.TagButton) {
    QTags.TagButton.prototype.callback = function (element, canvas, ed) {
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
  }

  // Link tag button
  if (window.wpLink) {
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
  }

  // Media
  if (wp.media && wp.media.editor) {
    wp.media.editor.insert_without_wp_emmet = wp.media.editor.insert;
    wp.media.editor.insert = function(h) {
      var cursor,
          editor = $('#' + wpActiveEditor).codeMirrorEditor();

      if (!editor) {
        return this.insert_without_wp_emmet(h);
      }

      editor.doc.replaceSelection(h);
      cursor = editor.doc.getCursor();
      editor.doc.setCursor(cursor.line, cursor.ch + h.indexOf('>'));
      editor.focus();
    };
  }

  //  Fullscreen
  if (wp.editor && wp.editor.fullscreen) {
    wp.editor.fullscreen.switchmode_without_wp_emmet = wp.editor.fullscreen.switchmode;
    wp.editor.fullscreen.switchmode = function(to) {
      switchEditors.switchto({id: 'content-' + to});
      this.settings.mode = to;
      this.refreshButtons(true);
    };

    wp.editor.fullscreen.save_without_wp_emmet = wp.editor.fullscreen.save;
    wp.editor.fullscreen.save = function() {
      if (this.settings.mode === 'html') {
        $('#content').codeMirrorEditor().save();
      }
      this.save_without_wp_emmet();
    };

    wp.editor.fullscreen.pubsub.subscribe('showing', function() {
      wp_emmet.$top = $([]);
      wp_emmet.adjust();
    });

    wp.editor.fullscreen.pubsub.subscribe('hiding', function() {
      wp_emmet.$top = null;
      wp_emmet.adjust();
    });
  }
}(jQuery);
