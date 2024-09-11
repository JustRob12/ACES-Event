<?php

namespace database;

use PDO;
use PDOException;

require_once(__DIR__ . "/../util/response.php");
require_once(__DIR__ . "/../util/util.php");

class Database
{
	private const DB_HOST = "localhost";
	private const DB_NAME = "aces_system";
	private const DB_USERNAME = "aces_system_admin";
	private const DB_PASSWORD = "acsSysadmin";

	public static function connect()
	{
		try {
			$dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME;
			$pdo = new PDO($dsn, self::DB_USERNAME, self::DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			$response = [
				"message" => "Error: {$e->getMessage()} on line {$e->getLine()}"
			];
			response(500, false, $response);
			exit;
		}
		return $pdo;
	}
}