<?php
class Complaint {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function submit($submitter_id, $subject, $description) {
        $stmt = $this->conn->prepare(
            "INSERT INTO complaints (submitter_id, subject, description, status) VALUES (?,?,?,'open')"
        );
        $stmt->bind_param('iss', $submitter_id, $subject, $description);
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function getByCustomer($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM complaints WHERE submitter_id=? ORDER BY created_at DESC"
        );
        $stmt->bind_param('i', $customer_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }
}
?>
