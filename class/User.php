<?php
class User
{
    private $conn;

    public $id;
    public $first_name;
    public $last_name;
    public $gender;
    public $email;
    public $password;
    public $mobile;
    public $address;
    public $created;
    public $role;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function saveUserDetails()
    {
        $query = "INSERT INTO hotel_user (first_name, last_name, gender, email, password, mobile, address, created, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssss", $this->first_name, $this->last_name, $this->gender, $this->email, $this->password, $this->mobile, $this->address, $this->created, $this->role);

        $this->created = date('Y-m-d H:i:s');
        $this->gender = 'Male'; // You can add a gender field to the form if required

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }
}
?>
