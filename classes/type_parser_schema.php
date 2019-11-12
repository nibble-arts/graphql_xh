<?php

namespace data;

class Type_Parser_Schema {

	public static function parse($schema) {

		$types = [];

		// iterate schema and add types
		foreach ($schema as $type) {

			self::parse_type_name($type[0]["name"]);


			$type_name = $type[0]["name"];
			$type_fields = $type[0]["children"][0];

			$type = new Type($type_name);


			// add fields to type
			foreach ($type_fields as $field) {

				$f = new Field($field);

				for($i=0;$i<10;$i++) {
					$f->add("val".$i);
				}

				$f->find("0");

				$type->add_field($f);
			}

			$types[$type->name()] = $type;
		}

		return $types;
	}


	// parse type name
	private static function parse_type_name(&$name) {

		$operation = false;
		$type_ary = explode(" ", $name);

		switch ($type_ary[0]) {

			case "schema":
				$operation = "schema";
				break;

			case "type":

				// set type name
				if (count($type_ary) > 1) {
					$name = $type_ary[1];
					$operation = "type";
				}

				else {
					Message::error("type name missing");
				}


				break;

			default:
				Message::error("'" . $type_ary[0] . "' is no schema or type");
				break;
		}

		return $operation;
	} 
}