var wp_emmet = (function($) {
  'use strict';

  var editorID = 'wp-emmet-editor';

  /**
   * Apply the CodeMirror
   *
   * @param options
   * @returns {*}
   */
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

  /**
   * Get an editor of the CodeMirror
   *
   * @param editor
   * @returns {*}
   */
  $.fn.codeMirrorEditor = function(editor) {
    if (editor) {
      return this.data(editorID, editor);
    }
    return this.data(editorID);
  };

  /**
   * Apply emmet
   *
   * @param options
   * @param mimeTypes
   * @returns {*}
   */
  $.fn.emmet = function(options, mimeTypes) {
    options = options || {};
    mimeTypes = mimeTypes || {};

    return this.each(function() {
      var maxWidth, minHeight,
          $textarea = $(this),
          file = $textarea.closest('form').find('input[name="file"]').val(),
          mode = $textarea.attr('data-cm-mode');

      if (!mode) {
        mode = mimeTypes[file ? file.split('.').pop() : 'html'];
      }

      $textarea.codeMirror($.extend({}, options, {mode: mode}));

      if (maxWidth = $textarea.attr('data-cm-max-width')) {
        $($textarea.codeMirrorEditor().display.wrapper).css({maxWidth: maxWidth});
      }

      if (minHeight = $textarea.attr('data-cm-min-height')) {
        $($textarea.codeMirrorEditor().display.scroller).css({minHeight: minHeight});
      }
    });
  };

  function adjust() {
    var editor = $('#content').codeMirrorEditor(),
        $top = wp_emmet.$top || $('#ed_toolbar');

    if (editor) {
      $(editor.display.wrapper).css('marginTop', $top.innerHeight()|0)
    }
  }

  var scrollTimerID;

  $(window).on('scroll resize', function() {
    adjust();
    clearTimeout(scrollTimerID);
    scrollTimerID = setTimeout(adjust, 100);
  });

  $(document).on('wp-collapse-menu postboxes-columnchange editor-classchange postbox-toggled', adjust);

  return {
    adjust: adjust
  };
}(jQuery));