<?php

class SettingGroup implements JsonSerializable {

	use HasSettings;

	private $title;

	private $header_text;

	private $footer_text;

	private $settings;

	public function __construct($title, $settings) {
		$this->title = $title;
		$this->settings = $settings;
	}

	public function set_header_text($text) {
		$this->header_text = $text;
		return $this;
	}

	public function set_footer_text($text) {
		$this->footer_text = $text;
		return $this;
	}

	public function jsonSerialize(): array {
		return [
			'title' 		=> $this->title,
			'header_text'	=> $this->header_text,
			'footer_text'	=> $this->footer_text,
			'settings' 		=> $this->settings,
		];
	}

	public static function named($name): SettingGroup {
		return new SettingGroup($name, []);
	}
}
