<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
require 'db.php';

$message = ""; $class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['message'] ?? '');
    if (!$conn) {
        $message = $db_error; $class = "err";
    } elseif ($text === '') {
        $message = "Please write a message."; $class = "err";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $_SESSION['user'], $text);
        $stmt->execute();
        $message = "Thank you, your feedback was saved."; $class = "ok";
    }
}

$rows = [];
if ($conn) {
    $res = $conn->query("SELECT id, username, message, submitted_at FROM feedback ORDER BY id DESC");
    while ($r = $res->fetch_assoc()) { $rows[] = $r; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Feedback</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="card" style="max-width:640px">
  <h1>Feedback form</h1>
  <p>Logged in as <b><?= htmlspecialchars($_SESSION['user']) ?></b></p>
  <?php if ($message): ?><div class="msg <?= $class ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="POST" action="feedback.php">
    <label>Your feedback</label>
    <textarea name="message" rows="4" required></textarea>
    <button type="submit">Submit</button>
  </form>

  <h2>Saved feedback</h2>
  <table>
    <tr><th>ID</th><th>User</th><th>Message</th><th>Time</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['username']) ?></td>
      <td><?= htmlspecialchars($r['message']) ?></td>
      <td><?= htmlspecialchars($r['submitted_at']) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <p><a href="welcome.php">Back</a> | <a href="logout.php">Log out</a></p>
</div>
</body>
</html>
