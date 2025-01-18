<?php

$rootDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

// require_once 
require_once($rootDir . "config" . DIRECTORY_SEPARATOR . "cfg.php"); //файл с значение db


//переменая для exec() таблица в db
$sql = "CREATE TABLE IF NOT EXISTS tasks (              
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            completed bool default false
)";



// подключение db
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // выполнение запроса
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['completed'])){
        $title = $_POST['title'];
        $completed = $_POST['completed'];

        $sql = 'INSERT INTO tasks (title, completed) VALUES (:title, :completed)';
        $stmt = $conn->prepare($sql);

        // if($stmt->execute([':title' => $title, ':completed' =>$completed])){
        //     echo "WW";
        // } else {
        //     echo "LL";
        // }   
        

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }


    // Чекаем был ли запрос в пост по delete_id
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])){
        $deleteID = $_POST['delete_id'];

        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $deleteID]);

        // Обновление ID, чтобы они шли подряд
        $sql = "SET @new_id = 0;
            UPDATE tasks SET id = (@new_id := @new_id + 1);
            ALTER TABLE tasks AUTO_INCREMENT = 1;";
        $conn->exec($sql);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    require $rootDir . 'view' . DIRECTORY_SEPARATOR . 'view.php';

    // вывод задач
    $sql = 'SELECT * FROM tasks';
    $stmt = $conn->query($sql);
    
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //вывод + передача в $_POST по нажатию кнопки 'delete_id'
    foreach($tasks as $task){
        echo "ID: " . $task['id'] . " - Title: " . $task['title'] . " - Completed: " . ($task['completed'] ? 'Yes' : 'No') . 
        "<form method='POST' action=''>
                <input type='hidden' name='delete_id' value='" . $task['id'] . "'>
                <button type='submit'>Delete</button>
        </form> " . "<br>";
    }



} catch (PDOException $e) {
    die($e);
}
