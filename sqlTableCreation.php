<?php

$mysqli = new mysqli("127.0.0.1", "root", "", "data");

$lastPostQuery = "  SELECT u.email email, u.birth birth, p.title title, p.body body, p.created_at created_at
                    FROM users u JOIN posts p ON u.id = p.user_id
                    WHERE month(u.birth) = month(now())
                    AND p.created_at = (SELECT MAX(created_at) FROM posts WHERE user_id = u.id)";

$result = $mysqli->query($lastPostQuery);

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
