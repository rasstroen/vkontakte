<?php

class InitAction extends BaseAction {

	function process() {
		global $current_user;

		$profile = Request::get('profile');
		$profile['bdate'] = (int) @strtotime($profile['bdate']);
		if ($profile['bdate'])
			$profile['bdate'] = date('Y-m-d', $profile['bdate']);
		$data = array(
		    'first_name' => $profile['first_name'],
		    'last_name' => $profile['last_name'],
		    'bdate' => $profile['bdate'],
		    'city' => (int) $profile['city'],
		    'country' => (int) $profile['country'],
		    'nickname' => $profile['nickname'],
		    'photo' => $profile['photo'],
		    'photo_big' => $profile['photo_big'],
		    'photo_medium' => $profile['photo_medium'],
		    'sex' => min(2, max(0, (int) $profile['sex'])),
		);
		// user registering or updating profile
		if ($current_user->register($data)) {
			
		} else {
			$current_user->updateSocialProfile($data);
		}
		/* @var $current_user CurrentUser */
		$this->setResponseField('profile', $current_user->getClientProfile());
		$current_user->setProperty('lastLogin', time());
		$current_user->save();
	}

}