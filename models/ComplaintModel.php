<?php

require_once __DIR__ . '/../config/database.php';

class ComplaintModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }



    public function getAllComplaints() {

        $sql = "
            SELECT c.*,
                   u.name AS submitter_name,
                   u.email AS submitter_email,
                   u.role AS submitter_role
            FROM complaints c
            JOIN users u ON c.submitter_id = u.id
            ORDER BY c.status ASC, c.created_at DESC
        ";

        $result = $this->db->query($sql);

        return $result->fetchAll();
    }


    public function resolveComplaint($id) {

        $sql = "UPDATE complaints SET status = 'resolved' WHERE id = $id";

        return $this->db->query($sql);
    }
}

?>