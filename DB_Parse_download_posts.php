<?php  
$jsonData = file_get_contents('https://jsonplaceholder.typicode.com/posts');
$dataToInsert = json_decode($jsonData, true);

$pdo = new PDO('mysql:host=localhost;dbname=test2','root','');
$stmt = $pdo->prepare("INSERT INTO posts(userId, id, title, body) VALUES (:userId, :id, :title, :body) ON DUPLICATE KEY UPDATE userId = VALUES(userId)");


foreach ($dataToInsert as $row) {
    if(isset($row['userId'], $row['id'], $row['title'], $row['body'])){
        $stmt->execute([
            ':userId' => $row['userId'],
            ':id' => $row['id'],
            ':title' => $row['title'],
            ':body' => $row['body']
        ]);
    }else{
        echo "Error: Недостаточно данных";
    }
}