<?php

class SettingPage implements JsonSerializable {

	use HasSettings, HasGroups;

	private $title;

	private $description;

	private $groups;

	private $settings;

	public function __construct($title, $description, $settings = []) {
		$this->title = $title;
		$this->description = $description;
		$this->settings = $settings;
	}

	public function jsonSerialize(): array {
		return [
			'title' 		=> $this->title,
			'description'	=> $this->description,
			'settings' 		=> $this->settings,
			'groups'		=> $this->groups,
		];
	}
}
