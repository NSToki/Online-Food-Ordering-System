<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registerCustomer($name, $email, $password, $phone, $profile_pic) {
        $query = "INSERT INTO users (name, email, password_hash, phone, role, profile_pic)
                  VALUES (:name, :email, :password, :phone, 'customer', :profile_pic)";
        $stmt = $this->conn->prepare($query);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $stmt->execute([
            ':name'        => $name,
            ':email'       => $email,
            ':password'    => $hash,
            ':phone'       => $phone,
            ':profile_pic' => $profile_pic
        ]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email = :email AND role = 'customer' AND is_active = 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->rowCount() > 0;
    }
}
?>
