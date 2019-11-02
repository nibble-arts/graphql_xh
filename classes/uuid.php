<?php

namespace data;


class UUID {

	public static function create() {
		return uniqid();
	}
}

?>