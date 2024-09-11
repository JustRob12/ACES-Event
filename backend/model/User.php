<?php
require_once(__DIR__ . "/../database/database.php");

use database\Database;

$userTable = "user";
$database = new Database();


function read()
{
    //access variables outside the function
    global $userTable;
    global $database;

    //query statement
    $query = "SELECT id, firstname, lastname, middlename, email, role FROM $userTable";
    //perform connection and execute
    $stmt = $database->connect()->prepare($query);
    $stmt->execute();

    //count return data rows
    $rowCount = $stmt->rowCount();
    //return null if no results
    if ($rowCount == 0) {
        return null;
    }
    //fetch results
    $result = $stmt->fetchAll();
    return $result;
}

function findUserById($id)
{
    global $userTable;
    global $database;

    $query = "SELECT * FROM $userTable WHERE id = :userId";

    //perform connection and execute
    $stmt = $database->connect()->prepare($query);

    //bind data
    $stmt->bindParam(":userId", $id);
    $stmt->execute();

    //return event
    $result = $stmt->fetch();
    return $result;
}

function findUserByEmail($email)
{
    global $userTable;
    global $database;

    $query = "SELECT * FROM $userTable WHERE email = :email";

    //perform connection and execute
    $stmt = $database->connect()->prepare($query);

    //bind data
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    //return event
    $result = $stmt->fetch();
    return $result;
}
// function insertEvent(array $events)
// {
//     global $userTable;
//     global $database;

//     $query = "INSERT INTO $userTable (id, name, description, startDate, endDate, checkIn, checkOut, banner) 
//               VALUES (:id, :name, :description, :startDate, :endDate, :checkIn, :checkOut, :banner)";

//     $stmt = $database->connect()->prepare($query);

//     // Bind values
//     $stmt->bindValue(":id", $events["id"]);
//     $stmt->bindValue(":name", $events["name"]);
//     $stmt->bindValue(":description", $events["description"]);
//     $stmt->bindValue(":startDate", $events["startDate"]);
//     $stmt->bindValue(":endDate", $events["endDate"]);
//     $stmt->bindValue(":checkIn", $events["checkIn"]);
//     $stmt->bindValue(":checkOut", $events["checkOut"]);
//     $stmt->bindValue(":banner", $events["banner"]);

//     // Execute the prepared statement
//     return $stmt->execute();
// }