<?php
$imageUrl = 'https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';
$directoryPath = '/Applications/XAMPP/xamppfiles/htdocs/inManage';
$savePath = $directoryPath . '/savedImg.jpg';

if (!file_exists($savePath)) {
    $imageContent = file_get_contents($imageUrl);

    if ($imageContent !== false) {
        $result = file_put_contents($savePath, $imageContent);

        if ($result !== false) {
            echo "Image is saved!";
        } else {
            echo "Failed saving image";
        }
    } else {
        echo "Failed to fetch the image content";
    }
}

$mysqli = new mysqli("127.0.0.1", "root", "", "data");

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$query = "SELECT u.name as name, p.title as title, p.body as body
FROM users u JOIN posts p ON p.user_id = u.id
WHERE u.active = 1";

$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>{$row['name']}</h3>";
        echo "<h3>{$row['title']}</h3>";
        echo "<h3>{$row['body']}</h3>";
        echo '<img src="savedImg.jpg" alt="User Image" width="100" height="100">';

        echo "</div>";
    }
    $result->free();
} else {
    echo "Query failed: " . $mysqli->error;
}

$mysqli->close();
