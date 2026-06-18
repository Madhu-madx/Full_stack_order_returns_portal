<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Check Return Status - Returns Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'nav.php'; ?>
  <?php include 'db.php'; ?>

  <div class="content">
    <h2>Check Return Status</h2>
    <p style="margin-bottom:20px; color:#666;">Enter your Order ID to view all associated return requests.</p>

    <form method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <input type="text" name="order_id"
             placeholder="Enter Order ID (e.g. ORD001)"
             value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>"
             style="flex:1; min-width:200px; padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:1rem;">
      <button type="submit" class="btn btn-pink">Search</button>
    </form>

    <?php
    if (!empty($_GET['order_id'])) {
      $oid  = trim($_GET['order_id']);
      $stmt = $conn->prepare("SELECT * FROM return_requests WHERE order_id = ? ORDER BY submitted_at DESC");
      $stmt->bind_param("s", $oid);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
        echo "<div class='alert alert-error' style='margin-top:20px;'>No return requests found for Order ID <strong>" . htmlspecialchars($oid) . "</strong>.</div>";
      } else {
        echo "<table class='returns-table'>";
        echo "<thead><tr>
                <th>Return ID</th>
                <th>Item</th>
                <th>Purchase Date</th>
                <th>Submitted On</th>
                <th>Condition</th>
                <th>Status</th>
              </tr></thead><tbody>";

        while ($row = $result->fetch_assoc()) {
          $rid   = "RET" . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
          $badge = "badge-" . $row['status'];
          echo "<tr>
                  <td><strong>$rid</strong></td>
                  <td>" . htmlspecialchars($row['item_name']) . "</td>
                  <td>" . date('d M Y', strtotime($row['purchase_date'])) . "</td>
                  <td>" . date('d M Y', strtotime($row['submitted_at'])) . "</td>
                  <td>" . ucfirst($row['item_condition']) . "</td>
                  <td><span class='badge $badge'>" . ucfirst($row['status']) . "</span></td>
                </tr>";
        }
        echo "</tbody></table>";
      }
    }
    ?>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
