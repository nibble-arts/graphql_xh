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
	private static function parse_sections($schema, $level = 0) {

		$ret_array = [];
		$sections = self::extract_sections($schema);

		// sections found
		if ($sections) {

			foreach ($sections as $idx => $part) {

				if (isset($part["name"])) {

					$lines = self::split_lines($part["name"], $level);

					// get last type entry
					$last = end($lines);

					if (count($lines)) {
						$ret_array[$idx] = $lines;
					}
				}


				// recursion
				if (isset($part["child"])) {

					// get index of last type entry
					$sub_idx = count($ret_array[$idx-1])-1;

					// is not on root level
					if ($sub_idx >= 0) {

						$ret_array[$idx-1][$sub_idx]["children"] = self::parse_sections($part["child"], $level  + 1);
					}

					$last = "";
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

					$ret[] = ["name" => trim($name)];
				}

				$ret[] = ["child" => $hit[0], "type" => "children"];

				$cursor = $hit[1] + strlen($hit[0]) + 1;
			}

			// add rest
			if ($cursor < (strlen($string))) {
				$ret[] = ["name" => substr($string, $cursor)];
			}

			return $ret;
		}

		return false;
	}


	// ************************************************
	// split string to trimmed lines
	private static function split_lines($string, $level) {

		$ret = [];
		$lines = explode("\n", $string);

		// iterate lines
		foreach ($lines as $line) {

			$line = trim($line);

			if ($line != "") {
				$ret[] = self::parse_line($line, $level);
			}
		}

		return $ret;
	}


	// ************************************************
	// parse line
	private static function parse_line($line, $level) {

		$op = "";
		$field = "";
		$type = "";
		$name = "";
		$params = [];
		
		$line = trim($line);

		$param = self::get_parameters($line);

		// test for field => type
		$split = explode(":", $line);

		if (count($split) > 1) {

			$field = trim($split[0]);

			if (self::is_quoted($split[1])) {
				$name = trim($split[1]);
			}
			else {
				$type = trim($split[1]);
			}
		}

		// type => name
		else {

			$split = explode(" ", trim($line));

			if (count($split) > 1) {
				$op = trim($split[0]);
				$name = trim($split[1]);
			}

			else {
				// op on root level
				if (!$level) {
					$op = trim($line);
				}

				// field on sublevel
				else {
					$field = $line;
				}
			}
		}


		return [
			"op" => $op,
			"field" => $field,
			"type" => $type,
			"name" => $name,
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
}

?>