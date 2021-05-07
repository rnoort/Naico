<?php
    /**
    * A simple mysqli wrapper written in PHP, made for simplicity.
    * @package Naico
    * @author Roelof Noort
    * @copyright Copyright (c) 2021, Roelof Noort
    *
    */
    class Naico {
        private $instance;
        private $hostname;
        private $username;
        private $password;
        private $database;

        public function __construct($options = []) {
            // Assign and check variables
            if(empty($options)) die("naico.php: no options specified");
            $this->hostname = ((empty($options["hostname"])) ? die("naico.php: no hostname provided") : $options["hostname"]);
            $this->username = ((empty($options["username"])) ? die("naico.php: no username provided") : $options["username"]);
            $this->password = ((empty($options["password"])) ? die("naico.php: no password provided") : $options["password"]);
            $this->database = ((empty($options["database"])) ? die("naico.php: no database provided") : $options["database"]);

            // Create instance and check for connection errors
            $this->instance = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            if($this->instance->connect_error) die("naico.php: " . $this->instance->connect_error);
        }
        /**
        * Check the table
        * @param string Table
        */
        private function checkTable($table) {
            if(empty($table)) die("naico.php: no table provided");
            $stmt = $this->instance->prepare("CREATE TABLE IF NOT EXISTS {$table} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                k TEXT NOT NULL,
                v TEXT NOT NULL
            );");
            if(!$stmt) die("naico.php: not a valid sql query");
            if($stmt->execute()) {
                return true;
            } else {
                die("naico.php: couldn't create table {$table}");
            }
        }

        /**
        * Check if table has a specific key
        * @param string Table
        * @param string Key
        * @return boolean
        */
        public function has($table, $key) {
            if(empty($table) || empty($key)) die("naico.php: no table or key provided");
            $this->checkTable($table);
            $exists = false;
            $stmt = $this->instance->prepare("SELECT * FROM {$table} WHERE k = ?;");
            if(!$stmt) die("naico.php: not a valid sql query");
            $stmt->bind_param("s", $key);
            if($stmt->execute()) {
                $result = $stmt->get_result();
                if($result->num_rows == 1) $exists = true;
            }
            return $exists;
        }

        /**
        * Assign a value to a key in a table
        * @param table Table
        * @param string Key
        * @param mixed Value
        */
        public function set($table, $key, $value) {
            if(empty($table) || empty($key) || empty($value)) die("naico.php: no table, key or value provided");
            $this->checkTable($table);
            $exists = $this->has($table, $key);
            if($exists) {
                $stmt = $this->instance->prepare("UPDATE {$table} SET v = ? WHERE k = ?");
                if(!$stmt) die("naico.php: not a valid sql query");
                $serializedValue = serialize($value);
                $stmt->bind_param("ss", $serializedValue, $key);
                if($stmt->execute()) {
                    $result = $stmt->get_result();
                    return true;
                } else {
                    return false;
                }
            } else {
                $stmt = $this->instance->prepare("INSERT INTO {$table} (k, v) VALUES (?, ?);");
                if(!$stmt) die("naico.php: not a valid sql query");
                $serializedValue = serialize($value);
                $stmt->bind_param("ss", $key, $serializedValue);
                if($stmt->execute()) {
                    $result = $stmt->get_result();
                    return true;
                } else {
                    return false;
                }
            }
        }

        /**
        * Get the value of a key
        * @param string Table
        * @param string Key
        * @return string Value
        */
        public function get($table, $key) {
            if(empty($table) || empty($key)) die("naico.php: no table or key provided");
            $exists = $this->has($table, $key);
            if(!$exists) return;

            $stmt = $this->instance->prepare("SELECT (v) FROM {$table} WHERE k = ?;");
            if(!$stmt) die("naico.php: not a valid sql query");
            $stmt->bind_param("s", $key);
            if($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result->fetch_row();
                $unserialized = unserialize($row[0]);
                return $unserialized;
            } else {
                return;
            }
        }
    }
?>
