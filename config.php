<?php
// Connect to the database
$mysqli = new mysqli("localhost", "root", "", "login_system");

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}