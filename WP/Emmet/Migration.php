<?php
class WP_Emmet_Migration {
	/**
	 * Migration version name
	 */
	const VERSION_NAME = 'wp-emmet-migrations';

	/**
	 * Migrate
	 *
	 * @param WP_Emmet $context
	 */
	public static function migrate(WP_Emmet $context) {
		$currentVersion = get_option(self::VERSION_NAME, 0);
		$versions = self::getVersions();
		$version = 0;

		foreach ($versions as $info) {
			if ($currentVersion >= $info['version']) {
				continue;
			}

			require_once $info['path'];
			call_user_func(array($info['class'], 'migrate'), $context);

			if ($version < $info['version']) {
				$version = $info['version'];
			}
		}

		if ($version > $currentVersion) {
			update_option(self::VERSION_NAME, $version);
		}
	}

	/**
	 * Get versions
	 *
	 * @return array
	 */
	public static function getVersions() {
		$files = glob(dirname(__FILE__) . '/Migration/*.php');
		$versions = array();

		foreach ($files as $file) {
			$version = basename($file, '.php');
			$versions[] = array(
				'version' => str_replace('_', '.', $version),
				'path'    => $file,
				'class'   => __CLASS__ . '_' . $version
			);
		}

		return $versions;
	}
}
