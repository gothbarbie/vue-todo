<?php

header('Content-Type: application/json');

$db = new PDO('mysql:host=127.0.0.1;dbname=vue-todo', 'root', '');

$method = strtolower($_SERVER['REQUEST_METHOD']);

//die($method);

// get
if ($method === 'get') {
    $todos = $db->query("SELECT id, title FROM todos");
    if ($todos->rowCount() === 0) {
        echo json_encode([]);
        die();
    }
    echo json_encode($todos->fetchAll(PDO::FETCH_OBJ));
    die();
}

// post
if ($method === 'post') {
    if (!isset($_POST['title']) || empty(trim($_POST['title']))) {
        http_response_code(400);
        die();
    }

    $todo = $db->prepare("INSERT INTO todos (title) VALUES (:title)");
    $todo->execute(['title' => $_POST['title']]);
}


// delete
if ($method === 'delete') {
    parse_str(file_get_contents('php://input'), $payload);

    if (!isset($payload['id']) || empty(trim($payload['id']))) {
        http_response_code(400);
        die();
    }

    $todo = $db->prepare("DELETE FROM todos WHERE id = :id");

    $todo->execute([
        'id' => $payload['id'],
    ]);

    http_response_code(200);
    die();
}