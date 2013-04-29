<?php
/**
 * WP Emmet Options
 */
class WP_Emmet_Options {
	/**
	 * Name of option
	 * @var string
	 */
	protected $name;

	/**
	 * Options
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param string $name Optional, Name of option.
	 */
	public function __construct($name = WP_EMMET_DOMAIN) {
		$this->name = $name;
		$this->setupOptions();
		$this->addAdminMenu();
	}

	/**
	 * Setup options
	 */
	public function setupOptions() {
		$this->options = array_merge(array(
			'variables' => array(
				'indentation' => "\t"
			),

			'options' => array(
				'profile' => 'xhtml',
				'syntax' => 'html',
				'use_tab' => true,
				'pretty_break' => true
			),

			'override_shortcuts' => '',

			'shortcuts' => array(
				'Expand Abbreviation'      => 'Meta+E',
				'Match Pair Outward'       => 'Meta+D',
				'Match Pair Inward'        => 'Shift+Meta+D',
				'Wrap with Abbreviation'   => 'Shift+Meta+A',
				'Next Edit Point'          => 'Ctrl+Alt+Right',
				'Prev Edit Point'          => 'Ctrl+Alt+Left',
				'Select Line'              => 'Meta+L',
				'Merge Lines'              => 'Meta+Shift+M',
				'Toggle Comment'           => 'Meta+/',
				'Split/Join Tag'           => 'Meta+J',
				'Remove Tag'               => 'Meta+K',
				'Evaluate Math Expression' => 'Shift+Meta+Y',

				'Increment number by 1'   => 'Ctrl+Up',
				'Decrement number by 1'   => 'Ctrl+Down',
				'Increment number by 0.1' => 'Alt+Up',
				'Decrement number by 0.1' => 'Alt+Down',
				'Increment number by 10'  => 'Ctrl+Alt+Up',
				'Decrement number by 10'  => 'Ctrl+Alt+Down',

				'Select Next Item'     => 'Meta+.',
				'Select Previous Item' => 'Meta+,',
				'Reflect CSS Value'    => 'Meta+Shift+B'
			)
		), get_option($this->name, array()));
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 */
	public function get($key = null) {
		if (empty($key)) {
			return $this->options;
		}

		$keys = explode('.', $key);
		$option = $this->options;

		do {
			$key = array_shift($keys);
			if (!isset($option[$key])) {
				$option = null;
				break;
			}
			$option = $option[$key];
		} while (!empty($keys));

		return $option;
	}

	/**
	 * Option to JSON
	 *
	 * @param string $key
	 */
	public function toJSON($key) {
		return json_encode($this->get($key));
	}

	/**
	 * Add action
	 */
	public function addAdminMenu() {
		add_action('admin_menu', array($this, 'addOptionsPage'));
	}

	/**
	 * Add options page
	 */
	public function addOptionsPage() {
		add_options_page('Emmet', 'Emmet', 'manage_options', $this->name, array($this, 'pageOptions'));
	}

	/**
	 * Page options
	 */
	public function pageOptions() {
		$domain = WP_EMMET_DOMAIN;
		require_once WP_EMMET_VIEW_DIR . DIRECTORY_SEPARATOR . 'options.php';
	}
}