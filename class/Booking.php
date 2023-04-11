<?php
class Booking {	
   
    private $accomodationTable = 'hotel_accomodation';	
	private $roomTable = 'hotel_room';
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }
	
	function addToCart(){
			
		if($this->room_id < 1 or $this->days < 1) return;
		if (!empty($_SESSION['booking_cart'])){
			if(is_array($_SESSION['booking_cart'])){
				if($this->itemExists()) return;
				$max=count($_SESSION['booking_cart']);
				$_SESSION['booking_cart'][$max]['bookingroomid'] = $this->room_id; 
				$_SESSION['booking_cart'][$max]['bookingday'] = $this->days; 
				$_SESSION['booking_cart'][$max]['bookingroomprice'] = $this->total_price;
				$_SESSION['booking_cart'][$max]['bookingcheckin'] = $_SESSION['arrival'];
				$_SESSION['booking_cart'][$max]['bookingcheckout'] = $_SESSION['departure']; 				
			}else{
				$_SESSION['booking_cart']=array();
				$_SESSION['booking_cart'][0]['bookingroomid'] = $this->room_id; 
				$_SESSION['booking_cart'][0]['bookingday'] = $this->days; 
				$_SESSION['booking_cart'][0]['bookingroomprice'] = $this->total_price;
				$_SESSION['booking_cart'][0]['bookingcheckin'] = $_SESSION['arrival'];
				$_SESSION['booking_cart'][0]['bookingcheckout'] = $_SESSION['departure'];				
			}
		} else {
			$_SESSION['booking_cart']=array();
			$_SESSION['booking_cart'][0]['bookingroomid'] = $this->room_id; 
			$_SESSION['booking_cart'][0]['bookingday'] = $this->days; 
			$_SESSION['booking_cart'][0]['bookingroomprice'] = $this->total_price;
			$_SESSION['booking_cart'][0]['bookingcheckin'] = $_SESSION['arrival'];
			$_SESSION['booking_cart'][0]['bookingcheckout'] = $_SESSION['departure'];			
		}
	}
	function removeFromCart(){
		$pid = intval($this->room_id);
		$max = count($_SESSION['booking_cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['booking_cart'][$i]['bookingroomid']){
				unset($_SESSION['booking_cart'][$i]);
				break;
			}
		}
		$_SESSION['booking_cart'] = array_values($_SESSION['booking_cart']);
	}
	
	function itemExists(){
		$pid = intval($this->room_id);
		$max = count($_SESSION['booking_cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['booking_cart'][$i]['bookingroomid']){
				$flag=1;
				echo "Item is already in the cart.";
				break;
			}
		}
		return $flag;
	}	
}
?>