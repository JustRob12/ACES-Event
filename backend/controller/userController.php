<?php
use Ulid\Ulid;
require_once(__DIR__ . "/../../util/header.php");
require_once(__DIR__ . "/../../model/User.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {

    case "POST": {
        register();
        break;
    }
    default: {
        $responseMessage = "Request method: {$requestMethod} not allowed!";
        response(false, ["message" => $responseMessage]);
        break;
    }

}
function register()
{
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $middlename = $_POST["middlename"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = findUserByEmail($email);

    if ($result) {
        response(false, ["message" => "User already registered"]);
        exit;
    }

    //generate ulid 
    $id = Ulid::generate(true);

    $data = [
        "id" => $id,
        "firstname"=> $firstname,   
        "lastname"=> $lastname,
        "middlename"=> $middlename,
        "email"=> $email,
        "password"=> $password
    ];

    $user = insertUser($data);
    if (!$user) {
        response(false, ["message"=> "Failed to create user"]);
        exit;
    }
    response(true, ["message" => "User created succesfully!"]);
}
