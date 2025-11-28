<?php
header('Content-Type: application/json; charset=utf-8');
include 'config.php';

$halls = [
    'Mini Convention',
    'Ampitheater',
    'Nieto Hall',
    'Rico Fajardo',
    'Rico Fajardo Hall'
];

$out = [];
foreach ($halls as $h) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM room WHERE TRIM(LOWER(type)) = TRIM(LOWER(?))");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'db prepare failed', 'mysqli_error' => $conn->error]);
        exit;
    }
    $stmt->bind_param('s', $h);
    $stmt->execute();
    $r = $stmt->get_result();
    $total = 0;
    if ($r && ($row = $r->fetch_assoc())) $total = (int)$row['total'];
    $stmt->close();
    $out[$h] = $total;
}

// also include a quick sample of confirmed bookings per hall
$booked = [];
foreach (['Mini Convention','Ampitheater','Nieto Hall','Rico Fajardo'] as $h) {
    $s = $conn->prepare("SELECT COALESCE(SUM(CAST(NoofRoom AS UNSIGNED)),0) AS booked FROM roombook WHERE TRIM(LOWER(RoomType)) = TRIM(LOWER(?)) AND stat = 'Confirm'");
    if ($s) {
        $s->bind_param('s', $h);
        $s->execute();
        $rr = $s->get_result();
        $bk = 0; if ($rr && ($rrr = $rr->fetch_assoc())) $bk = (int)$rrr['booked'];
        $s->close();
    } else { $bk = null; }
    $booked[$h] = $bk;
}

echo json_encode(['success' => true, 'totals' => $out, 'booked' => $booked]);

?>
