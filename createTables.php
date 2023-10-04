<?php
include('./connection.php');

$db = new Database("localhost", "root", "qurman26", "data");


$db->createTable(
    "users",
    "id INT PRIMARY KEY, 
    name VARCHAR(255), 
    email VARCHAR(255), 
    active BOOLEAN"
);

$db->createTable(
    "posts",
    "id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, 
    title TEXT, 
    body TEXT, 
    created_at DATETIME, 
    active BOOLEAN"
);

function pushUsers($db)
{
    $userDataUrl = 'https://jsonplaceholder.typicode.com/users';
    $userData = file_get_contents($userDataUrl);
    $users = json_decode($userData, true);
    foreach ($users as $user) {
        $db->insert("users", [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "active" => ($user["id"] % 2 == 0) ? 1 : 0,
        ]);
        //print_r($users);
    }
}

function pushPosts($db)
{
    $postDataUrl = 'https://jsonplaceholder.typicode.com/posts';
    $postData = file_get_contents($postDataUrl);
    $posts = json_decode($postData, true);


    foreach ($posts as $post) {
        $startDate = strtotime('2013-01-01');
        $endDate = strtotime('2022-12-31');
        $randomTimestamp = mt_rand($startDate, $endDate);
        $dateOfCreation = date('ymd', $randomTimestamp);

        $db->insert("posts", [
            "id" => $post["id"],
            "user_id" => $post["userId"],
            "title" => $post["title"],
            "body" => $post["body"],
            "created_at" => $dateOfCreation,
            "active" => ($post["id"] % 2 == 0) ? 1 : 0,
        ]);
    }
}

pushUsers($db);
pushPosts($db);

$db->close();
