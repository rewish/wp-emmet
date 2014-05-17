<div class="wrap" data-use-editor-type="<?php echo $this->options['use_codemirror'] ? 'codemirror' : 'textarea'; ?>">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Emmet</h2>

	<form method="post" action="options.php">
		<div class="hiddens">
			<?php wp_nonce_field('update-options'); ?>
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="page_options" value="<?php echo $this->name; ?>">
		</div>

		<h3><?php _e('Editor', $domain); ?></h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e('Code Coloring', $domain); ?></th>
					<td>
						<?php echo $form->checkBoolean('use_codemirror'); ?>
						<?php echo $form->label('use_codemirror', __('Enable', $domain)); ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Profile', $domain); ?></th>
					<td><?php echo $form->select('profile', 'xhtml,html,xml,plain,line'); ?></td>
				</tr>

				<tr data-editor-type="textarea">
					<th><?php _e('Use tab key', $domain); ?></th>
					<td>
						<?php echo $form->checkBoolean('textarea.options.use_tab'); ?>
						<?php echo $form->label('textarea.options.use_tab', __('Use', $domain)); ?>
					</td>
				</tr>
				<tr data-editor-type="textarea">
					<th><?php _e('Auto indent', $domain); ?></th>
					<td>
						<?php echo $form->checkBoolean('textarea.options.pretty_break'); ?>
						<?php echo $form->label('textarea.options.pretty_break', __('Use', $domain)); ?>
					</td>
				</tr>
				<tr data-editor-type="textarea">
					<th><?php _e('Indent character', $domain); ?></th>
					<td>
<?php				if ($this->options['textarea']['variables']['indentation'] === "\t"): ?>
							<input type="text" id="<?php echo $this->name; ?>_var_indentation_text" name="<?php echo $this->name; ?>[textarea][variables][indentation]" value="" disabled="disabled"  class="small-text">
<?php				else: ?>
							<input type="text" id="<?php echo $this->name; ?>_var_indentation_text" name="<?php echo $this->name; ?>[textarea][variables][indentation]" value="<?php echo $this->options['textarea']['variables']['indentation']; ?>" class="small-text">
<?php				endif; ?>
						<input type="checkbox" id="<?php echo $this->name; ?>_var_indentation" name="<?php echo $this->name; ?>[textarea][variables][indentation]" value="<?php echo "\t"; ?>"<?php if ($this->options['textarea']['variables']['indentation'] === "\t") echo ' checked="checked"'; ?>>
						<label for="<?php echo $this->name; ?>_var_indentation"><?php _e('Use hard tabs', $domain); ?></label>
					</td>
				</tr>

				<tr data-editor-type="codemirror">
					<th><?php _e('Theme', $domain); ?></th>
					<td><?php echo $form->select('codemirror.theme', $themes); ?></td>
				</tr>

				<tr data-editor-type="codemirror">
					<th><?php _e('Tabs and Indents', $domain); ?></th>
					<td>
						<?php echo $form->checkBoolean('codemirror.indentWithTabs'); ?>
						<?php echo $form->label('codemirror.indentWithTabs', __('Use tab character', $domain)); ?>

						<br>

						<?php echo $form->checkBoolean('codemirror.smartIndent'); ?>
						<?php echo $form->label('codemirror.smartIndent', __('Smart indent', $domain)); ?>

						<br>

						<?php echo $form->label('codemirror.tabSize', __('Tab size', $domain)); ?>
						<?php echo $form->numberField('codemirror.tabSize'); ?>

						<br>

						<?php echo $form->label('codemirror.indentUnit', __('Indent unit', $domain)); ?>
						<?php echo $form->numberField('codemirror.indentUnit'); ?>
					</td>
				</tr>

				<tr data-editor-type="codemirror">
					<th><?php _e('Appearance'); ?></th>
					<td>
						<?php echo $form->checkBoolean('codemirror.lineNumbers'); ?>
						<?php echo $form->label('codemirror.lineNumbers', __('Show line numbers', $domain)); ?>

						<br>

						<?php echo $form->checkBoolean('codemirror.lineWrapping'); ?>
						<?php echo $form->label('codemirror.lineWrapping', __('Line wrapping', $domain)); ?>
					</td>
				</tr>

				<tr data-editor-type="codemirror">
					<th><?php _e('Editor Style', $domain); ?></th>
					<td><?php echo $form->textarea('codemirror_style', array(
						'data-cm-mode' => 'css',
						'data-cm-max-width' => '600px',
						'data-cm-min-height' => '150px'
					)); ?></td>
				</tr>

				<tr>
					<th><?php _e('Scope', $domain); ?></th>
					<td>
						<fieldset>
							<label>
								<?php echo $form->checkBoolean('scope.post'); ?>
								<?php _e('Post Editor', $domain); ?>
							</label>
							<br>
							<label>
								<?php echo $form->checkBoolean('scope.theme-editor'); ?>
								<?php _e('Theme Editor', $domain); ?>
							</label>
							<br>
							<label>
								<?php echo $form->checkBoolean('scope.plugin-editor'); ?>
								<?php _e('Plugin Editor', $domain); ?>
							</label>
							<br>
							<label>
								<?php echo $form->checkBoolean('scope.others'); ?>
								<?php _e('Others', $domain); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e('Shortcuts', $domain); ?></h3>
		<p>
			<input type="hidden" name="<?php echo $this->name; ?>[override_shortcuts]" value="">
			<input type="checkbox" data-wp-emmet-toggle="shortcuts" id="<?php echo $this->name; ?>_override_shortcuts" name="<?php echo $this->name; ?>[override_shortcuts]" value="1"<?php if ($this->options['override_shortcuts']) echo ' checked="checked"'; ?>>
			<label for="<?php echo $this->name; ?>_override_shortcuts"><?php _e('Override shortcuts', $domain); ?></label>
		<p>
		<table data-wp-emmet-toggle-name="shortcuts" class="form-table <?php echo $this->name; ?>_shortcuts"<?php if (!$this->options['override_shortcuts']) echo ' style="display: none"'; ?>>
			<tbody>
<?php		foreach ($this->options['shortcuts'] as $label => $keystroke): ?>
				<tr>
					<th><?php _e($label, $domain); ?></th>
					<td><input type="text" name="<?php echo $this->name; ?>[shortcuts][<?php echo $label; ?>]" value="<?php echo $keystroke; ?>"></td>
				</tr>
<?php		endforeach; ?>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save option', $domain); ?>">
		</p>
	</form>

	<h3><?php _e('Bug reports', $domain); ?></h3>
	<p><?php printf(__('Please create an issue on %s.', $domain), '<a href="https://github.com/rewish/wp-emmet/issues" target="_blank">GitHub Issues</a>'); ?></p>

	<h3><?php _e('Test the Emmet', $domain); ?></h3>
	<div>
		<textarea rows="20" cols="80">.section>p>span+em^^^bq

ul>li*5

p*2>lorem

&lt;style&gt;
w100p
m10p30e5px
&lt;/style&gt;</textarea>
	</div>
</div>

<script>
jQuery(function($) {
	$('#<?php echo $form->id('use_codemirror'); ?>').on('click', function() {
		$('[data-use-editor-type]').attr('data-use-editor-type', this.checked ? 'codemirror' : 'textarea');
	});

	$('#<?php echo $this->name; ?>_var_indentation').click(function() {
		var $text = $('#<?php echo $this->name; ?>_var_indentation_text');
		if ($(this).attr('checked')) {
			$text.attr('disabled', 'disabled');
		} else {
			$text.removeAttr('disabled');
		}
	});

	$(document).on('click', '[data-wp-emmet-toggle]', function() {
		var $el = $(this),
			selector = '[data-wp-emmet-toggle-name="' + $el.attr('data-wp-emmet-toggle') + '"]';
		$(selector).toggle($el.prop('checked'));
	});
});
</script>