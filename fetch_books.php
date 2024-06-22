    <?php
    include 'config.php'; // Include database connection

    // Initialize an empty string to store HTML content
    $html = '';

    // SQL query to fetch books belonging to the current user
    $sql = "SELECT b.id, b.title, b.authors, b.image_url, ub.rating, ub.summary
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
                $rating = $row["rating"];
                $summary = $row["summary"];

                // HTML output for each book
                $html .= '<div class="card m-3" style="min-width: 20rem; max-width: 20rem;">';
                $html .= '<img class="card-img-top img-fluid img-thumbnail" src="' . $image_path . '" alt="Book Image">';
                $html .= '<div class="card-body" id="' . $book_id . '">';
                $html .= '<h5 class="card-title">' . $title . '</h5>';
                $html .= '<p class="card-text">' . $author . '</p>';
                //$html .= '<p class="card-summary">' . $summary . '</p>'; // Adjust class name as needed
    

                
                $html .= '</div>';
                // Generate stars based on rating
                $html .= '<div class="star-rating p-4">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        $html .= '<span class="fa fa-star checked"></span>'; // Filled star
                    } else {
                        $html .= '<span class="fa fa-star"></span>'; // Empty star
                    }
                }
                $html .= '<div class="btn-group position-absolute bottom-0 end-0 dropstart" >';
                $html .= '<button class="btn btn-secondary btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom-' . $book_id . '" aria-controls="offcanvasBottom-' . $book_id . '">Summary</button>';

                // Offcanvas content
                $html .= '<div class="offcanvas offcanvas-bottom"  tabindex="-1" id="offcanvasBottom-' . $book_id . '" aria-labelledby="offcanvasBottomLabel">';
                $html .= '<div class="offcanvas-header">';
                $html .= '<h5 class="offcanvas-title" id="offcanvasBottomLabel">Books Summary</h5>';
                $html .= '<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>';
                $html .= '</div>';
                $html .= '<div class="offcanvas-body small">';
                $html .= '<p>' . $summary . '</p>';
                $html .= '</div>';
                $html .= '</div>';

                $html .= '<button type="button" class="btn btn-dark btn-secondary dropdown-toggle dropdown-toggle-split " data-bs-toggle="dropdown" aria-expanded="false">';
                $html .= '<span class="visually-hidden">Toggle Dropdown</span>';
                $html .= '</button>';
                $html .= '<ul class="dropdown-menu">';
                $html .= '<li><a class="dropdown-item edit-book" href="#" data-bs-toggle="modal" data-bs-target="#editModal" data-bookid="' . $book_id . '">Edit</a></li>';
                $html .= '<li><a class="dropdown-item delete-book" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bookid="' . $book_id . '">Delete</a></li>';
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