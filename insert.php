<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 // Start the session

// Retrieve JSON data from POST request
$data = json_decode(file_get_contents('php://input'), true);

// Get userID from session
// $userID = $_SESSION['id'];

// Validate the received data
if (empty($data['title']) || empty($data['authors']) || empty($data['imageSrc']) || empty($data['userID'])) {
    echo json_encode(array('error' => 'Invalid input data'));
    exit();
}

$conn = new mysqli("localhost", "root", "", "login_system");

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
}

// Prepare SQL statement to insert data into a table
$stmt = $conn->prepare("INSERT INTO books (title, authors, image_url, user_id) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die(json_encode(array('error' => 'Prepare failed: ' . $conn->error)));
}

$stmt->bind_param("sssi", $data['title'], $data['authors'], $data['imageSrc'],$data['userID']);

// Execute SQL statement
if ($stmt->execute()) {
    echo json_encode(array('message' => 'Data added successfully'));
} else {
    echo json_encode(array('error' => 'Execute failed: ' . $stmt->error));
}

// Close connections
$stmt->close();
$conn->close();
?>
