<?php
class DatabaseHandler {
    private $host = "localhost";
    private $username = "root";
    private $password = "root_password";
    private $dbname = "corpse-chat";
    private $conn = null;
    /**
     * @param mixed $host
     * @param mixed $username
     * @param mixed $password
     * @param mixed $dbname
     */
    public function __construct($host = null, $username = null, $password = null, $dbname = null) {
        if ($host !== null) $this->host = $host;
        if ($username !== null) $this->username = $username;
        if ($password !== null) $this->password = $password;
        if ($dbname !== null) $this->dbname = $dbname;
        
        $this->connect();
    }
    /**
     * @return void
     */
    private function connect(): void {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $table
     */
    public function tableExists($table) {
        try {
            $result = $this->conn->query("SHOW TABLES LIKE '{$table}'");
            return $result->rowCount() > 0;
        } catch(PDOException $e) {
            die("Error checking table: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $table
     * @param mixed $data
     */
    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            
            $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
            $stmt = $this->conn->prepare($query);
            
            foreach($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            die("Insert failed: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $table
     * @param mixed $columns
     * @param mixed $where
     * @param mixed $params
     */
    public function select($table, $columns = "*", $where = null, $params = []) {
        try {
            $query = "SELECT {$columns} FROM {$table}";
            if ($where !== null) {
                $query .= " WHERE {$where}";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            die("Select failed: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $table
     * @param mixed $data
     * @param mixed $where
     * @param mixed $params
     */
    public function update($table, $data, $where, $params = []) {
        try {
            $sets = [];
            foreach($data as $key => $value) {
                $sets[] = "{$key} = :{$key}";
            }
            
            $query = "UPDATE {$table} SET " . implode(", ", $sets) . " WHERE {$where}";
            $stmt = $this->conn->prepare($query);
            
            foreach($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            foreach($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            die("Update failed: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $table
     * @param mixed $where
     * @param mixed $params
     */
    public function delete($table, $where, $params = []) {
        try {
            $query = "DELETE FROM {$table} WHERE {$where}";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            die("Delete failed: " . $e->getMessage());
        }
    }
    /**
     * @param mixed $sql
     * @param mixed $params
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
    /**
     * @return void
     */
    public function __destruct() {
        $this->conn = null;
    }
}
