<?php
class WP_Emmet_Migration_0_2 {
	public static function migrate(WP_Emmet $context) {
		$options = $context->option();
		$indent = $options['variables']['indentation'];
		$editor =& $options['editor'];

		$editor['profile'] = $options['options']['profile'];
		$editor['smartIndent'] = $options['options']['pretty_break'];
		$editor['indentWithTabs'] = $indent === "\t";

		if (!$editor['indentWithTabs']) {
			$editor['indentUnit'] = strlen($indent);
		}

		$context->option('editor', $editor);
	}
}