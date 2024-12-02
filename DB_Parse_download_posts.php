<?php  
require './db_connect/Database.php';
$db = new Database();

$jsonData = file_get_contents('https://jsonplaceholder.typicode.com/posts');
if($jsonData == false){
    echo "Ошибка при получении данных из API";
}
$dataToInsert = json_decode($jsonData, true);

// Проверьте, что данные были успешно декодированы
if ($dataToInsert === null) {
    die('Ошибка декодирования JSON');
}


$db->beginTransaction();
try{
    foreach ($dataToInsert as $row) {
        if(isset($row['userId'], $row['id'], $row['title'], $row['body'])){
            $db->execute("INSERT INTO posts(userId, id, title, body) VALUES (:userId, :id, :title, :body) ON DUPLICATE KEY UPDATE userId = VALUES(userId)", [
                ':userId' => $row['userId'],
                ':id' => $row['id'],
                ':title' => $row['title'],
                ':body' => $row['body']
            ]);
        }else{
            echo "Error: Недостаточно данных";
        }
    }
} catch(Exception $e){
    $db->rollBack();
    echo "Ошибка: " . $e->getMessage();
}
