<?php
header('Content-Type: application/json; charset=utf-8');
include 'config.php';

$room = isset($_REQUEST['room']) ? trim($_REQUEST['room']) : '';
if (empty($room)) {
    echo json_encode(['success' => false, 'error' => 'Missing room']);
    exit;
}

// Return confirmed bookings (cin, cout) starting from today - useful for showing booked ranges
// Try case-insensitive exact match first
$sql = "SELECT cin, cout FROM roombook WHERE TRIM(LOWER(RoomType)) = TRIM(LOWER(?)) AND stat = 'Confirm' AND cout >= CURDATE() ORDER BY cin ASC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare failed']);
    exit;
}
$stmt->bind_param('s', $room);
$stmt->execute();
$result = $stmt->get_result();
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = ['cin' => $row['cin'], 'cout' => $row['cout']];
}
$stmt->close();

// If nothing found, try a normalization (strip trailing 'Hall') and then a substring match
if (count($items) === 0) {
    $alt = preg_replace('/\s*Hall\s*$/i', '', $room);
    if ($alt !== $room) {
        $stmt2 = $conn->prepare("SELECT cin, cout FROM roombook WHERE TRIM(LOWER(RoomType)) = TRIM(LOWER(?)) AND stat = 'Confirm' AND cout >= CURDATE() ORDER BY cin ASC");
        if ($stmt2) {
            $stmt2->bind_param('s', $alt);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            while ($r = $res2->fetch_assoc()) $items[] = ['cin' => $r['cin'], 'cout' => $r['cout']];
            $stmt2->close();
        }
    }
}

if (count($items) === 0) {
    // flexible substring match against room types then fetch bookings for those types
    $stmtMatch = $conn->prepare("SELECT DISTINCT RoomType FROM roombook WHERE LOWER(RoomType) LIKE CONCAT('%', LOWER(?), '%') OR LOWER(?) LIKE CONCAT('%', LOWER(RoomType), '%')");
    if ($stmtMatch) {
        $stmtMatch->bind_param('ss', $room, $room);
        $stmtMatch->execute();
        $resMatch = $stmtMatch->get_result();
        $mtypes = [];
        while ($r = $resMatch->fetch_assoc()) $mtypes[] = $r['RoomType'];
        $stmtMatch->close();
        if (count($mtypes) > 0) {
            $stmtB = $conn->prepare("SELECT cin, cout FROM roombook WHERE RoomType = ? AND stat = 'Confirm' AND cout >= CURDATE() ORDER BY cin ASC");
            if ($stmtB) {
                foreach ($mtypes as $mt) {
                    $stmtB->bind_param('s', $mt);
                    $stmtB->execute();
                    $rB = $stmtB->get_result();
                    while ($rb = $rB->fetch_assoc()) $items[] = ['cin' => $rb['cin'], 'cout' => $rb['cout']];
                }
                $stmtB->close();
            }
        }
    }
}

// debug trace
try {
    $dbg = date('c') . " - get_booked_ranges: room=" . addslashes($room) . " found=" . count($items) . "\n";
    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'roombook_debug.log', $dbg, FILE_APPEND | LOCK_EX);
} catch (Exception $e) {}

echo json_encode(['success' => true, 'room' => $room, 'items' => $items]);

?>
