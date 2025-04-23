**Strategy**


<?php
interface GradeStrategy {
    public function grade($assignment);
}

class PercentageGradeStrategy implements GradeStrategy {
    public function grade($assignment) {
        // Implement percentage-based grading
        return "Grading assignment {$assignment['title']} based on percentage.";
    }
}

class LetterGradeStrategy implements GradeStrategy {
    public function grade($assignment) {
        // Implement letter-based grading
        return "Grading assignment {$assignment['title']} based on letter grade.";
    }
}

// Context class (Assignment)
class Assignment {
    private $gradeStrategy;

    public function __construct($gradeStrategy) {
        $this->gradeStrategy = $gradeStrategy;
    }

    public function applyGrade($assignment) {
        return $this->gradeStrategy->grade($assignment);
    }
}
?>


$assignment = new Assignment(new PercentageGradeStrategy());
echo $assignment->applyGrade(['title' => 'JavaScript Basics']);  // Grading assignment JavaScript Basics based on percentage.