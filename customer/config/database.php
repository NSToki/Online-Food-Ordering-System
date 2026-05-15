<?php
class Database {
    private $host     = 'localhost';
    private $dbname   = 'online_food_ordering_system';
    private $username = 'root';
    private $password = '';

    public function connect() {
        $conn = null;
        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
        return $conn;
    }
}
?>
