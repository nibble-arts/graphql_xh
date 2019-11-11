<?php

namespace data;

class Type_Parser_Query {

	public static function parse($schema) {

		$types = [];

		// iterate schema and add types
		foreach ($schema as $type) {

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
}