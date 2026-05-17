<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "online_food_ordering_system";
    private $port = 3307; 
    private $conn;

    // Added a boolean flag $debug which defaults to false
    public function connect($debug = false) {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname, $this->port);
            
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            
            // Only echo if you explicitly ask for it during direct file testing
            if ($debug === true) {
                echo "Connected successfully";
            }
            
        } catch (Exception $e) {
            die("Database exception error: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>