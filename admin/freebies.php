<?php
session_start();
include '../config.php';

// handle add new
if (isset($_POST['add_freebie'])) {
    $room_type = trim($_POST['room_type']);
    $item = trim($_POST['item']);
    $quantity = trim($_POST['quantity']);

    if ($room_type !== '' && $item !== '') {
        $stmt = $conn->prepare("INSERT INTO freebies (room_type, item, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $room_type, $item, $quantity);
        $stmt->execute();
        header('Location: freebies.php');
        exit;
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Freebies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="p-4">
    <div class="container">
      <h1 class="mb-3">Manage Freebies</h1>

      <div class="card mb-4">
        <div class="card-body">
          <form method="POST" class="row g-2 align-items-center">
            <div class="col-md-3">
              <select name="room_type" class="form-control" required>
                <option value="">Select hall</option>
                <option value="Mini Convention">Mini Convention</option>
                <option value="Ampitheater">Ampitheater</option>
                <option value="Nieto Hall">Nieto Hall</option>
                <option value="Rico Fajardo">Rico Fajardo</option>
              </select>
            </div>
            <div class="col-md-5">
              <input name="item" class="form-control" placeholder="Item name (e.g. Projector)" required>
            </div>
            <div class="col-md-3">
              <input name="quantity" class="form-control" placeholder="Qty / notes (optional)">
            </div>
            <div class="col-md-1">
              <button class="btn btn-primary" name="add_freebie">Add</button>
            </div>
          </form>
        </div>
      </div>

      <h2 class="h5">Existing freebies</h2>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr><th>ID</th><th>Hall</th><th>Item</th><th>Quantity</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM freebies ORDER BY room_type, id");
            while ($r = mysqli_fetch_assoc($res)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($r['id']) . '</td>';
                echo '<td>' . htmlspecialchars($r['room_type']) . '</td>';
                echo '<td>' . htmlspecialchars($r['item']) . '</td>';
                echo '<td>' . htmlspecialchars($r['quantity']) . '</td>';
                echo '<td><a class="btn btn-sm btn-outline-secondary me-1" href="freebies_edit.php?id=' . urlencode($r['id']) . '">Edit</a>';
                echo '<a class="btn btn-sm btn-outline-danger" href="freebies_delete.php?id=' . urlencode($r['id']) . '" onclick="return confirm(\'Delete this item?\')">Delete</a></td>';
                echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  </body>
</html>
