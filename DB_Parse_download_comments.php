<?php  
$jsonData = file_get_contents('https://jsonplaceholder.typicode.com/comments');
$dataToInsert = json_decode($jsonData, true);

// Проверьте, что данные были успешно декодированы
if ($dataToInsert === null) {
    die('Ошибка декодирования JSON');
}

// Пример кода для вставки данных в базу данных
try {
    // Подключение к базе данных
    $pdo = new PDO('mysql:host=localhost;dbname=test2','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Подготовка SQL запроса
    $stmt = $pdo->prepare("INSERT INTO comments (postId, id, name, email, body) VALUES (:postId, :id, :name, :email, :body) ON DUPLICATE KEY UPDATE postId = VALUES(postId)");

    foreach ($dataToInsert as $row) {
        // Убедитесь, что все необходимые поля существуют
        if (isset($row['postId'], $row['id'], $row['name'], $row['email'], $row['body'])) {
            $stmt->execute([
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
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}