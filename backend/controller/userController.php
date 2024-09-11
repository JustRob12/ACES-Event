<?php
use Ulid\Ulid;
require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../model/User.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//manage request
switch ($requestMethod) {

    case "POST": {
        register();
        break;
    }
    case "GET": {
        if (isset($_GET["id"]) || !empty($_GET["id"])) {
            //fetch event by id
            fetchUsersById($_GET["id"]);
        } else {
            fetchUsers();
        }
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
    //data from forms
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $middlename = $_POST["middlename"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = findUserByEmail($email);
    //manage if user already registered
    if ($result) {
        response(false, data: ["message" => "User already registered"]);
        exit;
    }

    //generate ulid 
    $id = Ulid::generate(true);

    //hash password
	$password = password_hash($password, PASSWORD_DEFAULT);
    
    $data = [
        "id" => $id,
        "firstname" => $firstname,
        "lastname" => $lastname,
        "middlename" => $middlename,
        "email" => $email,
        "password" => $password
    ];
    //perform insert to the user table
    $user = insertUser($data);
    if (!$user) {
        response(false, ["message" => "Failed to create user"]);
        exit;
    }
    response(true, ["message" => "User created succesfully!"]);
}

//fetch single user
function fetchUsersById($id)
{
    $user = findUserById($id);
    if (!$user) {
        response(false,["message" => "User not found"]);
        exit;
    }

    response(true, $user);
}
//fetch all users
function fetchUsers()
{
     //fetch all users from database
     $users = read();
     //if no users found return false
     if (!$users) {
         response(false, ["message" => "No users found"]);
         exit;
     }
    //  dd($users);
     //return all users
     response(true, ["data" =>$users]);
}