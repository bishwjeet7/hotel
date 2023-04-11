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
			SELECT rooms.id, rooms.room_number, rooms.room, rooms.description, rooms.number_person, rooms.price, rooms.picture, accomodation.accomodation
			FROM  ".$this->roomTable." rooms
			LEFT JOIN ".$this->accomodationTable." accomodation ON rooms.accomodation_id = accomodation.id";	
		
		$stmt = $this->conn->prepare($sqlQuery);		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	public function getRoomDetails() {
		if($this->room_id) {
			$sqlQuery = "
				SELECT id, room_number, room, description, number_person, price, picture
				FROM  ".$this->roomTable." 
				WHERE id = ?";			
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->room_id);				
			$stmt->execute();			
			$result = $stmt->get_result();		
			return $result;	
		}		
	}
	
	
}
?>