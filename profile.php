<?php
session_start(); // Start or resume the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch logged-in user's profile data
include 'config.php'; // Assuming this file connects to your database

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Query to fetch user profile data
    $sql_user = "SELECT username, email, created_at FROM users WHERE id = $user_id";
    $result_user = $mysqli->query($sql_user);

    if ($result_user->num_rows > 0) {
        $user_row = $result_user->fetch_assoc();
        $username = $user_row['username'];
        $email = $user_row['email'];
        $created_at = $user_row['created_at']; // Assuming this column exists in your users table
    } else {
        // Handle case where user profile data is not found
        $username = "Unknown";
        $email = "N/A";
        $created_at = "N/A";
    }
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}
// Query to count the number of books read by the logged-in user
$sql_count_books_read = "SELECT COUNT(*) AS books_read FROM user_books WHERE user_id = $user_id";
$result_count_books_read = $mysqli->query($sql_count_books_read);

if ($result_count_books_read->num_rows > 0) {
    $row_count_books_read = $result_count_books_read->fetch_assoc();
    $books_read = $row_count_books_read['books_read'];
} else {
    $books_read = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Book Tracker Profile</title>

    <style>
        body {
            background-color: #212529;
            color: white;
        }
        .col {
            background-color: white;
        }
        .card-title {
            white-space: normal;
            /* Allows text to wrap */
            word-wrap: break-word;
            /* Breaks long words to wrap */
            overflow-wrap: break-word;
            /* Ensures long words break correctly */
            hyphens: auto;
            /* Adds hyphens where necessary for long words */
        }

        .card-img-top {
            width: 100%;
            height: 300px;
            /* Set a fixed height */
            object-fit: cover;
            /* Ensures the image covers the element without stretching */
            object-fit: fill;
        }


        p {
            word-wrap: break-word;
            /* Ensures long words break to the next line */
            white-space: normal;
            /* Ensures normal wrapping of text */
        }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
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
                        <a class="nav-link "  href="Search.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
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

    <main>
        <!-- referecnce: https://www.bootdey.com/snippets/view/profile-with-data-and-skills -->
        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mt-5 mb-3">
                        <div class="card ">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User" class="rounded-circle" width="150">
                                    <div class="mt-3">
                                        <h4><?php echo $username; ?></h4>
                                        <p class="text-secondary mb-1">Username :<?php echo $username; ?> </p>
                                        <p class="text-muted font-size-sm">Email: <?php echo $email; ?></p>
                                        <p class="text-muted font-size-sm">Member Since: <?php echo date('F j, Y', strtotime($created_at)); ?></p>
                                        <p class="text-muted font-size-sm">Number of book read: <?php echo $books_read; ?></p>
                                        <!-- <button class="btn btn-primary">Follow</button>
                                        <button class="btn btn-outline-primary">Message</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h2 class="font-weight-light text-center">List of books</h2>
                        <div id="bookList" class="d-flex flex-row flex-nowrap overflow-auto " data-bs-smooth-scroll="true" data-bs-spy="scroll">
                            <?php include 'fetch_books.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </main>
</body>

<script>
    const bookList = document.getElementById('bookList');

    bookList.addEventListener('wheel', function(event) {
        if (event.deltaY > 0) {
            behavior: "smooth"
            bookList.scrollLeft += 50;
        } else {
            behavior: "smooth"
            bookList.scrollLeft -= 50;
        }
    });
</script>   
