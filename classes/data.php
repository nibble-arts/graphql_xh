<?php

namespace data;


class Data {

	private static $base_path;
	private static $references;
	private static $schema;
	private static $last_id;


	// init data class at base path
	public static function init($path) {

		self::$base_path = $path;
		self::$last_id = [];
	}


	// load type ini reference files
	public static function load($data_name) {

		$path = self::$base_path . $data_name . "/";

		self::$schema = new Graph($path . "query.gql");

debug(self::$schema);
		// $files = scandir($path);

		// foreach ($files as $file) {

		// 	$name = pathinfo($file, PATHINFO_FILENAME);

		// 	if (pathinfo($file, PATHINFO_EXTENSION) == "ini") {

		// 		self::add_reference($name, parse_ini_file($path . $file, true));
		// 	}
		// }
	}


	// query data
	public static function query($query) {

		$q = new Query($query);
	}


	// add reference
	public static function add_reference($name, $reference) {
		self::$references[$name] = $reference;
	}


	// add data to type
	// if type doesnt exist, return false
	// optional uuid for existing data
	// if false, create new uuid
	public static function add_to_type($type_name, $data, $uuid = false) {

		$ref = self::get_reference($type_name);

		if ($ref) {

			$new_type = new Type($type_name, $ref, $uuid);
			$uuid = $new_type->uuid();

			$new_type->add($data, $uuid);

			self::$types[$type_name][$uuid] = $new_type;
			self::$last_id[$type_name] = $uuid;
		}
	}


	// get type by name [,id]
	// if id = false > return array of types
	// return type with id
	public static function get_type($type, $idx = false) {

		if ($idx !== false) {

			if (isset(self::$types[$type][$idx])) {
				return self::$types[$type][$idx];
			}
		}

		else {

			if (isset(self::$types[$type])) {
				return self::$types[$type];
			}
		}

		return false;
	}


	// get type reference by name if exists
	// else, return false
	public static function get_reference($name) {

		if (isset(self::$references[$name])) {
			return self::$references[$name];
		}

		return false;
	}


	// get last insert id from type
	// if empty, returns false
	public static function last_id($name) {

		if (isset(self::$last_id[$name])) {
			return self::$last_id[$name];
		}

		return false;
	}


	// get list of types
	public static function get_type_list() {
		return array_keys(self::$references);
	}

	// dump data object
	public static function dump() {

		$ret = "";

		$ret .= '<table>';

		foreach (self::get_type_list() as $name) {

			$ret .= '<tr colspan="3"><th>' . $name . '</th></tr>';

			$types = self::get_type($name);

			$curr_uuid = 0;

			foreach ($types as $uuid => $entry) {

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