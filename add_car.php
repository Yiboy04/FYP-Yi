<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

$seller_id = $_SESSION['seller_id']; // make sure you set seller_id in login
$make = $_POST['make'];
$model = $_POST['model'];
$year = $_POST['year'];
$engine_capacity = $_POST['engine_capacity'];
$transmission = $_POST['transmission'];
$color = $_POST['color'];
$price = $_POST['price'];

// Insert car info
$stmt = $mysqli->prepare("INSERT INTO cars (seller_id, make, model, year, engine_capacity, transmission, color, price) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississss", $seller_id, $make, $model, $year, $engine_capacity, $transmission, $color, $price);
$stmt->execute();
$car_id = $stmt->insert_id;
$stmt->close();

// Handle multiple images
foreach ($_FILES['car_images']['tmp_name'] as $key => $tmp_name) {
    if (!empty($tmp_name)) {
        $file_name = basename($_FILES['car_images']['name'][$key]);
        $target_path = "uploads/" . time() . "_" . $file_name;

        if (move_uploaded_file($tmp_name, $target_path)) {
            $stmt = $mysqli->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $car_id, $target_path);
            $stmt->execute();
        }
    }
}

header("Location: seller_main.php?msg=Car+Added");
exit();
?>
