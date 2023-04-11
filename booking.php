<?php 
include_once 'config/Database.php';
include_once 'class/Booking.php';
include_once 'class/Rooms.php';

$database = new Database();
$db = $database->getConnection();

$booking = new Booking($db);
$rooms = new Rooms($db);

include('inc/header4.php');

$_SESSION['arrival'] = date("Y/m/d");
$_SESSION['departure'] =  date("Y/m/d");

if(isset($_GET['view']) && $_GET['view'] == 'process_cart' && isset($_GET['id'])){	
	$booking->room_id = $_GET['id'];
    $booking->removeFromCart();
}

if (isset($_POST['emptyCart'])){
   unset($_SESSION['pay']);
   unset($_SESSION['booking_cart']);   
}

if(isset($_POST['book_now'])){	
	$days = 0;
	$totalPrice = 0;
	if($days <= 0){
		$totalPrice = $_POST['room_price'] *1;
		$days = 1;
	} else {
		$totalPrice = $_POST['room_price'] * $days;
		$days = $days;
	}
	$booking->room_id = $_POST['room_id'];
	$booking->days = $days;
	$booking->total_price = $totalPrice;	
	$booking->addToCart();
}
?>
<title>phpzag.com : Demo Online Hotel Reservation System with PHP & MySQL</title>
<link rel="stylesheet" type="text/css" href="styles/bootstrap-4.1.2/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/custom-navbar.css">
<link rel="stylesheet" type="text/css" href="styles/bootstrap.css">
<script src="js/general.js"></script>
<?php include('inc/container4.php');?>
<div class="container">		
	<div class="row">
		<div class="col">
			<div class="row">   
				<?php include('menus.php');?>
			</div>
			<div class="row">   
				<h1>Your Booking Cart</h1> 
			</div> 
			<div class="row">   
				<table class="table" id="table">
					<thead>
					<tr  bgcolor="#999999">				
						<th align="center" width="120">Room</th>
						<th align="center" width="120">Check In</th>
						<th align="center" width="120">Check Out</th> 
						<th  width="120">Price</th> 
						<th align="center" width="120">Nights</th> 
						<th align="center" >Amount</th>
						<th align="center" width="90">Action</th> 
					</tr> 
					</thead>				
					<tbody>
						<?php
						$payable = 0;
						if (isset( $_SESSION['booking_cart'])){
							$cartCount = count($_SESSION['booking_cart']);
							for ($i=0; $i < $cartCount  ; $i++) {								
								$rooms->room_id = $_SESSION['booking_cart'][$i]['bookingroomid'];
								$roomsResult = $rooms->getRoomDetails();
								while ($room = $roomsResult->fetch_assoc()) { 				
								?>
								<tr>
									<td><?php echo $room['description']; ?></td>
									<td><?php echo date_format(date_create($_SESSION['booking_cart'][$i]['bookingcheckin']),"m/d/Y"); ?></td>
									<td><?php echo date_format(date_create($_SESSION['booking_cart'][$i]['bookingcheckin']),"m/d/Y"); ?></td>
									<td>$<?php echo $room['price']; ?></td>
									<td><?php echo $_SESSION['booking_cart'][$i]['bookingday']; ?></td>
									<td><?php echo $_SESSION['booking_cart'][$i]['bookingroomprice']; ?></td>
									<td><a href="booking.php?view=process_cart&id=<?php echo $room['id']; ?>">Remove</a></td>
								</tr>
						<?php 
								}
								$payable += $_SESSION['booking_cart'][$i]['bookingroomprice'];
							}
							$_SESSION['pay'] = $payable;
						} 
						?>
					</tbody>				
					<tfoot>
						<tr>
							<td colspan="6"><h4 align="right">Total:</h4></td>
							<td colspan="4">
								<h4><b><span id="sum"><?php  echo isset($_SESSION['pay']) ?  '$'.$_SESSION['pay'] :'Cart is empty.';?></span></b></h4>
							</td>
						</tr>
					</tfoot> 				
				</table>				
			</div>
			
			<form method="post" action="">
				<div class="row" >
				<?php
				if (isset($_SESSION['booking_cart'])){
				?> 
					<button type="submit" class="button" name="emptyCart">Clear Cart</button> 
					<?php

					if (isset($_SESSION['GUESTID'])){
					?>
						<div  class="button"><a href="booking.php?view=payment" name="continue">Continue Booking</a></div>
					<?php 
					} else { ?>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userDetailsModal">
  						Check Out
						</button>
					<?php
					}
				}
				?>
				</div>
			</form>				
		</div>
	</div>
</div>
<div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userDetailsModalLabel">Enter your details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="save_user_details.php">
        <div class="modal-body">
          <!-- Add your form fields here -->
          <!-- Add room_id as a hidden input field in the form -->
          <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
          </div>
          <div class="form-group">
            <label for="phone_number">Phone number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
          </div>
          <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="booking_date">Booking Date</label>
            <input type="date" class="form-control" id="booking_date" name="booking_date" required>
          </div>
          <!-- Add arrival, departure, and other necessary fields to the form in the modal -->
          <div class="form-group">
            <label for="arrival">Arrival Date</label>
            <input type="date" class="form-control" id="arrival" name="arrival" required>
          </div>
          <div class="form-group">
            <label for="departure">Departure Date</label>
            <input type="date" class="form-control" id="departure" name="departure" required>
          </div>
          <!-- Add other necessary fields -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save details</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php include('inc/footer4.php');?>