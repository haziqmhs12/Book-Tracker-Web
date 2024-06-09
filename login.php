<?php
session_start();

if (isset($_POST['login'])) {
    // Content Security Policy
    //header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");

    // Connect to the database
    $mysqli = new mysqli("localhost", "root", "", "login_system");

    // Check for connection errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Get the form data and sanitize input to prevent SQL injection and XSS
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']); // No need to htmlspecialchars or trim password before verification

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $_SESSION['error'] = "Invalid username!";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");

        // Check if the statement was prepared correctly
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }

        // Bind parameters to the SQL statement
        $stmt->bind_param("s", $username);

        // Execute the SQL statement
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($id, $hashed_password);

            // Fetch the result
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Set the session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['user'] = $username;

                // Redirect to the user's dashboard
                header("Location: Search.php");
                exit;
            } else {
                $_SESSION['error'] = "Incorrect Username or password!";
            }
        } else {
            $_SESSION['error'] = "Incorrect Username or password!";
        }

        // Close the statement and connection
        $stmt->close();
    }
    $mysqli->close();

    // Redirect to avoid resubmission
    header("Location: login.php");
    exit;
}

// Fetch and clear error messages from session
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            background-color: #212529;
        }

        form {
            max-width: 600px;
            margin: 10px auto;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <form action="login.php" method="post" class="bg-white bg-gradient p-5 rounded">
            <h4 class="text-center">Login</h4>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input id="username" name="username" class="form-control" placeholder="username" required type="text" />
            </div>
            <div class="mb-3">
                <label for="password" class="mb-3" class="form-label">Password:</label>
                <input id="password" name="password" class="form-control" aria-describedby="passwordHelpBlock" required type="password" />
                <div id="passwordHelpBlock" class="form-text text-danger">
                    Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </div>
            </div>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>

            <input class="btn btn-primary w-100 py-2" name="login" type="submit" value="Login" />
        </form>
    </div>
</body>

</html>
