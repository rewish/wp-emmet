<?php
class WP_Emmet_FormHelper {
	/**
	 * Base of Name attributes
	 * @var string
	 */
	protected $name;

	/**
	 * Values
	 * @var array
	 */
	protected $values;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param array $values
	 */
	public function __construct($name, Array $values) {
		$this->name = $name;
		$this->values = $values;
	}

	/**
	 * Arrayize
	 *
	 * @param string|array $value
	 * @param string $delimiter
	 * @return array
	 */
	public function arrayize($value, $delimiter = '.') {
		return is_array($value) ? $value : explode($delimiter, $value);
	}

	/**
	 * ID attribute
	 *
	 * @param string $name
	 * @return string
	 */
	public function id($name) {
		$arr = $this->arrayize($name);
		array_unshift($arr, $this->name);
		return implode('-', $arr);
	}

	/**
	 * Name attribute
	 *
	 * @param string $name
	 * @return string
	 */
	public function name($name) {
		return $this->name . '[' . implode('][', $this->arrayize($name)) . ']';
	}

	/**
	 * Value attribute
	 *
	 * @param string $name
	 * @return array
	 */
	public function value($name) {
		$names = $this->arrayize($name);
		$value = $this->values;

		while (count($names) > 0) {
			$value = $value[array_shift($names)];
		}

		return $value;
	}

	/**
	 * Attributes
	 *
	 * @param array $attributes
	 * @return string
	 */
	public function attributes(Array $attributes) {
		foreach ($attributes as $key => &$value) {
			if ($key === 'checked' && $value) {
				$value = 'checked';
			}
			if ($key === 'selected' && $value) {
				$value = 'selected';
			}
			if ($value !== false) {
				$value = "$key=\"$value\"";
			}
		}
		return implode(' ', $attributes);
	}

	/**
	 * Label tag
	 *
	 * @param string $name
	 * @param string $text
	 * @param array $attributes
	 * @return string
	 */
	public function label($name, $text, Array $attributes = array()) {
		$attr = $this->attributes(array('for' => $this->id($name)) + $attributes);
		return sprintf('<label %s>%s</label>', $attr, $text);
	}

	/**
	 * Input tag
	 *
	 * @param string $name
	 * @param string $type
	 * @param array $attributes
	 * @return string
	 */
	public function input($name, $type, Array $attributes = array()) {
		return sprintf('<input %s>', $this->attributes($attributes + array(
			'name' => $this->name($name),
			'type' => $type,
			'id' => $this->id($name)
		)));
	}

	/**
	 * Hidden field
	 *
	 * @param $name
	 * @param $value
	 * @param array $attributes
	 * @return string
	 */
	public function hiddenField($name, $value, Array $attributes = array()) {
		return $this->input($name, 'hidden', array('value' => $value, 'id' => false) + $attributes);
	}

	/**
	 * Text field
	 *
	 * @param string $name
	 * @param array $attributes
	 * @return string
	 */
	public function textField($name, Array $attributes = array()) {
		return $this->input($name, 'text', array('value' => $this->value($name)) + $attributes);
	}

	/**
	 * Number field
	 *
	 * @param string $name
	 * @param array $attributes
	 * @return string
	 */
	public function numberField($name, Array $attributes = array()) {
		return $this->input($name, 'number', array('value' => $this->value($name)) + $attributes);
	}

	/**
	 * Checkbox field
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */
	public function checkbox($name, $value, Array $attributes = array()) {
		return $this->input($name, 'checkbox', $attributes + array(
			'value' => $value,
			'checked' => $value === $this->value($name)
		));
	}

	/**
	 * Checkbox of Boolean
	 *
	 * @param string $name
	 * @param array $attributes
	 * @return string
	 */
	public function checkBoolean($name, Array $attributes = array()) {
		return $this->hiddenField($name, '') . $this->checkbox($name, '1', $attributes);
	}

	/**
	 * Option tag
	 *
	 * @param string $label
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */
	public function option($label, $value, Array $attributes = array()) {
		$attrs = $this->attributes($attributes);

		if (is_numeric($label)) {
			$label = $value;
		}

		return sprintf('<option value="%s" %s>%s</option>', $value, $attrs, $label);
	}

	/**
	 * Select tag
	 *
	 * @param string $name
	 * @param string|array $values
	 * @param array $attributes
	 * @return string
	 */
	public function select($name, $values, Array $attributes = array()) {
		$attrs = $this->attributes($attributes + array('name' => $this->name($name)));
		$values = $this->arrayize($values, ',');
		$currentValue = $this->value($name);
		$optionTags = array();

		foreach ($values as $label => $value) {
			$optionTags[] = $this->option($label, $value, array(
				'selected' => $currentValue === $value
			));
		}

		return sprintf('<select %s>%s</select>', $attrs, implode('', $optionTags));
	}

	/**
	 * Textarea
	 *
	 * @param string $name
	 * @param array $attributes
	 * @return string
	 */
	public function textarea($name, Array $attributes = array()) {
		$attrs = $this->attributes($attributes + array('name' => $this->name($name)));
		return sprintf('<textarea %s>%s</textarea>', $attrs, $this->value($name));
	}
}