<?php
class DatabaseConnection {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = mysqli_connect("localhost", "root", "", "e_learning_platform");

        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
