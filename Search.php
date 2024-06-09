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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>Search</title>
    <style>
        body {
            background-color: #212529;
            color: white;
        }

        .search-output,
        .loading {
            opacity: 0;
            transition: 0.3s ease-in-out;
            overflow: hidden;

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
    </style>
</head>

<body>
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
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" id="title" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success fetchBtn" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="loading">
            <p class="text-center">Loading... this may take a moment.</p>
        </div>
        <div class="search-output">
            <h2 id="top_results">- Top Results -</h2>
            <pre></pre>
        </div>

    </div>


</body>

</html>
<script src="search.js"></script>