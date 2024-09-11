<?php
require_once(__DIR__ . "/../../util/header.php");
require_once(__DIR__ . "/../../model/User.php");

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
    //data from forms
    $email = $_POST["email"];
    $password = $_POST["password"];
    //fetch user email
    $result = findUserByEmail($email);
    //validate if user exists and password matches with the encrypted password
    if (!$result || !password_verify($password, $result["password"])) {
        response(false, ["message" => "Invalid Credentials"]);
        exit;
    }
    //return user information for page access
    $returnData = [
        "userId" => $result["id"],
        "firstname" => $result["firstname"],
        "lastname" => $result["lastname"],
        "middlename"=> $result["middlename"],
        "role" => $result["role"]
    ];
    response(true, $returnData);
}
