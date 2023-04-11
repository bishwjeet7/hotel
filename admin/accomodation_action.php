<?php
include_once 'config/Database.php';
include_once 'class/Accomodation.php';

$database = new Database();
$db = $database->getConnection();

$accomodation = new Accomodation($db);

if(!empty($_POST['action']) && $_POST['action'] == 'listAccomodation') {
	$accomodation->listAccomodation();
}


if(!empty($_POST['action']) && $_POST['action'] == 'getAccomodationDetails') {
	$accomodation->id = $_POST["id"];
	$accomodation->getAccomodationDetails();
}

if(!empty($_POST['action']) && $_POST['action'] == 'addAccomodation') {
	$accomodation->accomodationName = $_POST["accomodationName"];
	$accomodation->description = $_POST["description"];
	$accomodation->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'updateAccomodation') {
	$accomodation->id = $_POST["id"];
	$accomodation->accomodationName = $_POST["accomodationName"];
	$accomodation->description = $_POST["description"];	
	$accomodation->update();
}

if(!empty($_POST['action']) && $_POST['action'] == 'deleteAccomodation') {
	$accomodation->id = $_POST["id"];
	$accomodation->delete();
} 
?>