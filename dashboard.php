<?php
session_start();
require_once 'db_connect.php';
require_once 'Course.php';  // Include the Course and CourseBuilder

// Check if user is logged in
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Get the logged-in user's name and role
$userName = $_SESSION['userName'];
$role = $_SESSION['role'];

// Get the database connection
$conn = DatabaseConnection::getInstance()->getConnection();

// Query the UserSelectedCourses view to get the selected courses
$query = "SELECT * FROM UserSelectedCourses";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Display the selected courses as cards
$selectedCourses = [];
while ($row = $result->fetch_assoc()) {
    $courseBuilder = new CourseBuilder();
    $course = $courseBuilder
        ->setCourseId($row['course_id'])
        ->setName($row['name'])
        ->setDescription($row['description'])
        ->setDuration($row['duration'])
        ->setInstructorId($row['instructor_id'])
        ->build();

    $selectedCourses[] = $course;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar with navigation links -->
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="select_course.php" class="list-group-item list-group-item-action">Select Course</a>
                    <a href="profile.php" class="list-group-item list-group-item-action">My Profile</a>
                    <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Main Content Area -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Welcome, <?php echo $userName; ?>!</h4>
                        <p class="card-text">Role: <?php echo $role; ?></p>

                        <!-- Dashboard: Show Selected Courses -->
                        <h5>Selected Courses:</h5>
                        <div class="row">
                            <?php if (count($selectedCourses) > 0): ?>
                                <?php foreach ($selectedCourses as $course): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $course->getDetails()['name']; ?></h5>
                                                <p class="card-text"><?php echo $course->getDetails()['description']; ?></p>
                                                <p class="card-text"><small class="text-muted">Duration: <?php echo $course->getDetails()['duration']; ?> weeks</small></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No courses selected.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
