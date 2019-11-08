<?php

// init class autoloader
spl_autoload_register(function ($path) {


	if ($path && strpos($path, "data\\") !== false) {

		$path = str_replace('\\', '/', $path);
		$path = "classes/" . str_replace("data/", "", strtolower($path)) . ".php";

		include_once $path;
	}
});


data\Main::init($plugin_cf, $plugin_tx);


// main plugin function
function data ($data_name = false, $query = false) {

	$o = data\Text::plugin_name();

	// load data structure
	data\Data::load($data_name);

	data\Data::query("
query {
	Actor
	{
		id
		name
		forename
		addresses
		{
			address
			zip
			city
			land
			{
				name
			}
		}
	}
}
");

	// $data = [
	// 	"forename" => "Thomas",
	// 	"name" => "Winkler",
	// 	"birth" => "1965-02-09",
	// 	"username" => "tom",
	// 	"addresses" => [
	// 		[
	// 			"address" => "Maurer Lange Gasse 136/10/2",
	// 			"zip" => "1230",
	// 			"city" => "Wien",
	// 			"type" => "privat",
	// 			"land" => [
	// 				[
	// 					"name" => "Österreich",
	// 					"short" => "AT"
	// 				]
	// 			]
	// 		],
	// 		[
	// 			"address" => "Linzerstraße 3",
	// 			"zip" => "1140",
	// 			"city" => "Wien",
	// 			"type" => "arbeit",
	// 			"land" => "5dbd47195100f"
	// 		]
	// 	]
	// ];


	// data\Data::add_to_type("actor", $data);
	// $o .= data\Data::dump();



	// data\Data::query($query);



	return $o;
}



?>