<?php
//required headers
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json");
// header("Access-Control-Allow-Methods: POST, PATCH, GET, DELETE");
// header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, X-Requested-With");

// if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "OPTIONS") {
// 	header('Access-Control-Allow-Origin: *');
// 	header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Allow-Methods,Access-Control-Request-Headers, Authorization");
// 	//header("HTTP/1.1 200 OK");
// 	exit(0);
// }

require_once(__DIR__ . "/response.php");
require_once(__DIR__ . "/util.php");
require_once(__DIR__ . "/../vendor/autoload.php");