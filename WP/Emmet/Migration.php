<?php
class WP_Emmet_Migration {
	/**
	 * Migration version name
	 */
	const VERSION_NAME = 'wp-emmet-migration-version';

	/**
	 * Migrate
	 *
	 * @param WP_Emmet $context
	 */
	public static function migrate(WP_Emmet $context) {
		$versions = self::getVersions();

		$currentVersion = get_option(self::VERSION_NAME, 0);
		$latestVersion = 0;

		foreach ($versions as $version => $data) {
			if ($currentVersion < $version) {
				require_once $data['path'];
				call_user_func(array($data['class'], 'migrate'), $context);
			}

			if ($latestVersion < $version) {
				$latestVersion = $version;
			}
		}

		if ($latestVersion > $currentVersion) {
			update_option(self::VERSION_NAME, $latestVersion);
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
			$versions[str_replace('_', '.', $version)] = array(
				'path'  => $file,
				'class' => __CLASS__ . '_' . $version
			);
		}

		return $versions;
	}
}
