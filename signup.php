<?php
require 'db.php';
$message = ""; $class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$conn) {
        $message = $db_error; $class = "err";
    } elseif ($username === '' || $email === '' || strlen($password) < 6) {
        $message = "Fill in all fields. Password must be at least 6 characters."; $class = "err";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, fullname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $fullname, $email, $hash);
            $stmt->execute();
            $message = "Account created for " . htmlspecialchars($username) . ". You can now log in."; $class = "ok";
        } catch (mysqli_sql_exception $e) {
            $message = ($e->getCode() == 1062) ? "That username is already taken." : "Could not create the account."; $class = "err";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign Up</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="card">
  <h1>Create an account</h1>
  <?php if ($message): ?><div class="msg <?= $class ?>"><?= $message ?></div><?php endif; ?>
  <?php if (!$conn && !$message): ?><div class="msg err"><?= htmlspecialchars($db_error) ?></div><?php endif; ?>
  <form method="POST" action="signup.php">
    <label>Username</label><input name="username" required>
    <label>Full name</label><input name="fullname">
    <label>Email</label><input type="email" name="email" required>
    <label>Password</label><input type="password" name="password" required>
    <button type="submit">Sign up</button>
  </form>
  <p class="alt">Already registered? <a href="login.php">Log in</a></p>
</div>
</body>
</html>
