<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home - Order Returns Portal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'nav.php'; ?>

  <div class="content">
    <div class="hero">
      <h2>Welcome to the Returns Portal</h2>
      <p>Submit a return request for your recent order, or track the status of an existing return.</p>
      <a href="order.php" class="btn btn-pink">Submit a Return</a>
      &nbsp;&nbsp;
      <a href="status.php" class="btn btn-outline">Check Return Status</a>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #f0e0e8;">

    <h3 style="color:#ff4fa1; font-size:1.2rem; margin-bottom:12px;">How it works</h3>
    <ol style="padding-left:20px; line-height:2;">
      <li>Enter your <strong>Order ID</strong> on the Submit Return page.</li>
      <li>Provide the reason and your item's condition.</li>
      <li>We validate your eligibility automatically (30-day return window, item condition check).</li>
      <li>Track your return status using the Check Status page.</li>
      <li>Our admin team reviews and approves or rejects your request.</li>
    </ol>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
