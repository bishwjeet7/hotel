<?php
include_once 'config/Database.php';
include_once 'class/Booking.php';

$database = new Database();
$db = $database->getConnection();

$booking = new Booking($db);

if (isset($_GET['confirmation_code'])) {
  $confirmation_code = $_GET['confirmation_code'];
  $booking->confirmation_code = $confirmation_code;
  $bookingDetails = $booking->getBookingDetailsByConfirmationCode();
}

include('inc/header4.php');
?>

<!-- Add your receipt HTML and PHP code here -->
<div class="container">
  <div class="row">
    <div class="col">
      <h1>Booking Receipt</h1>
      <?php
      if ($bookingDetails->num_rows > 0) {
        while ($row = $bookingDetails->fetch_assoc()) {
          echo "Confirmation Code: " . $row['confirmation_code'] . "<br>";
          echo "Transaction Date: " . $row['transaction_date'] . "<br>";
          echo "Check-in Date: " . $row['arrival'] . "<br>";
          echo "Check-out Date: " . $row['departure'] . "<br>";
          echo "Price: $" . $row['room_price'] . "<br>";
          // Add other details as required
        }
      } else {
        echo "No booking found with the given confirmation code.";
      }
      ?>
    </div>
  </div>
</div>

<?php include('inc/footer4.php');?>
