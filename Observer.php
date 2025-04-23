<?php
class Grade {
    private $observers = [];

    public function addObserver($observer) {
        $this->observers[] = $observer;
    }

    public function assignGrade($grade) {
        foreach ($this->observers as $observer) {
            $observer->update($grade);  // Notify the observers
        }
    }
}

class Observer {
    public function update($grade) {
        // Handle the grade update logic (e.g., send email or alert)
        echo "Grade assigned: " . $grade;
    }
}
?>


// Adding observers to the grade object
$grade = new Grade();
$observer = new Observer();
$grade->addObserver($observer);

// Assign a grade, which will notify the observer
$grade->assignGrade(90);