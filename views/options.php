<div class="wrap">
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
					<th><?php _e('Profile', $domain); ?></th>
					<td><?php echo $form->select('editor.profile', 'xhtml,html,xml,plain,line'); ?></td>
				</tr>
				<tr>
					<th><?php _e('Theme', $domain); ?></th>
					<td><?php echo $form->select('editor.theme', $themes); ?></td>
				</tr>
				<tr>
					<th><?php _e('Tabs and Indents', $domain); ?></th>
					<td>
						<?php echo $form->checkBoolean('editor.indentWithTabs'); ?>
						<?php echo $form->label('editor.indentWithTabs', __('Use tab character', $domain)); ?>

						<br>

						<?php echo $form->checkBoolean('editor.smartIndent'); ?>
						<?php echo $form->label('editor.smartIndent', __('Smart indent', $domain)); ?>

						<br>

						<?php echo $form->label('editor.tabSize', __('Tab size', $domain)); ?>
						<?php echo $form->numberField('editor.tabSize'); ?>

						<br>

						<?php echo $form->label('editor.indentUnit', __('Indent unit', $domain)); ?>
						<?php echo $form->numberField('editor.indentUnit'); ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Appearance'); ?></th>
					<td>
						<?php echo $form->checkBoolean('editor.lineNumbers'); ?>
						<?php echo $form->label('editor.lineNumbers', __('Show line numbers')); ?>

						<br>

						<?php echo $form->checkBoolean('editor.lineWrapping'); ?>
						<?php echo $form->label('editor.lineWrapping', __('Line wrapping')); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<h3><?php _e('Shortcuts', $domain); ?></h3>
		<p>
			<input type="hidden" name="<?php echo $this->name; ?>[override_shortcuts]" value=""><input type="checkbox" id="<?php echo $this->name; ?>_override_shortcuts" name="<?php echo $this->name; ?>[override_shortcuts]" value="1"<?php if ($this->options['override_shortcuts']) echo ' checked="checked"'; ?>>
			<label for="<?php echo $this->name; ?>_override_shortcuts"><?php _e('Override shortcuts', $domain); ?></label>
		<p>
		<table class="form-table <?php echo $this->name; ?>_shortcuts"<?php if (!$this->options['override_shortcuts']) echo ' style="display: none"'; ?>>
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

	<h3><?php _e('Test the Emmet', $domain); ?></h3>
	<div>
		<textarea rows="20" cols="80">.section>p>span+em^^^bq

ul>li*5

p*2>lorem

&lt;style&gt;
w100p
m10p30e5x
&lt;/style&gt;</textarea>
	</div>
</div>

<script>
jQuery(function($) {
	var $shortcuts = $('.<?php echo $this->name; ?>_shortcuts'),
		shortcut = emmet.require('shortcut');

	$('#<?php echo $this->name; ?>_var_indentation').click(function() {
		var $text = $('#<?php echo $this->name; ?>_var_indentation_text');
		if ($(this).attr('checked')) {
			$text.attr('disabled', 'disabled');
		} else {
			$text.removeAttr('disabled');
		}
	});

	$('#<?php echo $this->name; ?>_override_shortcuts').on('click', function() {
		if ($(this).attr('checked')) {
			$shortcuts.show();
		} else {
			$shortcuts.hide();
		}
	});
});
</script>

<?php
// Fake call for gettext
__('Expand Abbreviation');
__('Match Pair Outward');
__('Match Pair Inward');
__('Wrap with Abbreviation');
__('Next Edit Point');
__('Prev Edit Point');
__('Select Line');
__('Merge Lines');
__('Toggle Comment');
__('Split/Join Tag');
__('Remove Tag');
__('Evaluate Math Expression');
__('Increment number by 1');
__('Decrement number by 1');
__('Increment number by 0.1');
__('Decrement number by 0.1');
__('Increment number by 10');
__('Decrement number by 10');
__('Select Next Item');
__('Select Previous Item');
__('Reflect CSS Value');
?>