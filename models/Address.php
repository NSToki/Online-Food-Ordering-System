<?php
class Address {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getAll($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM delivery_addresses WHERE customer_id=? ORDER BY is_default DESC, id ASC"
        );
        $stmt->bind_param('i', $customer_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function add($customer_id, $label, $address_line, $city, $is_default) {
        if ($is_default) {
            $c = $this->conn->prepare("UPDATE delivery_addresses SET is_default=0 WHERE customer_id=?");
            $c->bind_param('i', $customer_id); $c->execute(); $c->close();
        }
        $stmt = $this->conn->prepare(
            "INSERT INTO delivery_addresses (customer_id, label, address_line, city, is_default) VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('isssi', $customer_id, $label, $address_line, $city, $is_default);
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function delete($id, $customer_id) {
        $stmt = $this->conn->prepare("DELETE FROM delivery_addresses WHERE id=? AND customer_id=?");
        $stmt->bind_param('ii', $id, $customer_id); $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function setDefault($id, $customer_id) {
        $c = $this->conn->prepare("UPDATE delivery_addresses SET is_default=0 WHERE customer_id=?");
        $c->bind_param('i', $customer_id); $c->execute(); $c->close();
        $stmt = $this->conn->prepare("UPDATE delivery_addresses SET is_default=1 WHERE id=? AND customer_id=?");
        $stmt->bind_param('ii', $id, $customer_id); $ok = $stmt->execute(); $stmt->close(); return $ok;
    }
}
?>
