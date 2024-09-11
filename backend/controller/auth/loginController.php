<?php
require_once(__DIR__ . "/../../util/header.php");
require_once(__DIR__ . "/../../model/Event.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {

    case "POST": {
        login();
        break;
    }
    default: {
        $responseMessage = "Request method: {$requestMethod} not allowed!";
        response(false, ["message" => $responseMessage]);
        break;
    }

}
function login()
{
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = findUserByEmail($email);

    if (!$result || !password_verify($password, $result["password"])) {
        response(false, ["message" => "Invalid Credentials"]);
        exit;
    }

    $returnData = [
        "userId" => $result["id"],
        "email" => $result["email"],
        "role" => $result["role"]
    ];
    response(true, $returnData);
}
