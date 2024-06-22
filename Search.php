<?php
session_start(); // Start or resume the session

if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <meta charset="UTF-8">
    <title>Search</title>
    <style>
        body {
            background-color: #212529;
            color: white;
        }

        .search-output,
        .loading,
        #top_results {
            opacity: 0;
            transition: 0.3s ease-in-out;
            overflow: hidden;
        }

        .Prevbutton,
        .Nextbutton {
            opacity: 0;
            display: disabled;
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

        .carousel-item {
            height: 32rem;
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
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4 px-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Book</a>
            <button class="navbar-toggler collapsed " type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
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
    <main>
        <div id="carouselExampleAutoplaying" class="carousel slide mb-6" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item "
                    style="background-image: url('https://manybooks.net/sites/default/files/2018-07/bookstackssmall.jpg');">
                    <!-- <img width = "100%" height="100%" src="https://manybooks.net/sites/default/files/2018-07/bookdisplaysmall.jpg" class="d-block w-100" alt="..."> -->
                </div>
                <div class="carousel-item active"
                    style="background-image:url('https://manybooks.net/sites/default/files/2018-07/bookcoverssmall2.jpg');">
                    <!-- <img width = "100%" height="100%" src="https://manybooks.net/sites/default/files/2018-07/bookcoverssmall2.jpg" class="d-block w-100" alt="..."> -->
                </div>
                <div class="carousel-item"
                    style="background-image:url('https://manybooks.net/sites/default/files/2018-07/bookdisplaysmall.jpg');">
                    <!-- <img width = "100%" height="100%" src="https://manybooks.net/sites/default/files/2018-07/bookstackssmall.jpg" class="d-block w-100" alt="..."> -->
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>


        <div class="container mt-5">
            <h2 class="text-center">- Find Book by Title -</h2>

            <div class="mb-3 ">
                <form class="d-flex flex-column ">
                    <label for="title" class="form-label">Book Title</label>
                    <input type="search" class="form-control me-2 mb-3" id="title" aria-label="Search">
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                    <button class="btn btn-outline-success fetchBtn" type="submit">Search</button>
                </form>
            </div>

        </div>




        <!-- <div class="container mt-6"> -->
        <div class="loading">
            <p class="text-center">Loading... this may take a moment.</p>
        </div>
        <h2 id="top_results" class="text-center">- Search Results -</h2>
        <div class="search-output ">
            <pre class="row row-cols-1 row-cols-md-4 g-5 mt-3 mx-5 "></pre>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
                <div class="modal-dialog d-flex justify-content-center">
                    <div class="modal-content w-75">
                        <div class="modal-header  bg-dark">
                        <h5 class="modal-title" id="exampleModalLabel1">Feedback</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 bg-dark">
                            <div id="bookForm">
            
                                <input type="hidden" id="bookTitle" name="title">
                                <input type="hidden" id="bookAuthors" name="authors">
                                <input type="hidden" id="bookImageSrc" name="imageSrc">
                                <input type="hidden" id="bookIsbn" name="isbn">
                                <!-- Email input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="summary">Summary</label>
                                        <input type="text" id="summary" class="form-control" />
                                        
                                </div>

                            <!-- password input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="rating">Rating</label>
                                    <input type="number" id="rating" class="form-control" min= "0" max="5"  />
                                
                                </div>

                        <!-- Submit button -->
                                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block descButton">Add</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
<!-- Modal -->
    </div>
        <div class="d-flex justify-content-center mb-5">
            <button class="Prevbutton btn btn-outline-success me-5" onclick="prev()">prev</button>
            <button class="Nextbutton btn btn-outline-success ms-5" onclick="next()">Next</button>

        </div>


        <!-- </div> -->
    </main>


</body>

</html>
<script src="search.js"></script>
<script>
    // Store PHP session ID in a JavaScript variable
    const userID = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null'; ?>;
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Delegate the click event handling to the parent element
    //     document.querySelector('.search-output').addEventListener('click', function(event) {
    //         // Check if the clicked element matches the .insert-button selector
    //         if (event.target && event.target.matches('.insert-button')) {

    //             // Disable the button to prevent multiple submissions
                
    //             addButton.disabled = true;
    //             addButton.innerText = "Added";
    //             // Retrieve data from the clicked card
    //             const card = event.target.closest('.card');
    //             const title = card.querySelector('.card-title').innerText;
    //             const authors = card.querySelector('.card-text').innerText;
    //             const imageSrc = card.querySelector('.card-img-top').getAttribute('data-original-src');

    //             // Create an object with the data
    //             const data = {
    //                 title: title,
    //                 authors: authors,
    //                 imageSrc: imageSrc
    //             };

    //             // Send the data to the PHP script using fetch
    //             fetch('insert.php', {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json'
    //                 },
    //                 body: JSON.stringify(data)
    //             })
    //             .then(response => {
    //                 if (response.ok) {
    //                     alert('Data added to database successfully!');
    //                 } else {
    //                     alert('Failed to add data to database.');
    //                 }
    //             })
    //             .catch(error => console.error('Error:', error));
    //         }
    //     });
    // });
</script>
 