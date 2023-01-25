<?php


class Base{
    public $mysqli;
    function __construct(){
        $this->connect_base();
    }
    public function connect_base(){
        $mysqli = new mysqli($_ENV["MYSQL_SERVERNAME"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        if ($mysqli -> connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
            exit();
        };
        $this->mysqli = $mysqli;

    }
    public function close(){
        $this->mysqli -> close();
    }
}