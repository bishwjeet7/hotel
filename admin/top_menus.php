<h3><?php if($_SESSION["userid"]) { echo "Logged in: ".ucfirst($_SESSION["name"]); } ?> | <a href="logout.php">Logout</a> </h3><br>
<p><strong>Welcome <?php echo ucfirst($_SESSION["role"]); ?></strong></p>	
<ul class="nav nav-tabs">	
	<?php if($_SESSION["role"] == 'admin') { ?>	    
		<li id="rooms"><a href="rooms.php">Rooms</a></li>
		<li id="accomodation"><a href="accomodation.php">Accomodation</a></li>
		<li id="reservation"><a href="reservation.php">Reservation <span style="color:red;">(0)</span></a></li>
		<li id="users"><a href="users.php">Users</a></li>					
	<?php } ?>		
</ul>