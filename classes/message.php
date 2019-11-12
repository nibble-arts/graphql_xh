<?php

namespace data;


class Message {

	private static $message;

	public static function error($string) {
		self::$message["error"][] = $string;
	}

	public static function warning($string) {
		self::$message["warning"][] = $string;
	}

	public static function info($string) {
		self::$message["info"][] = $string;
	}

	public static function get($type = false) {

		if ($type !== false) {		
			if (isset(self::$message[$type])) {
				return self::$message[$type];
			}
		}

		else {
			return self::$message;
		}
	}

}