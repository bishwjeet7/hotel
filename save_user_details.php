<?php
// Include the necessary files
include_once 'config/Database.php';
include_once 'class/Booking.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$booking = new Booking($db);

// Get the submitted form data for user details
$user->first_name = $_POST['first_name'] ?? '';
$user->last_name = $_POST['last_name'] ?? '';
$user->mobile = $_POST['phone_number'] ?? '';
$user->email = $_POST['email'] ?? '';
$booking_date = $_POST['booking_date'] ?? '';

// Get the submitted form data for reservation details
$room_id = $_POST['room_id'] ?? '';
$arrival = $_POST['arrival'] ?? '';
$departure = $_POST['departure'] ?? '';


// Save user details
$user->first_name = $first_name;
$user->last_name = $last_name;
$user->mobile = $mobile;
$user->email = $email;
$user->address = ''; // You can add an address field to the form if required
$user->role = 'user';
$user->password = ''; // You can add a password field to the form if required
$user_id = $user->saveUserDetails();

if ($user_id) {
    // Generate a confirmation code
    $confirmation_code = strtoupper(substr(md5(uniqid(rand())), 0, 10));

    // Save reservation details
    $booking->user_id = $user_id;
    $booking->confirmation_code = $confirmation_code;
    $booking->transaction_date = $booking_date;
    $booking->room_id = $room_id;
    $booking->arrival = $arrival;
    $booking->departure = $departure;
    $booking->status = 'Pending'; // Set the default status
    $booking->purpose = ''; // You can add a purpose field to the form if required
    $booking->remark = ''; // You can add a remark field to the form if required

    // Get room price
    $room_price = $booking->getRoomPrice($room_id);
    $booking->room_price = $room_price;

    if ($booking->saveReservationDetails()) {
        // Redirect to a success page or print the receipt
        header('Location: success_page.php?confirmation_code=' . $confirmation_code);
    } else {
        echo "Error saving reservation details.";
    }
} else {
    echo "Error saving user details.";
}
?>
