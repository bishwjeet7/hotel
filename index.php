<?php 
include_once 'config/Database.php';
include_once 'class/Rooms.php';

$database = new Database();
$db = $database->getConnection();

$rooms = new Rooms($db);

include('inc/header4.php');

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
			<?php include('menus.php');?>			
			<div class="card-columns">
			<?php 
			$roomsResult = $rooms->listRooms();
			while ($room = $roomsResult->fetch_assoc()) { 	
			?>						  
				<form method="POST" action="booking.php">
					<input type="hidden" name="room_price" value="<?php echo $room['price']; ?>">
					<input type="hidden" name="room_id" value="<?php  echo $room['id']; ?>">
					<div class="card">
						<img class="card-img-top"  src="images/<?php echo $room['picture']; ?>" alt="Room image description">
						<div class="card-body">
							<div class="rooms_title"><h2><?php echo $room['room']; ?> <?php echo $room['accomodation']; ?></h2></div>
							<div class="rooms_text">
								<p><?php echo $room['description']; ?></p>
							</div>
							<div class="rooms_list">
								<ul>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<img src="images/check.png" alt="">
										<span>Number of Person : <?php echo $room['number_person']; ?></span>
									</li> 
									<li class="d-flex flex-row align-items-center justify-content-start">
										<img src="images/check.png" alt="">
										<span>Remaining Rooms :<?php echo $room['room_number']; ?></span>
									</li>
								</ul>
							</div>
							<div class="rooms_price">$<?php echo $room['price']; ?>/<span>Night</span></div>
							<div class="form-group">
								<div class="row">
								<div class="col-xs-12 col-sm-12">
								<input type="submit" class="button rooms_button"  id="book_now" name="book_now" value="Book Now!"/>                         
								</div>
								</div>
							</div>
						</div>
					</div> 
				</form>			
			<?php } ?>
			</div> 		
		</div>
	</div>
</div>
<?php include('inc/footer4.php');?>