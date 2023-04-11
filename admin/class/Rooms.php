<?php
class Rooms {	
   
    private $accomodationTable = 'hotel_accomodation';	
	private $roomTable = 'hotel_room';
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listRooms(){			
		
		$sqlQuery = "
			SELECT rooms.id, rooms.room_number, rooms.room, rooms.number_person, rooms.price, rooms.picture, accomodation.accomodation
			FROM  ".$this->roomTable." rooms
			LEFT JOIN ".$this->accomodationTable." accomodation ON rooms.accomodation_id = accomodation.id ";
						
		if(!empty($_POST["order"])){
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY rooms.id ASC ';
		}
				
		if($_POST["length"] != -1){
			$sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();	
		
		$stmtTotal = $this->conn->prepare($sqlQuery);
		$stmtTotal->execute();
		$allResult = $stmtTotal->get_result();
		$allRecords = $allResult->num_rows;
		
		$displayRecords = $result->num_rows;
		$records = array();	
	
		while ($room = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $room['id'];			
			$rows[] = $room['room'];
			$rows[] = "<img src='../images/".$room['picture']."' width='60' height='60'>";
			$rows[] = $room['accomodation'];
			$rows[] = $room['number_person'];
			$rows[] = "$".$room['price'];
			$rows[] = '<button type="button" name="update" id="'.$room["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$room["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
			$records[] = $rows;
		}
		
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$displayRecords,
			"iTotalDisplayRecords"	=>  $allRecords,
			"data"	=> 	$records
		);
		
		echo json_encode($output);
	}
	
	public function getRoomDetails(){
		if($this->id && $_SESSION["userid"]) {						
			
			$sqlQuery = "
			SELECT rooms.id, rooms.room_number, rooms.room, rooms.number_person, rooms.price, rooms.picture, accomodation.id AS accomodationId
			FROM  ".$this->roomTable." rooms
			LEFT JOIN ".$this->accomodationTable." accomodation ON rooms.accomodation_id = accomodation.id 
			WHERE rooms.id = ?";
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($rooms = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['id'] = $rooms['id'];				
				$rows['room'] = $rooms['room'];	
				$rows['picture'] = $rooms['picture'];				
				$rows['accomodationId'] = $rooms['accomodationId'];
				$rows['number_person'] = $rooms['number_person'];				
				$rows['price'] = $rooms['price'];				
				$records[] = $rows;
			}		
			$output = array(			
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
	
	
	public function insert(){		
		if($this->room && $_SESSION["userid"]) {			
			$fileName = '';
			$fileName = $this->uploadpicture();			
			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->roomTable."(`room`, `accomodation_id`, `number_person`, `price`, `picture`)
				VALUES(?, ?, ?, ?, ?)");
		
			$this->room = htmlspecialchars(strip_tags($this->room));
			$this->accomodations = htmlspecialchars(strip_tags($this->accomodations));
			$this->number_of_person = htmlspecialchars(strip_tags($this->number_of_person));
			$this->price = htmlspecialchars(strip_tags($this->price));
			
			$stmt->bind_param("siiss", $this->room, $this->accomodations, $this->number_of_person, $this->price, $fileName);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){		
		if($this->id && $this->room && $_SESSION["userid"]) {
			
			$fileName = $this->uploadpicture();
			$updateField = '';
			if($fileName) {
				$updateField = ", picture = '".$fileName."'";
			}
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->roomTable." 
			SET room = ?, accomodation_id = ?, number_person = ?, price = ? $updateField
			WHERE id = ?");
	 
			$this->room = htmlspecialchars(strip_tags($this->room));
			$this->accomodations = htmlspecialchars(strip_tags($this->accomodations));
			$this->number_of_person = htmlspecialchars(strip_tags($this->number_of_person));
			$this->price = htmlspecialchars(strip_tags($this->price));
			
						
			$stmt->bind_param("siisi", $this->room, $this->accomodations, $this->number_of_person, $this->price, $this->id);			
						
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	
	public function delete(){
		if($this->id && $_SESSION["userid"]) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->roomTable." 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){				
				return true;
			}
		}
	} 
	
	function uploadpicture () {		
		if($_FILES['picture']['name'] != ''){
			$test = explode('.', $_FILES['picture']['name']);
			$extension = end($test);    
			$name = rand(100,999).'.'.$extension;

			$location = 'C:/xampp/htdocs/phpzag_demo/hotel-reservation-system-php-mysql/images/'.$name;
			move_uploaded_file($_FILES['picture']['tmp_name'], $location);
			return $name;			
		} 
	}
}
?>