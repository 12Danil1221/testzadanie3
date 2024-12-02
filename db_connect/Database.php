<?php


class Database{
    private $pdo;

    public function __construct(){
        $conn = "mysql:host=localhost;dbname=test2";
        $this->pdo = new PDO($conn, 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function execute($query, $params = []){
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        return $query;
    }
    public function beginTransaction(){
        $this->pdo->beginTransaction();
    }
    public function rollback(){
        $this->pdo->rollBack();
    }
}
