<?php

session_start();

// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "visitor_system";

// $conn = new mysqli($servername, $username, $password, $database);

// if ($conn->connect_error) {
//     # code...
//     die("Connection failed: ". $conn->connect_error);
// }

class Database {
    private $conn;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "123qwe";
        $database = "visitor_system";

        $this->conn = mysqli_connect($servername, $username, $password, $database);

        if (!$this->conn) {
            die("Connection failed: ". mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}