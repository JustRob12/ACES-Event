<?php
use Ulid\Ulid;

require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../model/Event.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//handle request
switch ($requestMethod) {

    case "GET": {
        if (isset($_GET["id"]) || !empty($_GET["id"])) {
            //fetch event by id
            fetchEventsById($_GET["id"]);
        } else {
            fetchEvents();
        }
        break;
    }
    case "POST" : {
       
       if(isset($_POST["method"])){
        if($_POST["method"] === "PATCH"){
            editEvent($_GET["id"]);
        }else if($_POST["method"] === "DELETE"){

        }
       }
       else{
        insertEvent();
       }
        break;

    }default: {
        $responseMessage = "Request method: {$requestMethod} not allowed!";
        response(false, ["message" => $responseMessage]);
        break;
    }


}

//perform fetch all events
function fetchEvents()
{
    //fetch all events from database
    $events = read();
    //if no events found return false
    if (!$events) {
        response(false, ["message" => "No events found"]);
        exit;
    }
    //return all events
    response(true, ["data" => $events]);
}

//perform fetch single event
function fetchEventsById($id){
    $event = findEventById($id);
    if (!$event) {
        response(false,["message" => "Event not found"]);
        exit;
    }

    response(true, $event);
}

function insertEvent(){

    $id = Ulid::generate(true);
    $name = $_POST["name"];
    $description = $_POST["description"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $checkIn = $_POST["checkIn"];
    $checkOut = $_POST["checkOut"];
    $banner = $_POST["banner"];
    $data = ["id" => $id, "name" => $name, "description" => $description, "startDate"=> $startDate, "endDate"=> $endDate,
    "checkIn" => $checkIn, "checkOut" => $checkOut,  "banner" => $banner];

    $event = createEvent($data);

    if(!$event){
        response(false,["message" => "Failed to create Event"]);
        exit;
    }
    response(true, ["message" => "Event Created"]);
        
}

function editEvent($id){

   
    $name = $_POST["name"];
    $description = $_POST["description"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $checkIn = $_POST["checkIn"];
    $checkOut = $_POST["checkOut"];
    $banner = $_POST["banner"];

    $data = ["name" => $name, "description" => $description, "startDate"=> $startDate, "endDate"=> $endDate,
    "checkIn" => $checkIn, "checkOut" => $checkOut,  "banner" => $banner];

    $event = updateEvent($id,  $data);

    if(!$event){
        response(false,["message" => "Failed to Update Event"]);
        exit;
    }
    response(true, ["message" => "Event Updated"]);


}


function removeEvent($id){

    $event = deleteEvent($id);

    if(!$event){
        response(false,["message" => "Failed to Delete Event"]);
        exit;
    }
    response(true, ["message" => "Event Removed"]);
}