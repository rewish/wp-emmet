<?php
class WP_Emmet_Migration_0_2 {
	public static function migrate(WP_Emmet $context) {
		$options = $context->Options->get();

		foreach (array('options', 'variables') as $key) {
			$options['textarea'][$key] = $options[$key];
		}

		$context->Options->save($options);
	}
}