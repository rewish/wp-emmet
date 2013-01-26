<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Emmet</h2>

	<form method="post" action="options.php">
		<div class="hiddens">
			<?php wp_nonce_field('update-options'); ?>
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="page_options" value="<?php echo $this->name; ?>">
		</div>

		<h3><?php _e('Options', $domain); ?></h3>
		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e('Profile', $domain); ?></th>
					<td>
						<select name="<?php echo $this->name; ?>[options][profile]">
<?php					foreach (array('xhtml', 'html', 'xml', 'plain', 'line') as $profile): ?>
							<option<?php if ($this->options['options']['profile'] === $profile) echo ' selected="selected"'; ?>><?php echo $profile; ?></option>
<?php					endforeach; ?>
						</select>
				</tr>
				<tr>
					<th><?php _e('Use tab key', $domain); ?></th>
					<td>
						<input type="hidden" name="<?php echo $this->name; ?>[options][use_tab]" value="0">
						<input id="<?php echo $this->name; ?>_op_use_tab" type="checkbox" name="<?php echo $this->name; ?>[options][use_tab]" value="1"<?php if ($this->options['options']['use_tab']) echo ' checked="checked"'; ?>>
						<label for="<?php echo $this->name; ?>_op_use_tab"><?php _e('Use', $domain); ?></label>
					</td>
				</tr>
				<tr>
					<th><?php _e('Auto indent', $domain); ?></th>
					<td>
						<input type="hidden" name="<?php echo $this->name; ?>[options][pretty_break]" value="0">
						<input id="<?php echo $this->name; ?>_op_pretty_break" type="checkbox" name="<?php echo $this->name; ?>[options][pretty_break]" value="1"<?php if ($this->options['options']['pretty_break']) echo ' checked="checked"'; ?>>
						<label for="<?php echo $this->name; ?>_op_pretty_break"><?php _e('Use', $domain); ?></label>
					</td>
				</tr>
				<tr>
					<th><?php _e('Indent character', $domain); ?></th>
					<td>
<?php				if ($this->options['variables']['indentation'] === "\t"): ?>
						<input type="text" id="<?php echo $this->name; ?>_var_indentation_text" name="<?php echo $this->name; ?>[variables][indentation]" value="" disabled="disabled"  class="small-text">
<?php				else: ?>
						<input type="text" id="<?php echo $this->name; ?>_var_indentation_text" name="<?php echo $this->name; ?>[variables][indentation]" value="<?php echo $this->options['variables']['indentation']; ?>" class="small-text">
<?php				endif; ?>
						<input type="checkbox" id="<?php echo $this->name; ?>_var_indentation" name="<?php echo $this->name; ?>[variables][indentation]" value="<?php echo "\t"; ?>"<?php if ($this->options['variables']['indentation'] === "\t") echo ' checked="checked"'; ?>>
						<label for="<?php echo $this->name; ?>_var_indentation"><?php _e('Use hard tabs', $domain); ?></label>
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

	$shortcuts.find('input[type="text"]').each(function() {
		var $self = $(this),
			$kbd = $(document.createElement('kbd'));
		$kbd.text(shortcut.format($self.val()));
		$self.after($kbd);
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