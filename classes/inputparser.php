<?php

/*
 * GraphQL input parser
 *
 * extracts { } sections recursively
 * returns an multidimensional array
 *
 * @Author: Thomas Winkler
 * @Copyright: 2019
 */

namespace data;

class InputParser {

	private $schema;
	private $default_types;
	private $schema_types;


	public static function parse($schema) {

		return self::parse_sections($schema);
	}


	// ************************************************
	public static function query($query) {
// debug($query);
	}


	// ************************************************
	// parse schema recursively
	private static function parse_sections($schema) {

		$ret_array = [];
		$sections = self::extract_sections($schema);

		// sections found
		if ($sections) {

			foreach ($sections as $idx => $part) {

				switch (array_keys($part)[0]) {

					case "type":

						$lines = self::split_lines($part["type"]);

						// get last type entry
						$last = end($lines);

						if (count($lines)) {
							$ret_array[$idx] = $lines;

// 							debug(new Scalar($lines));
						}
						break;

					case "child":

						// get index of last type entry
						$sub_idx = count($ret_array[$idx-1])-1;

						// is not on root level
						if ($sub_idx >= 0) {

							$ret_array[$idx-1][$sub_idx]["children"] = self::parse_sections($part["child"]);
						}

						$last = "";
						break;
				}
			}
		}

		// return type
		else {
			return $schema;
		}

		return $ret_array;
	}


	// ************************************************
	// find { } sections and return array of type and sections
	private static function extract_sections($string) {

		$ret = [];

		preg_match_all('/\{((?:[^{}]++|(?R))*)}/', $string, $matches, PREG_OFFSET_CAPTURE);

		if (count($matches)) {

			$cursor = 0;

			foreach ($matches[1] as $hit) {

				if (($hit[1] - $cursor) > 0) {

					$name = substr($string, $cursor, ($hit[1] - $cursor - 1));
					$type = self::parse_type_name($name);

					$ret[] = ["type" => $name];
				}

				$ret[] = ["child" => $hit[0]];

				$cursor = $hit[1] + strlen($hit[0]) + 1;
			}

			// add rest
			if ($cursor < (strlen($string))) {
				$ret[] = ["type" => substr($string, $cursor)];
			}

			return $ret;
		}
		return false;
	}


	// ************************************************
	// split string to trimmed lines
	private static function split_lines($string) {

		$ret = [];
		$lines = explode("\n", $string);
// $lines = preg_split('(/\n\ )/', $string);

		foreach ($lines as $line) {

			$line = trim($line);

			if ($line != "") {

				$ret[] = self::parse_line($line);
			}
		}

		return $ret;
	}


	// ************************************************
	// parse line
	private static function parse_line($line) {

		$param = self::get_parameters($line);
		[$type, $string] = self::get_type_string($line);

		return [
			"name" => $line,
			"type" => $type,
			// "value" => $string,
			"params" => $param
		];
	}


	// ************************************************
	// get parameters
	private static function get_parameters(&$string) {

		preg_match('/\(([^{}]+)\)/', $string, $matches);

		if (count($matches)) {
			$string = str_replace($matches[0], "", $string);

			$params = self::explode_param($matches[1]);

			return $params;
		}
	}


	// ************************************************
	// explode parameter string
	private static function explode_param($string) {

		$ret = [];
		$params = explode(",", $string);

		foreach ($params as $param) {

			$key_val = explode(":", $param);

			if (count($key_val) > 1) {
				$ret[trim($key_val[0])] = trim($key_val[1]);
			}
		}

		return $ret;
	}


	// ************************************************
	// get quoted
	private static function get_type_string(&$type_string) {

		$type = "";
		$string = "";

		$field_type = explode(":", $type_string);

		if (count($field_type) > 1) {

			$type_string = trim($field_type[0]);

			if (self::is_quoted($field_type[1])) {
				$string = trim($field_type[1]);
			}
			else {
				$type = trim($field_type[1]);
			}
		}

		return [$type, $string];
	}


	// ************************************************
	// checks if string is quoted
	// false if no quotes
	// string without quotes if true
	private static function is_quoted($string) {

		preg_match('/[\"]([^\"]+)\"/', $string, $match);

		// is quoted
		if ($match) {
			return ($match[1]);
		}

		// no quotes
		return false;
	}


	// parse type name
	private static function parse_type_name(&$name) {

		$ret = false;

		$type_ary = array_filter(explode(" ", $name));

		if (isset($type_ary[0])) {
			$ret = trim($type_ary[0]);
		}

		if (isset($type_ary[1])) {
			$name = trim($type_ary[1]);
		}

		return $ret;
	}
}

?>