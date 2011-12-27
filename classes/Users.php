<?php

class Users {

	private static $users = array();

	/**
	 *
	 * @param array $ids - id пользователей
	 * @return array 
	 */
	public static function getByIdsLoaded(array $ids) {
		$out = array();

		if (count($ids)) {
			$to_load = array();
			foreach ($ids as $id) {
				$id = max(0, (int) $id);
				if (isset(self::$users[$id])) {
					// already have this users
					$out[$id] = self::$users[$id];
				} else {
					// need to load
					$to_load[$id] = $id;
				}
			}
			if (count($to_load)) {
				$to_mysql_fetch = array();
				// we need to load some users
				foreach ($to_load as $id) {
					if ($tmp_user_data = self::getFromCache($id)) {
						// we got data from cache
						$out[$id] = self::add($id, $tmp_user_data);
					} else {
						// will be loaded from mysql
						$to_mysql_fetch[$id] = $id;
					}
				}

				if (count($to_mysql_fetch)) {
					// some users will be fetched from mysql
					self::sqlLoad($ids, &$out);
				}
			}
		}
		return $out;
	}

	public static function getFromCache() {
		/* @todo */
		return false;
	}

	public static function add($id, array $data) {
		self::$users[$id] = new User($id, $data);
		return self::$users[$id];
	}

	public static function sqlLoad(array $ids, array $users) {
		if (count($ids)) {
			$query = 'SELECT * FROM `user` WHERE `id` IN (' . implode(',', $ids) . ')';
			$data = Database::sql2array($query, 'id');
			foreach ($data as $row) {
				$users[$row['id']] = self::add($row['id'], $row);
			}
		}
		return $users;
	}

}