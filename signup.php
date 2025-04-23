<?php
require_once 'db_connect.php';
require_once 'UserFactory.php';

if (isset($_POST["signup"])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];  // Get the selected role

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'signup.php';</script>";
        exit();
    }

    // Create user object using Factory Pattern based on role
    $userFactory = ($role == "Instructor") ? new InstructorFactory() : new StudentFactory();
    $user = $userFactory->createUser($username, $email, $password);

    $conn = DatabaseConnection::getInstance()->getConnection();

    // Now, register the user (inserting data into the database)
    if ($user->register($conn)) {
        echo "<script>alert('User registered successfully!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register user.'); window.location.href = 'signup.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Student">Student</option>
                    <option value="Instructor">Instructor</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="signup">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
