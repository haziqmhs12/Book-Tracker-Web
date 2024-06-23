<?php
$error = '';
$success = '';

if (isset($_POST['register'])) {
    // Content Security Policy
    //header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");

    // Connect to the database
    include 'config.php'; // Include database connection

    // Get the form data and sanitize input to prevent SQL injection and XSS
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']); // No need to htmlspecialchars or trim password before hashing

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $error = "Invalid username!";
    } else {
        // Check if the username or email is already taken
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $mysqli->error);
        }
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email is already taken!";
        } else {
            // Hash the password before binding it to the prepared statement
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind the SQL statement
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

            // Check if the statement was prepared correctly
            if ($stmt === false) {
                die("Prepare failed: " . $mysqli->error);
            }

            // Bind parameters to the SQL statement
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // Execute the SQL statement
            if ($stmt->execute()) {
                $success = "New account created successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            background-color: #212529;
        }

        form {
            max-width: 600px;
            margin: 10px auto;
            /* margin:0 auto; */
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <form action="register.php" method="post" class="bg-white bg-gradient p-5 rounded">
            <h4 class="text-center">Register</h4>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            <div class="mb-4">
                <label class="form-label" for="username">Username:</label>
                <input class="form-control" id="username" name="username" required type="text"
                    value="<?php echo isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>" />
            </div>
            <div class="mb-4">
                <label class="form-label" for="email">Email:</label>
                <input class="form-control" id="email" name="email" required type="email"
                    value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>" />
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Password:</label>
                <input class="form-control" id="password" name="password" required type="password"/>
            </div>
            <div class="login-link mb-4">
                <a href="login.php">Already have an account? Login</a>
            </div>
            <input class="btn btn-primary w-100 py-2" name="register" type="submit" value="Register" />
        </form>
    </div>
</body>

</html>
