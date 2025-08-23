<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["email"]);
  $password = $_POST["password"];

  $sql = "SELECT * FROM clients WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      header("Location: client_dashboard.php");
      exit();
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "No account found.";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .login-container {
      background: white;
      padding: 30px 40px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 400px;
      max-width: 90%;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group input[type="email"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }
    .form-group input[type="email"]:focus,
    .form-group input[type="password"]:focus {
      border-color: #4CAF50;
      outline: none;
    }
    input[type="submit"] {
      width: 100%;
      background-color: #4CAF50;
      border: none;
      padding: 14px 0;
      border-radius: 5px;
      color: white;
      font-size: 18px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
      background-color: #45a049;
    }
    .error-message {
      color: #d9534f;
      margin-bottom: 15px;
      text-align: center;
    }
    p {
      text-align: center;
      margin-top: 15px;
      color: #555;
    }
    p a {
      color: #4CAF50;
      text-decoration: none;
      font-weight: bold;
    }
    p a:hover {
      text-decoration: underline;
    }

        .home-button {
      background:#eee; 
       color:#333; 
       border:none; 
       padding:10px 20px;
       border-radius:5px;
       cursor:pointer;
       font-size:16px;
       font-weight:bold;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Sign In</h2>
    <?php if ($error): ?>
      <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required />
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required />
      </div>
      <input type="submit" value="Login" />
    </form>
    <p>Don't have an account? <a href="register.php">Register Here</a></p>
      <form action="index.html" method="get" style="text-align:center; margin-top:10px;">
      <button type="submit" class="home-button">
        Back to Home
      </button>
    </form>
  </div>
</body>
</html>
