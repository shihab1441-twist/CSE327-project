**Decorator**

<?php
class Course {
    private $name;
    private $description;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }
}

class CourseDecorator {
    protected $course;

    public function __construct($course) {
        $this->course = $course;
    }

    public function getDescription() {
        return $this->course->getDescription();
    }
}

class CertificationCourseDecorator extends CourseDecorator {
    public function getDescription() {
        return $this->course->getDescription() . " (Certified Course)";
    }
}
?>


$course = new Course("JavaScript Basics", "Learn the fundamentals of JavaScript.");
$certifiedCourse = new CertificationCourseDecorator($course);

echo $certifiedCourse->getDescription();  // Outputs: Learn the fundamentals of JavaScript. (Certified Course)
