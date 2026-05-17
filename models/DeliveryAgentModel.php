<?php

class DeliveryAgentModel {

    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Check if email already exists
    public function emailExists($email) {

        $sql = "SELECT id FROM users WHERE email = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);

        $count = mysqli_stmt_num_rows($stmt);

        mysqli_stmt_close($stmt);

        return $count > 0;
    }

    // Register delivery agent
    public function registerAgent($name, $email, $password_hash, $phone, $vehicle_type) {

        mysqli_begin_transaction($this->conn);

        try {

            // Insert into users table
            $userSql = "INSERT INTO users 
            (name, email, password_hash, phone, role, is_active, created_at)
            VALUES (?, ?, ?, ?, 'agent', 1, NOW())";

            $userStmt = mysqli_prepare($this->conn, $userSql);

            mysqli_stmt_bind_param(
                $userStmt,
                "ssss",
                $name,
                $email,
                $password_hash,
                $phone
            );

            mysqli_stmt_execute($userStmt);

            $user_id = mysqli_insert_id($this->conn);

            mysqli_stmt_close($userStmt);

            // Insert into delivery_agents table
            $agentSql = "INSERT INTO delivery_agents
            (user_id, vehicle_type, is_online, total_earnings, is_approved)
            VALUES (?, ?, 0, 0, 0)";

            $agentStmt = mysqli_prepare($this->conn, $agentSql);

            mysqli_stmt_bind_param(
                $agentStmt,
                "is",
                $user_id,
                $vehicle_type
            );

            mysqli_stmt_execute($agentStmt);

            mysqli_stmt_close($agentStmt);

            mysqli_commit($this->conn);

            return true;

        } catch (Exception $e) {

            mysqli_rollback($this->conn);

            return false;
        }
    }

    // Get delivery agent by email
    public function getAgentByEmail($email) {

        $sql = "SELECT 
                    users.id,
                    users.name,
                    users.email,
                    users.password_hash,
                    users.role,
                    users.is_active,
                    delivery_agents.id AS agent_id,
                    delivery_agents.vehicle_type,
                    delivery_agents.is_online,
                    delivery_agents.is_approved
                FROM users
                INNER JOIN delivery_agents
                ON users.id = delivery_agents.user_id
                WHERE users.email = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $user = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $user;
    }
    public function getAgentProfile($user_id) {
    $sql = "SELECT 
                users.id,
                users.name,
                users.email,
                users.phone,
                users.profile_pic,
                delivery_agents.id AS agent_id,
                delivery_agents.vehicle_type,
                delivery_agents.current_location_text,
                delivery_agents.is_online
            FROM users
            INNER JOIN delivery_agents ON users.id = delivery_agents.user_id
            WHERE users.id = ?";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $profile;
}

public function updateAgentProfile($user_id, $name, $phone, $vehicle_type, $current_location_text, $profile_pic) {
    mysqli_begin_transaction($this->conn);

    try {
        if ($profile_pic != "") {
            $userSql = "UPDATE users SET name = ?, phone = ?, profile_pic = ? WHERE id = ?";
            $userStmt = mysqli_prepare($this->conn, $userSql);
            mysqli_stmt_bind_param($userStmt, "sssi", $name, $phone, $profile_pic, $user_id);
        } else {
            $userSql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
            $userStmt = mysqli_prepare($this->conn, $userSql);
            mysqli_stmt_bind_param($userStmt, "ssi", $name, $phone, $user_id);
        }

        mysqli_stmt_execute($userStmt);
        mysqli_stmt_close($userStmt);

        $agentSql = "UPDATE delivery_agents 
                     SET vehicle_type = ?, current_location_text = ?
                     WHERE user_id = ?";

        $agentStmt = mysqli_prepare($this->conn, $agentSql);
        mysqli_stmt_bind_param($agentStmt, "ssi", $vehicle_type, $current_location_text, $user_id);
        mysqli_stmt_execute($agentStmt);
        mysqli_stmt_close($agentStmt);

        mysqli_commit($this->conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($this->conn);
        return false;
    }
    }
}
?>