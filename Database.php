<?php

class Database {
    private $_connection;
    private static $_instance;
    private $_host = 'localhost';
    private $_username = 'root';
    private $_password = 'manager';
    private $_dbname = 'db_data';
    
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __construct() {
        $this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_dbname);
        
        if (mysqli_connect_error()) {
            trigger_error("Gagal terkoneksi ke MySQL: " . mysql_connect_error(), E_USER_ERROR);
        }
    }
    
    private function __clone() { }
    
    public function getConnection() {
        return $this->_connection;
    }
}

?>