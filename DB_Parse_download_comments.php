<?php
//Создание класса Database
$db = new Database();

$jsonData = @file_get_contents('https://jsonplaceholder.typicode.com/comments');
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
        // Убедитесь, что все необходимые поля существуют
        if (isset($row['postId'], $row['id'], $row['name'], $row['email'], $row['body'])) {
            $db->execute("INSERT INTO comments (postId, id, name, email, body) VALUES (:postId, :id, :name, :email, :body) ON DUPLICATE KEY UPDATE postId = VALUES(postId)",[
                ':postId' => $row['postId'],
                ':id' => $row['id'],
                ':name' => $row['name'],
                ':email' => $row['email'],
                ':body' => $row['body'],
            ]);
        } else {
            // Обработка случая, когда данные отсутствуют
            echo "Недостаточно данных для вставки в базу данных.";
        }
    }
} catch (Exception $e) {
    $db->rollBack();
    echo "Ошибка: " . $e->getMessage();
}
