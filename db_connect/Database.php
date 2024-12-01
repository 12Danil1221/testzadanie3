<?php


class Database{
    public $pdo;

    public function __construct(){
        $conn = "mysql:host=localhost;dbname=test";
        $this->pdo = new PDO($conn, 'root', '');
    }
    public function query($query, $params = []){
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        return $query;
    }
}