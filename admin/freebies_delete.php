<?php
session_start();
include '../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM freebies WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: freebies.php');
exit;
