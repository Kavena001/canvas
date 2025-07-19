<?php
require_once 'config.php';

class Database {
    private $connection;
    private $lastError = '';

    public function __construct() {
        try {
            $this->connection = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                $this->lastError = "Connection failed: " . $this->connection->connect_error;
                throw new Exception($this->lastError);
            }
            
            if (!$this->connection->set_charset("utf8mb4")) {
                $this->lastError = "Charset error: " . $this->connection->error;
                throw new Exception($this->lastError);
            }
        } catch (Exception $e) {
            error_log("DB CONNECTION ERROR: " . $e->getMessage());
            $this->lastError = $e->getMessage();
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
                $this->lastError = "Prepare failed: " . $this->connection->error;
                throw new Exception($this->lastError);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) $types .= 'i';
                    elseif (is_double($param)) $types .= 'd';
                    else $types .= 's';
                }
                
                if (!$stmt->bind_param($types, ...$params)) {
                    $this->lastError = "Bind failed: " . $stmt->error;
                    throw new Exception($this->lastError);
                }
            }
            
            if (!$stmt->execute()) {
                $this->lastError = "Execute failed: " . $stmt->error;
                throw new Exception($this->lastError);
            }
            
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
            
        } catch (Exception $e) {
            error_log("QUERY ERROR: $sql | " . $e->getMessage());
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // ... (keep other methods similar with the same error handling pattern)

    public function insert($sql, $params = []) {
        $this->lastError = '';
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                $this->lastError = "Prepare failed: " . $this->connection->error;
                throw new Exception($this->lastError);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) $types .= 'i';
                    elseif (is_double($param)) $types .= 'd';
                    else $types .= 's';
                }
                
                if (!$stmt->bind_param($types, ...$params)) {
                    $this->lastError = "Bind failed: " . $stmt->error;
                    throw new Exception($this->lastError);
                }
            }
            
            if (!$stmt->execute()) {
                $this->lastError = "Execute failed: " . $stmt->error;
                throw new Exception($this->lastError);
            }
            
            $insertId = $stmt->insert_id;
            $stmt->close();
            return $insertId;
            
        } catch (Exception $e) {
            error_log("INSERT ERROR: $sql | " . $e->getMessage());
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // ... (other methods remain with similar error handling)
}

// Initialize with try-catch
try {
    $db = new Database();
} catch (Exception $e) {
    // Log detailed error but show generic message
    error_log("FATAL DB ERROR: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}
?>