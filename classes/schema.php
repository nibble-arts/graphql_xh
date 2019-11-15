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

		$schema = InputParser::parse($schema);
		$schema = self::parse($schema);

		return $schema;
	}


	// parse input array
	public static function parse($schema) {
// debug($schema);
		$types = [];

		// iterate schema and add types
		foreach ($schema as $type) {

			// $op_type = self::parse_type_name($type[0]["name"]);

			// switch ($op_type["op"]) {

			// 	case "schema":
			// 		self::$schema = new Type($type[0]["children"][0]);
			// 		break;

			// 	case "type":
			// 		self::$types[$op_type["type"]] = new Type($type[0]);
			// 		break;
			// }


			// $type_name = $type[0]["name"];
			// $type_fields = $type[0]["children"][0];

			// $type = new Type($type_name);


			// // add fields to type
			// foreach ($type_fields as $field) {
			// 	$type->add_field(new Field($field));
			// }

			// $types[$type->name()] = $type;
		}

// debug(self::$schema);
// debug(self::$types);
	}


	// query against schema
	public static function query($query) {
		
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