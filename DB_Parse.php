<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <form method="POST">
            <button type="submit" name="submit">Import Записей</button>
            <button type="submit" name="submit2">Import Комментариев</button>
        </form>
        <br>
        <form method="POST">
            <input type="text" name="search">
            <button type="submit" name="search_button">Search</button>
        </form>

    </div>
    <?php  
        require './db_connect/Database.php';
        $db = new Database();

        if(isset($_POST['search_button'])){
            $search = $_POST['search'];

            // Проверяем, что введено более 3 символов
            if(strlen($search) > 3){
            // Подготавливаем SQL-запрос
            $results = $db->execute("
            SELECT posts.*, comments.body
            FROM posts 
            JOIN comments ON posts.userId = comments.postId 
            WHERE comments.body LIKE :search
            ",['search' => '%'. $search .'%'])->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<table border=1>";
            echo "<tr><th></th><th>Заголовок</th>";
            echo "<th>Комментарий</th>";
            echo "</th></tr>";
            echo "<h2>Записи с искомым словом</h2>";
            foreach($results as $row){
                echo "<tr><td>".htmlspecialchars($row['userId'])."</td>";
                echo "<td>".htmlspecialchars($row['title'])."</td>";
                echo "<td>".htmlspecialchars($row['body'])."</td></tr>";

            }
            echo "</table>";
        }else{
            echo "Вы ввели меньше 3 символов";
        }

    }else{ 
        echo "Вы не ввели данные для поиска";
    };

        if(isset($_POST['submit']) || isset($_POST['submit2'])){
            require './DB_Parse_download_comments.php';
                        
            // Подготавливаем SQL-запрос
            $comments = $db->execute("SELECT * FROM comments")->fetchAll(PDO::FETCH_ASSOC); // Получаем все данные в виде ассоциативного массива

            //Проверка наличия данных
            if(count($comments) > 0){
                echo "<h2>Все комментарии:</h2>";
                echo "<table border='1'>";
                echo "<tr><th>PostId</th><th>ID</th><th>Name</th><th>Email</th><th>Body</th></tr>";

                foreach($comments as $comment){
                    echo "<tr><td>".htmlspecialchars($comment['postId'])."</td>";
                    echo "<td>".htmlspecialchars($comment['id'])."</td>";
                    echo "<td>".htmlspecialchars($comment['name'])."</td>";
                    echo "<td>".htmlspecialchars($comment['email'])."</td>";
                    echo "<td>".htmlspecialchars($comment['body'])."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }else{
                echo "Нет данных для отображения";
            }
        }

?>
    <?php
        if(isset($_POST['submit']) || isset($_POST['submit2'])){
            require './DB_Parse_download_posts.php';

            $posts = $db->execute("SELECT * FROM posts")->fetchAll(PDO::FETCH_ASSOC);

            //Проверка наличия данных
            if(count($posts) > 0){
        echo "<h2>Все записи:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>UserId</th><th>ID</th><th>title</th><th>body</th></tr>";

        foreach($posts as $post){
            echo "<tr><td>".htmlspecialchars($post['userId'])."</td>";
            echo "<td>".htmlspecialchars($post['id'])."</td>";
            echo "<td>".htmlspecialchars($post['title'])."</td>";
            echo "<td>".htmlspecialchars($post['body'])."</td>";
            echo "</tr>";
        }
        echo "</table>";
        }else{
            echo "Нет данных для отображения";
        }
    }
    ?>


</body>

</html>
