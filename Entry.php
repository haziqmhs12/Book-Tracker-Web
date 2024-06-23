<?php
session_start(); // Start or resume the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
  header("Location: login.php"); // Redirect to login if not logged in
  exit();
}

  // Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    include 'config.php';// Include database connection


    // Prepare data for insertion
    $title = $_POST['Title'];
    $author = $_POST['Author'];
    $rating = $_POST['Rating'];
    $summary = $_POST['message'];
    $user_id = $_SESSION['id']; // Assuming you have stored user ID in session
    $image_url = "icons/avatar_book-sm.png"; // Default image URL

    // Insert into `books` table with default image_url
    $stmt = $mysqli->prepare("INSERT INTO books (title, authors, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $author, $image_url);
    $stmt->execute();
    $book_id = $mysqli->insert_id; // Get the book ID

    // Insert into `user_books` table
    $stmt = $mysqli->prepare("INSERT INTO user_books (book_id, user_id, summary, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $book_id, $user_id, $summary, $rating);
  if ($stmt->execute()) {
    $_SESSION['success_message'] = "Book entry submitted successfully!";
  } else {
    $error = "Error: " . $stmt->error;
  }

    // Close connection
    $stmt->close();
    $mysqli->close();
    // echo '<script>showPopup();</script>';
    // Redirect to a success page or show a success message
    header("Location: Entry.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Form</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="Entry.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  
  <link
    href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet"
  />
  
  <style>
    .popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .popup.show {
      display: block;
    }

    .popup .close-btn {
      display: block;
      text-align: right;
      margin-top: 10px;
    }

    .popup .close-btn button {
      padding: 5px 10px;
    }

    .popup p {
      color: green;
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    .overlay.show {
      display: block;
    }
  </style>
</head>
<body>
  <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4 px-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Book</a>
                <button class="navbar-toggler collapsed " type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link " href="Search.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Add Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" id="title" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success fetchBtn" type="submit">Search</button>
                </form> -->
                </div>
            </div>
        </nav>
    </header>

  <div class="container">
    <div class="contact-header">
      <h2>BOOK ENTRY</h2>
      <h1>Share your reading experience</h1>
    </div>
    <?php
    if (isset($_SESSION['success_message'])) {
      echo '<div class="popup show" id="popup">';
      echo '<p>' . $_SESSION['success_message'] . '</p>';
      echo '<div class="close-btn">';
      echo '<button onclick="closePopup()">Close</button>';
      echo '</div>';
      echo '</div>';
      // Unset the session variable to prevent displaying the message on refresh
      unset($_SESSION['success_message']);
    }
    ?>
    <div class="contact-form">
      <div class="form-container">
        <form id="contactForm" method="post" action="Entry.php">
          <input
            type="text"
            id="name"
            name="Title"
            placeholder="Book Title"
            required
            oninput="capitalizeFirstLetter('name')"
          />
          <input
            type="text"
            id="author"
            name="Author"
            placeholder="Book Author"
            required
            oninput="capitalizeFirstLetter('author')"
          />
          <select id="rating" name="Rating" required>
            <option value="" disabled selected>Give Your Rating</option>
            <option value="1">1-Star</option>
            <option value="2">2-Star</option>
            <option value="3">3-Star</option>
            <option value="4">4-Star</option>
            <option value="5">5-Star</option>
          </select>

          <textarea
            id="message"
            name="message"
            placeholder="Your thoughts on this book"
            required
            oninput="properGrammar()"
          ></textarea>
          <button type="submit">SUBMIT</button>
        </form>
      </div>
    </div>
  </div>
  <div class="overlay" id="overlay"></div>
  <div class="popup" id="popup">
    <p>Entry successfully submitted!</p>
    <div class="close-btn">
      <button onclick="closePopup()">Close</button>
    </div>
  </div>
  <script>function closePopup() {
      document.getElementById('popup').classList.remove('show');
      document.getElementById('overlay').style.display = 'none';
    }</script>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
