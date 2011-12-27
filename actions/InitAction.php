<?php
/*
 * sample fields
action	Init
auth_key	fa466889de208a1c12cee583e2d3bdb8
friends[]	755064
friends[]	2547166
profile[bdate]	22.12.1987
profile[city]	1
profile[country]	1
profile[faculty]	3091
profile[faculty_name]	Экономический
profile[first_name]	Михаил
profile[graduation]	2011
profile[last_name]	Чубарь
profile[nickname]	
profile[photo]	http://cs11396.vkontakte.ru/u2373635/e_1980c20b.jpg
profile[photo_big]	http://cs11396.vkontakte.ru/u2373635/a_7ac562dd.jpg
profile[photo_medium]	http://cs11396.vkontakte.ru/u2373635/b_9df3983e.jpg
profile[screen_name]	server_side
profile[sex]	2
profile[timezone]	3
profile[uid]	2373635
profile[university]	207
profile[university_name]	МГУПИ
rnd	0.7205134734856171
viewer_id	2373635
 */

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
		return true;
	}

}