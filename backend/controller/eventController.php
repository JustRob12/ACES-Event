<?php
require_once(__DIR__ . "/../util/header.php");
require_once(__DIR__ . "/../model/Event.php");

//fetch request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

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
    case "POST": {
        //perform post requests
    }

}


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
    response(true, $events);
}

function fetchEventsById($id){
    $event = findEventById($id);
    if (!$event) {
        response(false,["message" => "Event not found"]);
        exit;
    }

    response(true, $event);
}
