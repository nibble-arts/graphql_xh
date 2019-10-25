<?php

// init class autoloader
spl_autoload_register(function ($path) {

	if ($path && strpos($path, "data\\") !== false) {
		$path = "classes/" . str_replace("data\\", "", strtolower($path)) . ".php";
		include_once $path; 
	}
});






?>