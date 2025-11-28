<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';

// Accept room name from query param (exact match to room_type values)
$room = isset($_GET['room']) ? trim($_GET['room']) : '';
if ($room === '') {
    // return more detailed error so frontend can display it while debugging
    echo json_encode(['success' => false, 'error' => 'missing room parameter']);
    exit;
}
if ($room === '') {
    echo json_encode(['success' => false, 'error' => 'missing room']);
    exit;
}

try {
    // try case-insensitive exact match first
    $stmt = $conn->prepare("SELECT id, item, quantity FROM freebies WHERE TRIM(LOWER(room_type)) = TRIM(LOWER(?)) ORDER BY id");
    if ($stmt === false) {
        // include db error for diagnosis (not for production)
        echo json_encode(['success' => false, 'error' => 'db prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('s', $room);
    $stmt->execute();
    $res = $stmt->get_result();
    $items = [];
    while ($row = $res->fetch_assoc()) {
        $items[] = $row;
    }
    // if nothing found, try a normalization (strip trailing 'Hall') then a substring match
    if (count($items) === 0) {
        $alt = preg_replace('/\s*Hall\s*$/i', '', $room);
        if ($alt !== $room) {
            $stmt2 = $conn->prepare("SELECT id, item, quantity FROM freebies WHERE TRIM(LOWER(room_type)) = TRIM(LOWER(?)) ORDER BY id");
            if ($stmt2) {
                $stmt2->bind_param('s', $alt);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                while ($r = $res2->fetch_assoc()) $items[] = $r;
                $stmt2->close();
            }
        }
    }

    if (count($items) === 0) {
        // lastly try substring match (safe fallback)
        $stmtMatch = $conn->prepare("SELECT id, item, quantity FROM freebies WHERE LOWER(room_type) LIKE CONCAT('%', LOWER(?), '%') OR LOWER(?) LIKE CONCAT('%', LOWER(room_type), '%') ORDER BY id");
        if ($stmtMatch) {
            $stmtMatch->bind_param('ss', $room, $room);
            $stmtMatch->execute();
            $rm = $stmtMatch->get_result();
            while ($r = $rm->fetch_assoc()) $items[] = $r;
            $stmtMatch->close();
        }
    }

    try { file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'roombook_debug.log', date('c') . " - get_freebies lookup for '" . addslashes($room) . "' found=" . count($items) . "\n", FILE_APPEND | LOCK_EX); } catch (Exception $e) {}

    echo json_encode(['success' => true, 'items' => $items]);
} catch (Exception $e) {
    // Log and return a helpful error message for local debugging
    error_log('get_freebies error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
