<?php

namespace data;

class Type_Parser_Query {

	public static function parse($schema) {

		$types = [];
		$schema = $schema[0];
		// iterate schema and add types
		foreach ($schema as $fields) {

			self::parse_field($fields);

			// $type_name = $type[0]["name"];
			// $type_fields = $type[0]["children"][0];

			// $type = new Type($type_name);


			// // add fields to type
			// foreach ($type_fields as $field) {

			// 	$type->add_field(new Field($field));
			// }

			// $types[$type->name()] = $type;
		}

		return $schema;
	}


	private static function parse_field($fields) {
		echo "<hr>";

		foreach ($fields as $field) {

debug($field);
// debug($field["params"]);
// debug($field["children"]);
		}
	}
}