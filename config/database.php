<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'online_food_ordering_system';
    private $username = 'root';
    private $password = '';

    public function connect() {
        $conn = null;
        try {
            $conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $conn;
    }
}
?>
