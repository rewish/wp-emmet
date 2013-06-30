<?php
class WP_Emmet_Migration_0_2 {
	public static function migrate(WP_Emmet $context) {
		$options = $context->Options->get();

		$editor =& $options['editor'];
		$indent = $options['variables']['indentation'];

		$editor['profile'] = $options['options']['profile'];
		$editor['smartIndent'] = $options['options']['pretty_break'];
		$editor['indentWithTabs'] = $indent === "\t";

		if (!$editor['indentWithTabs']) {
			$editor['indentUnit'] = strlen($indent);
		}

		$shortcuts =& $options['shortcuts'];

		foreach ($shortcuts as $type => $shortcutKey) {
			$shortcuts[$type] = str_replace(
				array('+', 'Meta', 'Cmd-Shift'),
				array('-', 'Cmd', 'Shift-Cmd'),
				$shortcutKey
			);
		}

		$context->Options->save($options);
	}
}