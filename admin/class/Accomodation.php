<?php
class Accomodation {	
   
    private $accomodationTable = 'hotel_accomodation';	
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function listAccomodation(){			
		
		$sqlQuery = "
			SELECT id, accomodation, description
			FROM ".$this->accomodationTable." ";
						
		if(!empty($_POST["order"])){
			$sqlQuery .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= ' ORDER BY id ASC ';
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
	
		while ($accomodation = $result->fetch_assoc()) { 				
			$rows = array();			
			$rows[] = $accomodation['id'];			
			$rows[] = $accomodation['accomodation'];
			$rows[] = $accomodation['description'];
			$rows[] = '<button type="button" name="update" id="'.$accomodation["id"].'" class="btn btn-warning btn-xs update"><span class="glyphicon glyphicon-edit" title="Edit"></span></button>';			
			$rows[] = '<button type="button" name="delete" id="'.$accomodation["id"].'" class="btn btn-danger btn-xs delete" ><span class="glyphicon glyphicon-remove" title="Delete"></span></button>';
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
	
	public function getAccomodationDetails(){
		if($this->id && $_SESSION["userid"]) {			
					
			$sqlQuery = "
			SELECT id, accomodation, description
			FROM ".$this->accomodationTable." WHERE id = ? ";	
					
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();				
			$records = array();		
			while ($accomodation = $result->fetch_assoc()) { 				
				$rows = array();	
				$rows['id'] = $accomodation['id'];				
				$rows['accomodation'] = $accomodation['accomodation'];				
				$rows['description'] = $accomodation['description'];					
				$records[] = $rows;
			}		
			$output = array(			
				"data"	=> 	$records
			);
			echo json_encode($output);
		}
	}
	
	
	public function insert(){
		
		if($this->accomodationName && $_SESSION["userid"]) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->accomodationTable."(`accomodation`, `description`)
				VALUES(?, ?)");
		
			$this->accomodationName = htmlspecialchars(strip_tags($this->accomodationName));
			$this->description = htmlspecialchars(strip_tags($this->description));
			
			$stmt->bind_param("ss", $this->accomodationName, $this->description);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}
	
	public function update(){
		
		if($this->id && $this->accomodationName && $_SESSION["userid"]) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->accomodationTable." 
			SET accomodation = ?, description = ?
			WHERE id = ?");
	 
			$this->accomodationName = htmlspecialchars(strip_tags($this->accomodationName));
			$this->description = htmlspecialchars(strip_tags($this->description));
								
			$stmt->bind_param("ssi", $this->accomodationName, $this->description, $this->id);
			
			if($stmt->execute()){				
				return true;
			}			
		}	
	}	
	
	
	public function delete(){
		if($this->id && $_SESSION["userid"]) {			

			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->accomodationTable." 
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){				
				return true;
			}
		}
	} 
	
	function getAccomodationList(){		
		$stmt = $this->conn->prepare("
		SELECT id, accomodation, description
		FROM ".$this->accomodationTable);				
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
}
?>