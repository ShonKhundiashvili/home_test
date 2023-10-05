<?php
include('./connection.php');
$db = new Database("127.0.0.1", "root", "", "data");
$mysqli = $db->getConnection();

//Sql query for question number 6
$lastPostQuery = "SELECT u.email email, u.birth birth, p.title title, p.body body, p.created_at created_at
                    FROM users u JOIN posts p ON u.id = p.user_id
                    WHERE month(u.birth) = month(now())
                    AND p.created_at = (SELECT MAX(created_at) FROM posts WHERE user_id = u.id)";


if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query($lastPostQuery);

//Showing the result in web
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>{$row['email']}</h3>";
        echo "<h3>{$row['birth']}</h3>";
        echo "<h3>{$row['title']}</h3>";
        echo "<h3>{$row['body']}</h3>";
        echo "<h3>{$row['created_at']}</h3>";
        echo "</div>";
    }
    $result->free();
} else {
    echo "Query failed: " . $mysqli->error;
}

//Creating the table named question_7 for question number 7
$db->createTable(
    "question_7",
    "date DATETIME,
    time TIME,
    hourly_post_counts INT"
);

//This is a query to insert the desired data into the table with 3 columns (pic of table is included in github)
$insertQuery = "INSERT into question_7 (date, time, hourly_post_counts) 
SELECT
    p.created_at date,
    time(p.created_at) time,
    COUNT(p.body) hourly_post_counts
    from posts p join users u ON p.user_id = u.id group by hour(p.created_at)";

$insert = $mysqli->query($insertQuery);

if ($insert) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $mysqli->error;
}
