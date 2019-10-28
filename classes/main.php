<?php

namespace data;


class Main {

	public static function init($config, $text) {

		Config::init($config["data"]);
		Text::init($text["data"]);

	}
}