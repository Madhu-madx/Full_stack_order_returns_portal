<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Return - Returns Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="content">
    <h2>Submit a Return Request</h2>

    <?php
    // Show error or success messages
    if (isset($_GET['error'])) {
      $errors = [
        'not_found' => 'Order ID not found in our system. Please check and try again.',
        'expired'   => 'Return window has expired. Returns must be submitted within 30 days of purchase.',
        'condition' => 'Items reported as damaged are not eligible for a return.',
        'db'        => 'A database error occurred. Please try again.',
      ];
      $msg = $errors[$_GET['error']] ?? 'An unexpected error occurred.';
      echo "<div class='alert alert-error'>$msg</div>";
    }
    if (isset($_GET['success'])) {
      $rid = htmlspecialchars($_GET['success']);
      echo "<div class='alert alert-success'>Return request submitted successfully! Your Return ID is <strong>$rid</strong>. Use this to track your request.</div>";
    }
    ?>

    <form method="POST" action="submit_return.php">

      <div class="form-group">
        <label for="order_id">Order ID</label>
        <input type="text" id="order_id" name="order_id" required
               placeholder="e.g. ORD001"
               value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>">
      </div>

      <div class="form-group">
        <label for="reason">Reason for Return</label>
        <textarea id="reason" name="reason" required rows="4"
                  placeholder="Describe why you want to return this item..."></textarea>
      </div>

      <div class="form-group">
        <label for="item_condition">Item Condition</label>
        <select id="item_condition" name="item_condition" required>
          <option value="">-- Select condition --</option>
          <option value="unopened">Unopened</option>
          <option value="opened">Opened but intact</option>
          <option value="damaged">Damaged</option>
        </select>
      </div>

      <button type="submit" class="btn btn-pink">Submit Return Request</button>
    </form>

    <p style="margin-top:20px; color:#888; font-size:0.9rem;">
      <strong>Note:</strong> Returns are accepted within 30 days of purchase. Damaged items are not eligible for returns.
    </p>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
