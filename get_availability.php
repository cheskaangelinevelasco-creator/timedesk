<?php
header('Content-Type: application/json; charset=utf-8');
include 'config.php';

$room = isset($_REQUEST['room']) ? trim($_REQUEST['room']) : '';
$cin = isset($_REQUEST['cin']) ? trim($_REQUEST['cin']) : '';
$cout = isset($_REQUEST['cout']) ? trim($_REQUEST['cout']) : '';

if (empty($room) || empty($cin) || empty($cout)) {
    echo json_encode(['success' => false, 'error' => 'Missing room, check-in or check-out date']);
    exit;
}

// Basic date validation
if (!strtotime($cin) || !strtotime($cout) || $cin >= $cout) {
    echo json_encode(['success' => false, 'error' => 'Invalid or reversed dates (cin must be before cout)']);
    exit;
}

$roomParam = $room; // keep original for advanced fallbacks
// total number of rooms of this type (case-insensitive, trimmed)
$sql_total = "SELECT COUNT(*) AS total FROM room WHERE TRIM(LOWER(type)) = TRIM(LOWER(?))";
$stmt = $conn->prepare($sql_total);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare error']);
    exit;
}
$stmt->bind_param('s', $room);
$stmt->execute();
$res = $stmt->get_result();
$total = 0;
if ($res && ($r = $res->fetch_assoc())) $total = (int)$r['total'];
$stmt->close();

// If we didn't find any matching inventory for the provided room name, try a common normalization
// (strip trailing "Hall" suffix) to support slightly different labels.
if ($total === 0) {
    $altRoom = preg_replace('/\s*Hall\s*$/i', '', $room);
    if ($altRoom !== $room) {
        $stmtAlt = $conn->prepare($sql_total);
        if ($stmtAlt) {
            $stmtAlt->bind_param('s', $altRoom);
            $stmtAlt->execute();
            $rAlt = $stmtAlt->get_result();
            if ($rAlt && ($ra = $rAlt->fetch_assoc())) $total = (int)$ra['total'];
            $stmtAlt->close();
            // use the normalized label for subsequent queries
            $room = $altRoom;
        }
    }
}

// Still nothing? try a flexible lookup (substring match) and compute totals/booked for matching types
// prepare booked vars — we may compute booked using either exact match or the flexible lookup
// track matched types for additional debugging (set if flexible lookup found several types)
$booked = 0;
$bookedComputed = false;
$matchedTypes = [];
if ($total === 0) {
    $sql_match_types = "SELECT DISTINCT type FROM room WHERE LOWER(type) LIKE CONCAT('%', LOWER(?), '%') OR LOWER(?) LIKE CONCAT('%', LOWER(type), '%')";
    $stmtMatch = $conn->prepare($sql_match_types);
    if ($stmtMatch) {
        $stmtMatch->bind_param('ss', $roomParam, $roomParam);
        $stmtMatch->execute();
        $resMatch = $stmtMatch->get_result();
        $types = [];
        while ($r = $resMatch->fetch_assoc()) $types[] = $r['type'];
        $matchedTypes = $types;
        $stmtMatch->close();

            if (count($types) > 0) {
            // compute total across the matched types (loop to avoid dynamic IN binding issues)
            $total = 0;
            $stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM room WHERE type = ?");
            if ($stmtCount) {
                foreach ($types as $t) {
                    $stmtCount->bind_param('s', $t);
                    $stmtCount->execute();
                    $rCount = $stmtCount->get_result();
                    if ($rCount && ($rc = $rCount->fetch_assoc())) $total += (int)$rc['total'];
                }
                $stmtCount->close();
            }

            // sum booked across each matching RoomType to avoid dynamic parameter binding
            $booked = 0;
            $stmtBookedType = $conn->prepare("SELECT COALESCE(SUM(CAST(NoofRoom AS UNSIGNED)),0) AS booked FROM roombook WHERE RoomType = ? AND stat = 'Confirm' AND NOT (cout <= ? OR cin >= ?)");
            if ($stmtBookedType) {
                foreach ($types as $t) {
                    $stmtBookedType->bind_param('sss', $t, $cin, $cout);
                    $stmtBookedType->execute();
                    $rB = $stmtBookedType->get_result();
                    if ($rB && ($rbval = $rB->fetch_assoc())) $booked += (int)$rbval['booked'];
                }
                $stmtBookedType->close();
                $bookedComputed = true;
            }
        }
    }
}

// If after all fallbacks we still have zero configured rooms, don't reject the check.
// Instead return inventory_enforced=false so the client knows bookings are allowed
// but there is no configured capacity (legacy setups).
$inventory_enforced = $total > 0 ? true : false;

$sql_booked = "SELECT COALESCE(SUM(CAST(NoofRoom AS UNSIGNED)),0) AS booked FROM roombook WHERE TRIM(LOWER(RoomType)) = TRIM(LOWER(?)) AND stat = 'Confirm' AND NOT (cout <= ? OR cin >= ?)";
if (!$bookedComputed) {
    $stmt2 = $conn->prepare($sql_booked);
    if (!$stmt2) {
        echo json_encode(['success' => false, 'error' => 'DB prepare error 2']);
        exit;
    }
    $stmt2->bind_param('sss', $room, $cin, $cout);
    $stmt2->execute();
    $r2 = $stmt2->get_result();
    $booked = 0;
    if ($r2 && ($row2 = $r2->fetch_assoc())) $booked = (int)$row2['booked'];
    $stmt2->close();
}

$matchedTypesStr = implode(', ', $matchedTypes);

// compute available and clamp to zero
$available = $total - $booked;
if ($available < 0) $available = 0; // never return negative availability

// write a small debug trace for the query so we can inspect mismatches
try {
    $log = date('c') . " - get_availability: roomParam=" . addslashes($roomParam) . " resolvedRoom=" . addslashes($room) . " matchedTypes=" . addslashes($matchedTypesStr) . " cin={$cin} cout={$cout} total={$total} booked={$booked} available={$available}\n";
    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'roombook_debug.log', $log, FILE_APPEND | LOCK_EX);
    // if some bookings were counted, record the matching booking rows to help trace mis-counts
    if ($booked > 0) {
        $q = $conn->prepare("SELECT id, RoomType, NoofRoom, cin, cout, stat FROM roombook WHERE TRIM(LOWER(RoomType)) = TRIM(LOWER(?)) AND stat = 'Confirm' AND NOT (cout <= ? OR cin >= ?) LIMIT 50");
        if ($q) {
            $q->bind_param('sss', $room, $cin, $cout);
            $q->execute();
            $r = $q->get_result();
            while ($row = $r->fetch_assoc()) {
                $dbgRow = sprintf("roombook[id=%s, RoomType=%s, NoofRoom=%s, cin=%s, cout=%s, stat=%s]", $row['id'], $row['RoomType'], $row['NoofRoom'], $row['cin'], $row['cout'], $row['stat']);
                file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'roombook_debug.log', date('c') . " - matched_booking: " . addslashes($dbgRow) . "\n", FILE_APPEND | LOCK_EX);
            }
            $q->close();
        }
    }
} catch (Exception $e) { /* ignore logging errors */ }

echo json_encode([
    'success' => true,
    'room' => $room,
    'cin' => $cin,
    'cout' => $cout,
    'total_rooms' => $total,
    'booked' => $booked,
    'available' => $available,
    'available_now' => $available > 0 ? true : false,
    'inventory_enforced' => $inventory_enforced,
]);

?>
