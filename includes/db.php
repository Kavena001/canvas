<?php
require_once 'config.php';

class Database {
    private $connection;
    private $lastError = '';

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getLastError() {
        return $this->lastError;
    }

    public function query($sql, $params = []) {
        $this->lastError = '';
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) $types .= 'i';
                    elseif (is_float($param)) $types .= 'd';
                    else $types .= 's';
                }
                
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
            $this->lastError = $e->getMessage();
            error_log("Database Query Error: " . $e->getMessage() . " | SQL: $sql");
            return false;
        }
    }

    public function getRow($sql, $params = []) {
        try {
            $result = $this->query($sql, $params);
            return $result ? $result->fetch_assoc() : false;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Get Row Error: " . $e->getMessage());
            return false;
        }
    }

    public function getRows($sql, $params = []) {
        try {
            $result = $this->query($sql, $params);
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Get Rows Error: " . $e->getMessage());
            return false;
        }
    }

    public function insert($sql, $params = []) {
        $this->lastError = '';
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Insert preparation failed: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) $types .= 'i';
                    elseif (is_float($param)) $types .= 'd';
                    else $types .= 's';
                }
                
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
            $this->lastError = $e->getMessage();
            error_log("Database Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($sql, $params = []) {
        $this->lastError = '';
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Update preparation failed: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) $types .= 'i';
                    elseif (is_float($param)) $types .= 'd';
                    else $types .= 's';
                }
                
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
            $this->lastError = $e->getMessage();
            error_log("Database Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function beginTransaction() {
        return $this->connection->begin_transaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollBack() {
        return $this->connection->rollback();
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

// Initialize with error handling
try {
    $db = new Database();
} catch (Exception $e) {
    error_log("Fatal Database Error: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}
?>