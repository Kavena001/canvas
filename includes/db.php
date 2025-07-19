<?php
require_once 'config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Database connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->connection->error . " | SQL: $sql");
            }
            
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                if (!$stmt->bind_param($types, ...$params)) {
                    throw new Exception("Parameter binding failed: " . $stmt->error);
                }
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Database Query Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getRow($sql, $params = []) {
        try {
            $result = $this->query($sql, $params);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Get Row Error: " . $e->getMessage());
            return false;
        }
    }

    public function getRows($sql, $params = []) {
        try {
            $result = $this->query($sql, $params);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Get Rows Error: " . $e->getMessage());
            return false;
        }
    }

    public function insert($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Insert preparation failed: " . $this->connection->error . " | SQL: $sql");
            }
            
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                if (!$stmt->bind_param($types, ...$params)) {
                    throw new Exception("Parameter binding failed: " . $stmt->error);
                }
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Insert execution failed: " . $stmt->error);
            }
            
            $insertId = $stmt->insert_id;
            $stmt->close();
            
            return $insertId;
        } catch (Exception $e) {
            error_log("Database Insert Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Update preparation failed: " . $this->connection->error . " | SQL: $sql");
            }
            
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                if (!$stmt->bind_param($types, ...$params)) {
                    throw new Exception("Parameter binding failed: " . $stmt->error);
                }
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Update execution failed: " . $stmt->error);
            }
            
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            return $affectedRows;
        } catch (Exception $e) {
            error_log("Database Update Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function beginTransaction() {
        try {
            if (!$this->connection->begin_transaction()) {
                throw new Exception("Failed to start transaction");
            }
            return true;
        } catch (Exception $e) {
            error_log("Transaction Start Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function commit() {
        try {
            if (!$this->connection->commit()) {
                throw new Exception("Failed to commit transaction");
            }
            return true;
        } catch (Exception $e) {
            error_log("Transaction Commit Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function rollBack() {
        try {
            if (!$this->connection->rollback()) {
                throw new Exception("Failed to rollback transaction");
            }
            return true;
        } catch (Exception $e) {
            error_log("Transaction Rollback Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function isConnected() {
        return $this->connection && $this->connection->ping();
    }

    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct() {
        $this->close();
    }
}

// Initialize database connection with error handling
try {
    $db = new Database();
} catch (Exception $e) {
    // Log the error and show user-friendly message
    error_log("Fatal Database Error: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}
?>