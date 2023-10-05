<?php
include('./connection.php');

//Creating instance of the database
$db = new Database("127.0.0.1", "root", "", "data");

//Creating table users
$db->createTable(
    "users",
    "id INT PRIMARY KEY, 
    name VARCHAR(255), 
    email VARCHAR(255), 
    birth DATETIME,
    active BOOLEAN"
);

//Creating table posts
$db->createTable(
    "posts",
    "id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, 
    title TEXT, 
    body TEXT, 
    created_at DATETIME, 
    active BOOLEAN"
);

//This function takes the url that was provided that uses get contents func in order to push data into users table
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

//This function takes the url that was provided that uses get contents func in order to push data into posts table
function pushPosts($db)
{
    $postDataUrl = 'https://jsonplaceholder.typicode.com/posts';
    $postData = file_get_contents($postDataUrl);
    $posts = json_decode($postData, true);


    foreach ($posts as $post) {
        $startDate = strtotime('2013-01-01');
        $endDate = strtotime('2022-12-31');
        $randomTimestamp = mt_rand($startDate, $endDate);
        $randomTime = mt_rand(0, 86399);
        $dateOfCreation = date('Y-m-d H:i:s', $randomTimestamp + $randomTime);


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

//Calling the functions
pushUsers($db);
pushPosts($db);

$db->close();
