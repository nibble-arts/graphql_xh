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

	// $value1 = new data\Value("name");
	// $value2 = new data\Value("surname");

	// $value1->add(["Thomas", "Jitka", "Michael", "Lukas"]);
	// $value2->add(["Winkler", "Winkler", "Winkler", "Winkler"]);

	data\Data::load($attr);

	// $data = [
	// 	"forename" => "Lukas",
	// 	"name" => "Winkler",
	// 	"birth" => "2008-10-13",
	// 	"username" => "luki"
	// ];

	// data\Data::add_to_group("actor", $data);


	// $data = [
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
	// 		];
	// data\Data::add_to_group("address", $data);

	// $data = [
	// 			"address" => "Linzerstraße 3",
	// 			"zip" => "1140",
	// 			"city" => "Wien",
	// 			"type" => "arbeit"
	// 		];
	// data\Data::add_to_group("address", $data);

	$data = [
		"forename" => "Thomas",
		"name" => "Winkler",
		"birth" => "1965-02-09",
		"username" => "tom",
		"addresses" => [
			[
				"address" => "Maurer Lange Gasse 136/10/2",
				"zip" => "1230",
				"city" => "Wien",
				"type" => "privat",
				"land" => [
					[
						"name" => "Österreich",
						"short" => "AT"
					]
				]
			],
			[
				"address" => "Linzerstraße 3",
				"zip" => "1140",
				"city" => "Wien",
				"type" => "arbeit",
				"land" => 0
			]
		]
	];


	data\Data::add_to_group("actor", $data);


	debug(data\Data::dump());


	$query = [
		"forename",
		"name" => "wink*",
		"addresses",
		[
			"address",
			"zip",
			"city" => "wien",
			"land",
			[
				"name"
			]
		]
	];

debug($query);

	// $group_ini = parse_ini_string($group_ini, true);

	// $actor = new data\Group("actor", $group_ini);

	// $actor->add_value("username", "tom");
	// $actor->add_values([
	// 	"forename" => "Thomas",
	// 	"name" => "Winkler",
	// 	"birth" => "1965-02-09",
	// 	"username" => "tom",
	// 	"addresses" => [
	// 		[
	// 			"address" => "Maurer Lange Gasse 136/10/2",
	// 			"zip" => "1230",
	// 			"city" => "Wien",
	// 			"type" => "privat"
	// 		]
	// 	]
	// ]);

// debug(data\Data::get_group("actor"));
// debug(data\Data::get_group("address"));
// debug($actor);
	return $o;
}



?>