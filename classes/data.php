<?php

namespace data;


class Data {

	private static $base_path;
	private static $references;
	private static $groups;
	private static $last_id;


	// init data class at base path
	public static function init($path) {

		self::$base_path = $path;
		self::$last_id = [];
	}


	// load group ini reference files
	public static function load($data_name) {

		$path = self::$base_path . $data_name . "/";

		$files = scandir($path);

		foreach ($files as $file) {

			$name = pathinfo($file, PATHINFO_FILENAME);

			if (pathinfo($file, PATHINFO_EXTENSION) == "ini") {

				self::add_reference($name, parse_ini_file($path . $file, true));
			}
		}
	}


	// query data
	public static function query($query) {

		$q = new Query($query);
	}


	// add reference
	public static function add_reference($name, $reference) {
		self::$references[$name] = $reference;
	}


	// add data to group
	// if group doesnt exist, return false
	// optional uuid for existing data
	// if false, create new uuid
	public static function add_to_group($group_name, $data, $uuid = false) {

		$ref = self::get_reference($group_name);

		if ($ref) {

			$new_group = new Group($group_name, $ref, $uuid);
			$uuid = $new_group->uuid();

			$new_group->add($data, $uuid);

			self::$groups[$group_name][$uuid] = $new_group;
			self::$last_id[$group_name] = $uuid;
		}
	}


	// get group by name [,id]
	// if id = false > return array of groups
	// return group with id
	public static function get_group($group, $idx = false) {

		if ($idx !== false) {

			if (isset(self::$groups[$group][$idx])) {
				return self::$groups[$group][$idx];
			}
		}

		else {

			if (isset(self::$groups[$group])) {
				return self::$groups[$group];
			}
		}

		return false;
	}


	// get group reference by name if exists
	// else, return false
	public static function get_reference($name) {

		if (isset(self::$references[$name])) {
			return self::$references[$name];
		}

		return false;
	}


	// get last insert id from group
	// if empty, returns false
	public static function last_id($name) {

		if (isset(self::$last_id[$name])) {
			return self::$last_id[$name];
		}

		return false;
	}


	// get list of groups
	public static function get_group_list() {
		return array_keys(self::$references);
	}

	// dump data object
	public static function dump() {

		$ret = "";

		$ret .= '<table>';

		foreach (self::get_group_list() as $name) {

			$ret .= '<tr colspan="3"><th>' . $name . '</th></tr>';

			$groups = self::get_group($name);

			$curr_uuid = 0;

			foreach ($groups as $uuid => $entry) {

				foreach ($entry->values() as $key => $value) {

					$ret .= '<tr>';

						$ret .= '<td>';
							if ($uuid != $curr_uuid) {
								$ret .= $uuid;
								$curr_uuid = $uuid;
							}
						$ret .= '</td>';

						$ret .= '<td>' . $key . '</td>';
						$ret .= '<td>' . implode("<br>", $value) . '</td>';
					$ret .= '</tr>';
				}

				foreach ($entry->links() as $key => $value) {

					$ret .= '<tr>';
						$ret .= '<td>';
							if ($uuid != $curr_uuid) {
								$ret .= $uuid;
								$curr_uuid = $uuid;
							}
						$ret .= '</td>';

						$ret .= '<td>' . $key . '</td>';

						$ret .= '<td>';
							foreach ($value as $k => $v) {
								$ret .= '>> ' . $k . '[' . implode(", ", $v) . ']';
							}
						$ret .= '</td>';
					$ret .= '</tr>';
				}

			}
		}

		$ret .= '</table>';

		return $ret;
	}
}