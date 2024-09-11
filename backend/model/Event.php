<?php
require_once(__DIR__ . "/../database/database.php");

use database\Database;

$eventTable = "event";
$database = new Database();


function read()
{
    //access variables outside the function
    global $eventTable;
    global $database;

    //query statement
    $query = "SELECT id, name, startDate, endDate, status FROM $eventTable";
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

function findEventById($id)
{
    global $eventTable;
    global $database;

    $query = "SELECT * FROM $eventTable WHERE id = :eventId";

    //perform connection and execute
    $stmt = $database->connect()->prepare($query);

    //bind data
    $stmt->bindParam(":eventId", $id);
    $stmt->execute();

    //return event
    $result = $stmt->fetch();
    return $result;
}
function createEvent(array $events)
{
    global $eventTable;
    global $database;

    $query = "INSERT INTO $eventTable (id, name, description, startDate, endDate, checkIn, checkOut, banner) 
              VALUES (:id, :name, :description, :startDate, :endDate, :checkIn, :checkOut, :banner)";

    $stmt = $database->connect()->prepare($query);

    // Bind values
    $stmt->bindValue(":id", $events["id"]);
    $stmt->bindValue(":name", $events["name"]);
    $stmt->bindValue(":description", $events["description"]);
    $stmt->bindValue(":startDate", $events["startDate"]);
    $stmt->bindValue(":endDate", $events["endDate"]);
    $stmt->bindValue(":checkIn", $events["checkIn"]);
    $stmt->bindValue(":checkOut", $events["checkOut"]);
    $stmt->bindValue(":banner", $events["banner"]);

    // Execute the prepared statement
    return $stmt->execute();
}

function updateEvent($id, array $events)
{
    global $eventTable;
    global $database;


    $query = "UPDATE $eventTable 
                SET name = :name, description= :description, startDate = :startDate, endDate = :endDate, checkIn = :checkIn, checkOut = :checkOut, banner = :banner 
                WHERE id = :id";

    $stmt = $database->connect()->prepare($query);

    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":name", $events["name"]);
    $stmt->bindValue(":description", $events["description"]);
    $stmt->bindValue(":startDate", $events["startDate"]);
    $stmt->bindValue(":endDate", $events["endDate"]);
    $stmt->bindValue(":checkIn", $events["checkIn"]);
    $stmt->bindValue(":checkOut", $events["checkOut"]);
    $stmt->bindValue(":banner", $events["banner"]);

    return $stmt->execute();



}

function deleteEvent($id)
{

    global $eventTable;
    global $database;


    $query = "DELETE from $eventTable WHERE id = :id";

    $stmt = $database->connect()->prepare($query);


    $stmt->bindValue(":id", $id);

    return $stmt->execute();


}