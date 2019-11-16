<?php

namespace data;


class Schema {

	private static $schema;
	private static $types;


	public static function load($path) {

		// load from path
		if (file_exists($path)) {
			$schema = file_get_contents($path);
		}

		// load from file
		else {
			$schema = $path;
		}

		$schema = GraphQL::parse($schema);
		self::parse($schema);

	}


	// parse input array
	public static function parse($graphql) {

		$types = [];

		// iterate schema and add types
		foreach ($graphql as $type) {

			$type_obj = new Type($type[0]);

			switch ($type[0]["op"]) {

				case "schema":
					$type_obj->name($type[0]["op"]);

					self::$schema = $type_obj;
					break;

				case "type":
					self::$types[$type_obj->name()] = $type_obj;
					break;
			}
		}
	}


	// get schema
	// return schema type
	// if field = query || mutation > return corresponding field
	public static function get_schema($field = false) {

		if ($field !== false) {
			return self::$schema->get_field($field);
		}

		return self::$schema;
	}


	// get type
	public static function get_type($name) {

		if (self::has_type($name)) {
			return self::$types[$name];
		}

		return false;
	}


	// returns an list of types
	public static function list_types() {

		return array_keys(self::$types);
	}


	// true if type exists
	// is case sensitive
	public static function has_type($name) {

		return isset(self::$types[$name]);
	}


	// query against schema
	public static function query($query) {
		
		// create graphql schema from query
		$q = GraphQL::parse($query);
echo "<hr>";
new Query($q[0]);
	}


	// parse type name
	// private static function parse_type_name(&$name) {

	// 	$ret = ["op" => false, "type" => false];

	// 	$type_ary = array_filter(explode(" ", $name));

	// 	if (isset($type_ary[0])) {
	// 		$ret["op"] = $type_ary[0];
	// 	}

	// 	if (isset($type_ary[1])) {
	// 		$ret["type"] = $type_ary[1];
	// 	}

	// 	return $ret;
	// }
}