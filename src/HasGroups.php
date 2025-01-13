<?php

trait HasGroups {
	public function add_group($group) {
		$this->groups[] = $group;
		return $this;
	}
}
