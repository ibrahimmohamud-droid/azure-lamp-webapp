<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="card">
  <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
  <div class="msg ok">You are logged in.</div>
  <p><a href="feedback.php">Go to the feedback form</a></p>
  <p><a href="logout.php">Log out</a></p>
</div>
</body>
</html>
