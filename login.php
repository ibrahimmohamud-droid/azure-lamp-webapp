<?php
session_start();
require 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$conn) {
        $message = $db_error;
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row && password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $username;
            header("Location: welcome.php");
            exit;
        }
        $message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log In</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="card">
  <h1>Log in</h1>
  <?php if ($message): ?><div class="msg err"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <form method="POST" action="login.php">
    <label>Username</label><input name="username" required>
    <label>Password</label><input type="password" name="password" required>
    <button type="submit">Log in</button>
  </form>
  <p class="alt">No account? <a href="signup.php">Sign up</a></p>
</div>
</body>
</html>
