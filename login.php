<?php
session_start();
require_once 'db_connect.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = DatabaseConnection::getInstance()->getConnection();
    $query = "SELECT * FROM User WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['userName'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid credentials.'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials.'); window.location.href = 'login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
