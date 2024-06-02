<?php

define("USER","root");
define("Password","");
define("DBName","edtech");
define("Host","localhost");

class Connection {

    public $pdo;
    public function getConnection() {
        try {
            $dbuser = constant('USER');
            $dbpass = constant('Password');
            $dbname = constant('DBName');
            $dbhost = constant('Host');
            $this->pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;",$dbuser,$dbpass);
            return $this->pdo;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function disconnected() {
        $this->pdo = null;
    }
}

?>