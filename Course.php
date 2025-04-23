<?php
class Course {
    private $courseId;
    private $name;
    private $description;
    private $duration;
    private $instructorId;

    public function __construct($courseId, $name, $description, $duration, $instructorId) {
        $this->courseId = $courseId;
        $this->name = $name;
        $this->description = $description;
        $this->duration = $duration;
        $this->instructorId = $instructorId;
    }

    public function getDetails() {
        return [
            'courseId' => $this->courseId,
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'instructorId' => $this->instructorId
        ];
    }
}

class CourseBuilder {
    private $courseId;
    private $name;
    private $description;
    private $duration;
    private $instructorId;

    public function setCourseId($courseId) {
        $this->courseId = $courseId;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
        return $this;
    }

    public function setInstructorId($instructorId) {
        $this->instructorId = $instructorId;
        return $this;
    }

    public function build() {
        return new Course($this->courseId, $this->name, $this->description, $this->duration, $this->instructorId);
    }
}
?>
