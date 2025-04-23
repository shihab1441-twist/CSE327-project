<?php
// Base User class
class User {
    protected $name;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($name, $email, $password, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $this->role = $role;
    }

    public function register($conn) {
        // SQL query to insert the user into the database
        $query = "INSERT INTO `User` (`name`, `email`, `password`, `role`) VALUES (?, ?, ?, ?)";

        // Prepare and execute query
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $this->name, $this->email, $this->password, $this->role);
        return $stmt->execute();
    }
}

// Student Class
class Student extends User {
    public function __construct($name, $email, $password) {
        parent::__construct($name, $email, $password, "Student");
    }
}

// Instructor Class
class Instructor extends User {
    public function __construct($name, $email, $password) {
        parent::__construct($name, $email, $password, "Instructor");
    }
}

// Abstract Factory
abstract class UserFactory {
    abstract public function createUser($name, $email, $password);
}

// Concrete Factory for Student
class StudentFactory extends UserFactory {
    public function createUser($name, $email, $password) {
        return new Student($name, $email, $password);
    }
}

// Concrete Factory for Instructor
class InstructorFactory extends UserFactory {
    public function createUser($name, $email, $password) {
        return new Instructor($name, $email, $password);
    }
}
?>
