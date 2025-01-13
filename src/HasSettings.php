<?php

trait HasSettings {
	public function add_setting($setting) {
		$this->settings[] = $setting;
		return $this;
	}
}
