<?php
// api/posts.php - simple JSON API to fetch posts
require_once __DIR__ . '/../admin/db.php';
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,title,content,created_at FROM posts ORDER BY created_at DESC');
$rows = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode($rows);
?>