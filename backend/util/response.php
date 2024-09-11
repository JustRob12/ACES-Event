<?php
// response(200, true, ["username" => "clarence", "email" => "japinanclarence@gmail.com", "skills" => ["drawing", "singing", "dancing"]]);

function response(bool $success, $data = [], $toCache = false)
{
	$responseData = [];

	if ($toCache == true) {
		header('Cache-control: max-age=60');
	} else {
		header('Cache-control: no-cache, no-store');
	}

	//set reponse data
	$responseData["success"]  = $success;
	foreach ($data as $key => $value) {
		$responseData[$key] = $value;
	}

	echo json_encode($responseData);
}