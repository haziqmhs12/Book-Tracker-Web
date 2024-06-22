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



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    // Handle the delete book request
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['id'];

    // Prepared statement to delete the book entry for the user
    $stmt = $mysqli->prepare("DELETE FROM user_books WHERE book_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $book_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    exit(); // Exit to prevent the rest of the page from loading
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && isset($_POST['book_id'])) {
    $rating = $_POST['rating'];
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['id'];

    // Check if summary is provided
    if (isset($_POST['summary'])) {
        $summary = $_POST['summary'];
        // Prepared statement to update both summary and rating
        $stmt = $mysqli->prepare("UPDATE user_books SET summary = ?, rating = ? WHERE book_id = ? AND user_id = ?");
        $stmt->bind_param("siii", $summary, $rating, $book_id, $user_id);
    } else {
        // Prepared statement to update only rating
        $stmt = $mysqli->prepare("UPDATE user_books SET rating = ? WHERE book_id = ? AND user_id = ?");
        $stmt->bind_param("iii", $rating, $book_id, $user_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    exit(); // Exit to prevent the rest of the page from loading
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

        .checked {
            color: orange;
        }

        .dropdown-menu {
            min-width: 150px;
            /* Adjust width as needed */
            /* Add any additional styling */
        }
    </style>
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
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User"
                                        class="rounded-circle" width="150">
                                    <div class="mt-3">
                                        <h4><?php echo $username; ?></h4>
                                        <p class="text-secondary mb-1">Username :<?php echo $username; ?> </p>
                                        <p class="text-muted font-size-sm">Email: <?php echo $email; ?></p>
                                        <p class="text-muted font-size-sm">Member Since:
                                            <?php echo date('F j, Y', strtotime($created_at)); ?>
                                        </p>
                                        <p class="text-muted font-size-sm">Number of book read:
                                            <?php echo $books_read; ?>
                                        </p>
                                        <!-- <button class="btn btn-primary">Follow</button>
                                        <button class="btn btn-outline-primary">Message</button> -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h2 class="font-weight-light text-center">List of books</h2>
                        <div id="bookList" class="d-flex flex-row flex-nowrap overflow-auto"
                            data-bs-smooth-scroll="true" data-bs-spy="scroll">
                            <?php include 'fetch_books.php'; ?>
                        </div>
                    </div>
                    <!-- delete Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content  w-75">
                                <div class="modal-header  bg-dark">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete book</h5>
                                </div>
                                <div class="modal-body  bg-dark">
                                    Do you want to delete the book? serious
                                </div>
                                <div class="modal-footer  bg-dark">
                                    <button type="button" class="btn btn-secondary btn-outline-light" onclick= "$('#exampleModal').modal('hide');" >Close</button>
                                    <button type="button" class="btn btn-danger"
                                        id="confirmDeleteButton">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- edit modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editmodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg d-flex justify-content-center">
                            <div class="modal-content w-75">
                                <div class="modal-header  bg-dark">
                                    <h5 class="modal-title" id="edittitle">Edit Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 bg-dark">
                                    <div id="bookForm">
                                        <!-- Email input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="summary">Summary</label>
                                            <input type="text" id="summary" class="form-control"  />

                                        </div>

                                        <!-- password input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <label class="form-label" for="rating">Rating</label>
                                            <input type="number" id="rating" class="form-control" min="0" max="5"/>

                                        </div>

                                        <!-- Submit button -->
                                        <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-primary btn-block descButton">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script>
    const bookList = document.getElementById('bookList');

    bookList.addEventListener('wheel', function (event) {
        if (event.deltaY > 0) {
            behavior: "smooth"
            bookList.scrollLeft += 50;
        } else {
            behavior: "smooth"
            bookList.scrollLeft -= 50;
        }
    });

    // Function to handle delete book AJAX request
    let bookIdToDelete;
    let bookIdToEdit;

    $(document).on('click', '.delete-book', function () {
        bookIdToDelete = $(this).data('bookid');
        $('#deleteModal').modal('show');
    });

    $('#confirmDeleteButton').on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'profile.php',
            data: { delete_book: true, book_id: bookIdToDelete },
            success: function (response) {
                try {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        location.reload();
                    } else {
                        alert('Error deleting book: ' + (jsonResponse.message || 'Unknown error'));
                    }
                } catch (e) {
                    alert('Error parsing response: ' + e.message);
                }
            },
            error: function () {
                alert('An error occurred while deleting the book.');
            }
        });
    });

    // $(document).on('click', '.edit-book', function () {
    //     bookIdToEdit = $(this).data('bookid');
    //     $('#deleteModal').modal('show');
    // });

    $(document).on('click', '.edit-book', function () {
        bookIdToEdit = $(this).data('bookid'); // Get the book ID from the data attribute
        $('#editModal').modal('show'); // Show the edit modal
    });

    

    $(document).ready(function() {
    $('.descButton').click(function(e) {
        e.preventDefault();

        // Gather data from the form
        var summary = $('#summary').val();
        var rating = $('#rating').val();
        //ar book_id = $(this).data('bookid'); // Assuming you have book ID accessible
        var dataToSend = {
            rating: rating,
            book_id: bookIdToEdit
        };

        if (summary.trim() !== '') {
            dataToSend.summary = summary;
        }
        if (rating > 5) {
            // If rating is greater than 5, set it back to 5
            dataToSend.rating = 5;
        }
        if (summary.trim() === '' && rating.trim() === '') {
            alert('Please enter summary or rating.'); // Inform the user to enter data
            return; // Exit function without sending AJAX request
        }
        // AJAX request
        $.ajax({
            type: 'POST',
            url: 'profile.php', // Replace with your server-side script URL
            data: dataToSend,
            success: function (response) {
                try {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        alert('Feedback added successfully!');
                        location.reload();
                    } else {
                        alert('Error updating book: ' + (jsonResponse.message || 'Unknown error'));
                    }
                } catch (e) {
                    alert('Error parsing response: ' + e.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle error, show error message
                alert('Error adding feedback: ' + error);
            }
        });
    });
});


</script>