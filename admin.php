<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Returns Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'nav.php'; ?>
  <?php include 'db.php'; ?>

  <?php
  // Handle approve / reject action
  if (isset($_GET['action']) && isset($_GET['id'])) {
    $id     = intval($_GET['id']);
    $action = ($_GET['action'] === 'approve') ? 'approved' : 'rejected';
    $stmt   = $conn->prepare("UPDATE return_requests SET status = ?, reviewed_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $action, $id);
    $stmt->execute();
    header('Location: admin.php?done=' . $action);
    exit;
  }
  ?>

  <div class="content">
    <h2>Admin Dashboard</h2>
    <p style="color:#666; margin-bottom:20px;">Review and process all pending return requests.</p>

    <?php
    if (isset($_GET['done'])) {
      $action = $_GET['done'] === 'approved' ? 'approved' : 'rejected';
      $color  = $action === 'approved' ? 'alert-success' : 'alert-error';
      echo "<div class='alert $color'>Return request has been <strong>" . ucfirst($action) . "</strong>.</div>";
    }

    // Counters
    $counts = $conn->query("SELECT status, COUNT(*) as cnt FROM return_requests GROUP BY status");
    $summary = ['pending'=>0, 'approved'=>0, 'rejected'=>0];
    while ($c = $counts->fetch_assoc()) {
      $summary[$c['status']] = $c['cnt'];
    }
    ?>

    <!-- Summary cards -->
    <div style="display:flex; gap:16px; margin-bottom:28px; flex-wrap:wrap;">
      <div style="flex:1; min-width:140px; background:#fff8e1; border-radius:10px; padding:16px; text-align:center; border:1px solid #ffe082;">
        <div style="font-size:2rem; font-weight:700; color:#f0ad4e;"><?php echo $summary['pending']; ?></div>
        <div style="color:#888; font-weight:600;">Pending</div>
      </div>
      <div style="flex:1; min-width:140px; background:#e8f5e9; border-radius:10px; padding:16px; text-align:center; border:1px solid #a5d6a7;">
        <div style="font-size:2rem; font-weight:700; color:#5cb85c;"><?php echo $summary['approved']; ?></div>
        <div style="color:#888; font-weight:600;">Approved</div>
      </div>
      <div style="flex:1; min-width:140px; background:#fce4ec; border-radius:10px; padding:16px; text-align:center; border:1px solid #f48fb1;">
        <div style="font-size:2rem; font-weight:700; color:#d9534f;"><?php echo $summary['rejected']; ?></div>
        <div style="color:#888; font-weight:600;">Rejected</div>
      </div>
    </div>

    <?php
    $result = $conn->query("SELECT * FROM return_requests ORDER BY FIELD(status,'pending','approved','rejected'), submitted_at DESC");

    if ($result->num_rows === 0) {
      echo "<p style='color:#aaa; margin-top:20px;'>No return requests have been submitted yet.</p>";
    } else {
      echo "<table class='returns-table'>";
      echo "<thead><tr>
              <th>Return ID</th>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Item</th>
              <th>Reason</th>
              <th>Condition</th>
              <th>Submitted</th>
              <th>Status</th>
              <th>Action</th>
            </tr></thead><tbody>";

      while ($row = $result->fetch_assoc()) {
        $rid   = "RET" . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
        $badge = "badge-" . $row['status'];

        echo "<tr>
                <td><strong>$rid</strong></td>
                <td>" . htmlspecialchars($row['order_id']) . "</td>
                <td style='text-align:left;'>" . htmlspecialchars($row['customer_name']) . "</td>
                <td style='text-align:left;'>" . htmlspecialchars($row['item_name']) . "</td>
                <td style='text-align:left; max-width:160px;'>" . htmlspecialchars(substr($row['reason'],0,60)) . (strlen($row['reason'])>60?'...':'') . "</td>
                <td>" . ucfirst($row['item_condition']) . "</td>
                <td>" . date('d M Y', strtotime($row['submitted_at'])) . "</td>
                <td><span class='badge $badge'>" . ucfirst($row['status']) . "</span></td>
                <td>";

        if ($row['status'] === 'pending') {
          echo "<a href='admin.php?action=approve&id={$row['id']}' class='btn btn-green' style='padding:5px 12px; font-size:0.85rem;'>Approve</a>
                &nbsp;
                <a href='admin.php?action=reject&id={$row['id']}' class='btn btn-red' style='padding:5px 12px; font-size:0.85rem;'>Reject</a>";
        } else {
          echo "<span style='color:#aaa; font-size:0.85rem;'>Done</span>";
        }

        echo "  </td>
              </tr>";
      }
      echo "</tbody></table>";
    }
    ?>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
