<?php

class Setting implements JsonSerializable {
	// String – one of 'bool', 'string', 'enum', 'color', 'date', 'uuid', 'post_id', 'media_id', 'user_id', 'role'
	var $type;

	// String – defined by your API
	var $key;

	// Any JSON-representable value
	var $value;

	// An (optional) default value – if not provided, this Setting will default to `null`
	//
	var $default;

	// A user-facing description string. It should be localized using existing WordPress conventions
	var $description;

	// A list of acceptable values for `enum` types
	var $allowed_values;

	// The required capability to modify this setting
	var $capability;

	function __construct($type, $key, $value) {
		$this->type = $type;
		$this->key = $key;
		$this->value = $value;
	}

	static function from_bool($key, $value) {
		return new Setting('bool', $key, $value);
	}

	static function from_int($key, $value) {
		return new Setting('int', $key, $value);
	}

	static function from_enum($key, $value, $allowed_values) {
		return (new Setting('enum', $key, $value))->set_allowed_values($allowed_values);
	}

	static function from_color($key, $value) {
		return new Setting('color', $key, $value);
	}

	static function from_date($key, $value) {
		return new Setting('date', $key, $value);
	}

	public function is_valid_value($value) {
		switch($type) {
			case 'bool': return in_array($value, [true, false]);
		}
	}

	public function jsonSerialize(): array {
		return [
			'type'					=> $this->type,
			'key'					=> $this->key,
			'value'					=> $this->get_value(),
			'default'				=> $this->default,
			'description'			=> $this->description,
			'allowedValues'			=> $this->allowed_values,
			'requiredCapability'	=> $this->capability,
		];
	}

	// Object Builders
	public function set_value($value) {
		$this->value = $value;
		return $this;
	}

	public function set_allowed_values($values) {
		$this->allowed_values = $values;
		return $this;
	}

	public function set_description($description) {
		$this->description = $description;
		return $this;
	}

	// Returns a value that's coerced to the expected type
	//
	public function get_value() {
		switch($this->type) {
			case 'bool': return $this->coerce_to_bool();
			case 'int': return $this->coerce_to_int();
			case 'enum': return $this->coerce_to_enum();
			case 'color': return $this->coerce_to_color();
			case 'date': return $this->coerce_to_date();
		}
	}

	private function coerce_to_bool() {
		if(is_bool($this->value) === true) {
			return $this->value;
		}

		switch(trim($this->value)) {
			case 'true': return true;
			case 'false': return false;
		}
	}

	private function coerce_to_int() {
		if(is_int($this->value) === true) {
			return $this->value;
		}

		return intval($this->value);
	}

	private function coerce_to_enum() {
		if(in_array($this->value, $this->allowed_values, true)) {
			return $this->value;
		}

		return $this->default;
	}

	private function coerce_to_color() {
		$hex = preg_replace("/[^0-9A-Fa-f]/", '', $this->value); // Filter out non-hex chars
		$hex = ltrim($hex, '#');

		// Check if the string has 3, 4, 6, or 8 characters
		if (strlen($hex) == 3) {
			// If it's 3 characters, convert to 6 characters
			$hex = str_repeat(substr($hex, 0, 1), 2) .
				str_repeat(substr($hex, 1, 1), 2) .
				str_repeat(substr($hex, 2, 1), 2);
		} elseif (strlen($hex) == 4) {
			// If it's 4 characters, convert to 8 characters
			$hex = str_repeat(substr($hex, 0, 1), 2) .
				str_repeat(substr($hex, 1, 1), 2) .
				str_repeat(substr($hex, 2, 1), 2) .
				str_repeat(substr($hex, 3, 1), 2);
		} elseif (strlen($hex) != 6 && strlen($hex) != 8) {
			return null; // Invalid hex color
		}

		// Convert hex to RGB and alpha values
		$rgb = [
			'red'   => hexdec(substr($hex, 0, 2)),
			'green' => hexdec(substr($hex, 2, 2)),
			'blue'  => hexdec(substr($hex, 4, 2))
		];

		if (strlen($hex) == 8) {
			$rgb['alpha'] = hexdec(substr($hex, 6, 2));

			if($rgb['alpha'] == 255) {
				return '#' . dechex($rgb['red']) . dechex($rgb['green']) . dechex($rgb['blue']);
			}

			return '#' . dechex($rgb['red']) . dechex($rgb['green']) . dechex($rgb['blue']) . sprintf('%02x', $rgb['alpha']);
		} else {
			return '#' . dechex($rgb['red']) . dechex($rgb['green']) . dechex($rgb['blue']);
		}

		return '#' . $hex;
	}

	private function coerce_to_date() {
		if($this->value instanceof DateTime) {
			return $this->value;
		}

		$timestamp = strtotime($this->value);

		if($timestamp === false) {
			return null;
		}

		return date('c', $timestamp);
	}
}
