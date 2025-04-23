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
$userId = $_SESSION['userId']; // Assuming the user_id is stored in the session

// Get the database connection
$conn = DatabaseConnection::getInstance()->getConnection();

// Fetch all courses for selection (from the database)
$courses = [];
$query = "SELECT * FROM Course";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
    
while ($row = $result->fetch_assoc()) {
    // Build each course using the Builder Pattern
    $courseBuilder = new CourseBuilder();
    $course = $courseBuilder
        ->setCourseId($row['course_id'])
        ->setName($row['name'])
        ->setDescription($row['description'])
        ->setDuration($row['duration'])
        ->setInstructorId($row['instructor_id'])
        ->build();
    
    $courses[] = $course;
}

// If the form is submitted, create a view and show the selected courses
if (isset($_POST['selected_courses'])) {
    $selectedCourseIds = $_POST['selected_courses']; // array of selected course IDs
    
    // Create a dynamic SQL view for selected courses
    $courseIds = implode(',', $selectedCourseIds); // Create a comma-separated list of selected course IDs
    $createViewQuery = "
        CREATE OR REPLACE VIEW UserSelectedCourses AS
        SELECT c.course_id, c.name, c.description, c.duration
        FROM Course c
        WHERE c.course_id IN ($courseIds)
    ";

    // Execute the SQL query to create the view
    if ($conn->query($createViewQuery) === TRUE) {
        // Redirect to the dashboard to view the selected courses
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating view: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar with navigation links -->
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="select_course.php" class="list-group-item list-group-item-action active">Select Course</a>
                    <a href="profile.php" class="list-group-item list-group-item-action">My Profile</a>
                    <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Course Selection Form -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Select Courses</h4>
                        <form action="select_course.php" method="POST">
                            <?php foreach ($courses as $course): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_courses[]" value="<?php echo $course->getDetails()['courseId']; ?>" id="course_<?php echo $course->getDetails()['courseId']; ?>">
                                    <label class="form-check-label" for="course_<?php echo $course->getDetails()['courseId']; ?>">
                                        <?php echo $course->getDetails()['name']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary mt-3">Done</button>
                        </form>
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
