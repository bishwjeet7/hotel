<?php
include_once 'config/Database.php';
include_once 'class/Rooms.php';

$database = new Database();
$db = $database->getConnection();

$room = new Rooms($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listRooms') {
	$room->listRooms();
}


if(!empty($_POST['action']) && $_POST['action'] == 'getRoomDetails') {
	$room->id = $_POST["id"];
	$room->getRoomDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addRoom') {
	$room->room = $_POST["room"];
	$room->picture = $_FILES;
	$room->accomodations = $_POST["accomodations"];
	$room->number_of_person = $_POST["number_of_person"];
	$room->price = $_POST["price"];
	$room->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateRoom') {
	$room->id = $_POST["id"];
	$room->room = $_POST["room"];
	$room->picture = $_FILES;	
	$room->accomodations = $_POST["accomodations"];
	$room->number_of_person = $_POST["number_of_person"];
	$room->price = $_POST["price"];	
	$room->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteRoom') {
	$room->id = $_POST["id"];
	$room->delete();
} 


?>