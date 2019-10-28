<?php

// init class autoloader
spl_autoload_register(function ($path) {

	if ($path && strpos($path, "data\\") !== false) {
		$path = "classes/" . str_replace("data\\", "", strtolower($path)) . ".php";
		include_once $path; 
	}
});


data\Main::init($plugin_cf, $plugin_tx);


// main plugin function
function data ($attr = false) {

	$o = data\Text::plugin_name();


	$value = new data\Value("name");
	$value->unique(true);


	$value->add("Thomas");
	$value->add("Winkler");
	$value->add(["Thomas", "thomas"]);

// debug($value);
	$result = $value->find("*thom*");

debug($result);



	return $o;
}



?>