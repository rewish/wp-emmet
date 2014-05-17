<?php
class WP_Emmet_Migration_0_2_5 {
	public static function migrate(WP_Emmet $context) {
		$context->Options->set('scope.others', '1');
	}
}