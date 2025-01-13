<?php

class SettingSuite implements JsonSerializable {

	use HasSettings;

	private $title;

	private $icon;

	private $color;

	private $groups = [];

	private $pages = [];

	private $settings = [];

	public function __construct($title, $icon, $color, $settings = []) {
		$this->title = $title;
		$this->icon = $icon;
		$this->color = $color;
		$this->settings = $settings;
	}

	public function add_group($group) {
		$this->groups[] = $group;
		return $this;
	}

	public function add_page($page) {
		$this->pages[] = $page;
		return $this;
	}

	public function jsonSerialize(): array {
		return [
			'title'		=> $this->title,
			'icon'		=> empty($this->icon) ? null : $this->icon,
			'color'		=> empty($this->color) ? null : $this->color,
			'pages'		=> $this->pages,
			'groups'	=> $this->groups,
			'settings'	=> $this->settings,
		];
	}
}
