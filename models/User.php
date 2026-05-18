<?php
class User {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function registerCustomer($name, $email, $password, $phone, $profile_pic) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, password_hash, phone, role, profile_pic, is_active)
             VALUES (?, ?, ?, ?, 'customer', ?, 1)"
        );
        $stmt->bind_param('sssss', $name, $email, $hash, $phone, $profile_pic);
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email=? AND role='customer' AND is_active=1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if ($row && password_verify($password, $row['password_hash'])) return $row;
        return false;
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s', $email);
        $stmt->execute(); $stmt->store_result();
        $e = $stmt->num_rows > 0; $stmt->close(); return $e;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }

    public function updateProfile($id, $name, $phone, $profile_pic = null) {
        if ($profile_pic) {
            $stmt = $this->conn->prepare("UPDATE users SET name=?,phone=?,profile_pic=? WHERE id=?");
            $stmt->bind_param('sssi', $name, $phone, $profile_pic, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET name=?,phone=? WHERE id=?");
            $stmt->bind_param('ssi', $name, $phone, $id);
        }
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function changePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $stmt->bind_param('si', $hash, $id);
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }
}
?>
