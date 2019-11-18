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
	data\Data::activate($data_name);

	data\Data::query('
query {
	actor (id: $id, name: String)
	{
		name
		forename
		addresses
		{
			address
			zip
			city
			land
			{
				name: "Österreich"
			}
			
		}
		films
		{
			title
		}
	}
	film (title: "*count*")
	{
		title
		length
	}
}

mutation {
	actor (id: ID!) {
		name: "Winkler"
		forename: "Thomas"
	}
}
');





	debug(data\Message::get());

	return $o;
}



?>