<?php
use Ulid\Ulid;
require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../model/User.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

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
    // $firstname = $_POST["firstname"];
    // $lastname = $_POST["lastname"];
    // $middlename = $_POST["middlename"];
    // $email = $_POST["email"];
    // $password = $_POST["password"];

    $firstname = "User";
    $lastname = "Admin";
    $middlename = "";
    $email = "admin@gmail.com";
    $password = "defaultAdmin";

    $result = findUserByEmail($email);

    if ($result) {
        response(false, data: ["message" => "User already registered"]);
        exit;
    }

    //generate ulid 
    $id = Ulid::generate(true);

    //hash password
    	//default password
	$password = password_hash($password, PASSWORD_DEFAULT);

    $data = [
        "id" => $id,
        "firstname" => $firstname,
        "lastname" => $lastname,
        "middlename" => $middlename,
        "email" => $email,
        "password" => $password
    ];

    $user = insertUser($data);
    if (!$user) {
        response(false, ["message" => "Failed to create user"]);
        exit;
    }
    response(true, ["message" => "User created succesfully!"]);
}

function fetchUsersById($id)
{
    $user = findUserById($id);
    if (!$user) {
        response(false,["message" => "User not found"]);
        exit;
    }

    response(true, $user);
}
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