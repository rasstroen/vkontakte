<?php

class CurrentUser extends User {

	function register($data) {
		$this->load();
		if ($this->is_registered())
			return false;
		foreach ($data as $field => $value)
			$this->setProperty($field, $value, true);
		return true;
	}

	function updateSocialProfile($data) {
		foreach ($data as $field => $value)
			$this->setProperty($field, $value, true);
		return true;
	}

}