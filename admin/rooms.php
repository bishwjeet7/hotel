<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Accomodation.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$accomodation = new Accomodation($db);

if(!$user->loggedIn()) {
	header("Location: index.php");
}
include('inc/header.php');
?>
<title>phpzag.com : Demo Online Hotel Reservation System with PHP & MySQL</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/rooms.js"></script>	
<script src="js/general.js"></script>
<style>
.col-sm-6 {
	float:right;
}
div.dataTables_wrapper div.dataTables_filter {
	text-align: left;
}
</style>
<?php include('inc/container.php');?>
<div class="container" style="background-color:#f4f3ef;">  
	<h2>Online Hotel Reservation System with PHP & MySQL</h2>	
	<?php include('top_menus.php'); ?>
	<br>	
	<h4>List of rooms</h4>
	<br>	
	<div> 	
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
				<div class="col-md-2" align="right">
					<button type="button" id="addRoom" class="btn btn-info" title="Add Room"><span class="glyphicon glyphicon-plus"></span></button>
				</div>
			</div>
		</div>
		<table id="roomsListing" class="table table-bordered table-striped">
			<thead>
				<tr>						
					<th>Id</th>					
					<th>Room</th>					
					<th>Picture</th>
					<th>Accomodation</th>
					<th>Person</th>
					<th>Price</th>					
					<th></th>
					<th></th>					
				</tr>
			</thead>
		</table>
	</div>
	
	<div id="roomModal" class="modal fade">
		<div class="modal-dialog">
			<form method="post" id="roomForm" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Category</h4>
					</div>
					<div class="modal-body">						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Room <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="text" name="room" id="room" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>	
						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">picture <span class="text-danger">*</span></label>
								
								<div class="col-md-8">
									<div id="hotelPicture"></div><br>
									<input type="file" name="picture" id="picture" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Accomodation <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<select name="accomodations" id="accomodations" class="form-control">
										<option value="">--Select--</option>
										<?php 
										$accomodationResult = $accomodation->getAccomodationList();
										while ($accomodations = $accomodationResult->fetch_assoc()) { 	
										?>
											<option value="<?php echo $accomodations['id']; ?>"><?php echo $accomodations['accomodation']; ?></option>							
										<?php } ?>											
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Number of Person <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="text" name="number_of_person" id="number_of_person" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>	
						
						<div class="form-group">
							<div class="row">
								<label class="col-md-4 text-right">Price <span class="text-danger">*</span></label>
								<div class="col-md-8">
									<input type="text" name="price" id="price" autocomplete="off" class="form-control" required />
								</div>
							</div>
						</div>	
						
					</div>
					<div class="modal-footer">
						<input type="hidden" name="id" id="id" />						
						<input type="hidden" name="action" id="action" value="" />
						<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</form>
		</div>
	</div>
			
</div>
 <?php include('inc/footer.php');?>
