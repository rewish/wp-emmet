<?php
class WP_Emmet_Migration_0_2 {
	public static function migrate(WP_Emmet $context) {
		$options = $context->Options->get();
		$updated = false;

		foreach (array('options', 'variables') as $key) {
			if (isset($options[$key])) {
				$options['textarea'][$key] = $options[$key];
				$updated = true;
			}
		}

		if ($updated) {
			$context->Options->save($options);
		}
	}
}