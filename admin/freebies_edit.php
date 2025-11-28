<?php
session_start();
include '../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: freebies.php');
    exit;
}

if (isset($_POST['save'])) {
    $item = trim($_POST['item']);
    $quantity = trim($_POST['quantity']);
    $room_type = trim($_POST['room_type']);
    $stmt = $conn->prepare("UPDATE freebies SET room_type = ?, item = ?, quantity = ? WHERE id = ?");
    $stmt->bind_param('sssi', $room_type, $item, $quantity, $id);
    $stmt->execute();
    header('Location: freebies.php');
    exit;
}

$row = null;
$res = mysqli_query($conn, "SELECT * FROM freebies WHERE id = " . intval($id));
if ($res) {
    $row = mysqli_fetch_assoc($res);
}
if (!$row) {
    header('Location: freebies.php');
    exit;
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit freebie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="p-4">
    <div class="container">
      <h1 class="mb-3">Edit freebie #<?php echo htmlspecialchars($row['id']); ?></h1>
      <form method="POST" class="row g-3">
        <div class="col-md-4">
          <select name="room_type" class="form-control" required>
            <option value="Mini Convention" <?php if($row['room_type']==='Mini Convention') echo 'selected'; ?>>Mini Convention</option>
            <option value="Ampitheater" <?php if($row['room_type']==='Ampitheater') echo 'selected'; ?>>Ampitheater</option>
            <option value="Nieto Hall" <?php if($row['room_type']==='Nieto Hall') echo 'selected'; ?>>Nieto Hall</option>
            <option value="Rico Fajardo" <?php if($row['room_type']==='Rico Fajardo') echo 'selected'; ?>>Rico Fajardo</option>
          </select>
        </div>
        <div class="col-md-5">
          <input name="item" value="<?php echo htmlspecialchars($row['item']); ?>" class="form-control" required>
        </div>
        <div class="col-md-2">
          <input name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" class="form-control" placeholder="Qty/notes">
        </div>
        <div class="col-md-1">
          <button name="save" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </body>
</html>
