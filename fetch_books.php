<?php
include 'config.php'; // Include database connection

// Initialize an empty string to store HTML content
$html = '';

// SQL query to fetch books belonging to the current user
$sql = "SELECT b.id, b.title, b.authors, b.image_url
        FROM books b
        JOIN user_books ub ON b.id = ub.book_id
        WHERE ub.user_id = $user_id";
$result = $mysqli->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $book_id = $row["id"];
            $title = $row["title"];
            $author = $row["authors"];
            $image_path = $row["image_url"];

            // HTML output for each book
            $html .= '<div class="card m-3" style="min-width: 20rem; max-width: 20rem;">';
            $html .= '<img class="card-img-top img-fluid img-thumbnail" src="' . $image_path . '" alt="Book Image">';
            $html .= '<div class="card-body" id="' . $book_id . '">';
            $html .= '<h5 class="card-title">' . $title . '</h5>';
            $html .= '<p class="card-text">' . $author . '</p>';
            $html .= '<div class="btn-group">';
            $html .= '<button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
            $html .= '<span class="visually-hidden">Toggle Dropdown</span>';
            $html .= '</button>';
            $html .= '<ul class="dropdown-menu">';
            $html .= '<li><a class="dropdown-item" href="#">Edit</a></li>';
            $html .= '<li><a class="dropdown-item" href="#">Delete</a></li>';
            $html .= '<li><a class="dropdown-item" href="#">Something else here</a></li>';
            $html .= '<li><hr class="dropdown-divider"></li>';
            $html .= '<li><a class="dropdown-item" href="#">Separated link</a></li>';
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    } else {
        $html = "0 results";
    }
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close(); // Close database connection
echo $html; // Output the generated HTML
?>