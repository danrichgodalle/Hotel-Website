<?php
session_start();
require 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $confirm = $_POST["confirm_password"];

  if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
    $error = "Please fill out all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif ($password !== $confirm) {
    $error = "Passwords do not match.";
  } else {
    $check_sql = "SELECT id FROM clients WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
      $error = "Email is already registered.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);

      $sql = "INSERT INTO clients (username, email, password) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $username, $email, $hashed);

      if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['success_message'] = "Registration successful. Welcome, $username!";
        header("Location: client_dashboard.php");
        exit();
      } else {
        $error = "Error saving data: " . $stmt->error;
      }

      $stmt->close();
    }

    $check_stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Coiny&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
   /* Reset and Base Styles */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html, body {
  height: 100%;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f5f5;
}

/* Section Background */
.register-section {
  position: relative;
  width: 100%;
  height: 100vh;
  background: url('image/carrie-hotel-main.png') no-repeat center center/cover;
}

.register-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.45); /* Overlay */
  z-index: 0;
}

/* Register Container */
.register-container {
  position: absolute;
  top: 50%;
  left: 25%;
  transform: translate(-50%, -50%);
  background-color: rgba(255, 255, 255, 0.95);
  padding: 30px 35px;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  width: 500px;
  max-width: 90%;
  z-index: 1;
}

/* Heading */
h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #660B05;
  font-family: "Coiny", system-ui;
}

/* Form Group */
.form-group {
  margin-bottom: 15px;
  position: relative;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  color: #333;
}

/* Input with Icon */
.input-wrapper {
  position: relative;
}

.input-wrapper i {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  color: #888;
  font-size: 16px;
}

.input-wrapper input {
  width: 100%;
  padding: 10px 12px 10px 38px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  transition: border 0.3s ease;
}

.input-wrapper input:focus {
  border-color: #007bff;
  outline: none;
}

/* Button */
.btn-submit {
  width: 100%;
  padding: 12px;
  background-color: #660B05;
  border: none;
  border-radius: 15px;
  color: white;
  font-size: 20px;
  cursor: pointer;
  margin-top: 10px;
}

.btn-submit:hover {
  background-color: #0056b3;
}

/* Messages & Links */
.message {
  text-align: center;
  margin-bottom: 15px;
  color: red;
  font-weight: 500;
}

.bottom-text {
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
}

.bottom-text a {
  color: #007bff;
  text-decoration: none;
}

.bottom-text a:hover {
  text-decoration: underline;
}

/* Home Button */
.home-button {
  background: #eee;
  color: #333;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  margin-top: 10px;
  display: block;
  width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
  .register-container {
    position: static;
    transform: none;
    width: 90%;
    margin: 40px auto;
  }

  .register-section::before {
    background: rgba(0, 0, 0, 0.3);
  }
}

  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-black">
      <div class="container-fluid">
        <div class="footer-social">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navlist">
          <ul class="navbar-nav me-4">
            <li class="nav-item">
              <a class="nav-link "href="index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="About-us.html">ABOUT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Our-room.html">OUR HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Gallery.html">GALLERY</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Blog.html">BLOG</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Contact-us.html">CONTACT US</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Sign In</a>
            </li>
              <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header>
  <div class="register-section">
    <div class="register-container">
      <h2>Create New Account</h2>

      <!-- PHP error message -->
      <?php if (isset($error) && $error): ?>
        <div class="message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
<form method="POST" action="">
  <div class="form-group">
    <label for="username">Username:</label>
    <div class="input-wrapper">
      <i class="fas fa-user"></i>
      <input type="text" id="username" name="username" required />
    </div>
  </div>

  <div class="form-group">
    <label for="email">Email Address:</label>
    <div class="input-wrapper">
      <i class="fas fa-envelope"></i>
      <input type="email" id="email" name="email" required />
    </div>
  </div>

  <div class="form-group">
    <label for="password">Password:</label>
    <div class="input-wrapper">
      <i class="fas fa-lock"></i>
      <input type="password" id="password" name="password" required />
    </div>
  </div>

  <div class="form-group">
    <label for="confirm_password">Confirm Password:</label>
    <div class="input-wrapper">
      <i class="fas fa-lock"></i>
      <input type="password" id="confirm_password" name="confirm_password" required />
    </div>
  </div>

  <button type="submit" class="btn-submit">Register</button>
</form>


      <div class="bottom-text">
        Already have an account? <a href="login.php">Sign in</a>
      </div>

      <form action="index.html" method="get" style="text-align:center;">
        <button type="submit" class="home-button">Back to Home</button>
      </form>
    </div>
  </div>
</body>
</html>

