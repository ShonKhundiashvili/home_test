<?php
include('./connection.php');

$db = new Database("127.0.0.1", "root", "", "data");


$db->createTable(
    "users",
    "id INT PRIMARY KEY, 
    name VARCHAR(255), 
    email VARCHAR(255), 
    birth DATETIME,
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
        $startDate = strtotime('2000-01-01');
        $endDate = strtotime('2010-12-31');
        $randomDate = mt_rand($startDate, $endDate);
        $dateOfBirth = date('ymd', $randomDate);
        $db->insert("users", [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "birth" => $dateOfBirth,
            "active" => ($user["id"] % 2 == 0) ? 1 : 0,
        ]);
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
