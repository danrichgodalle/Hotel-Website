<?php
session_start();
require 'db.php';  // siguraduhing tama path ng database connection file

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
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Coiny&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
      /* Reset & Base */
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

    .input-wrapper input {
      width: 100%;
      padding: 10px 40px 10px 12px; /* padding right for eye icon */
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      transition: border 0.3s ease;
    }

    .input-wrapper input:focus {
      border-color: #007bff;
      outline: none;
    }

    /* Eye Icon */
    .input-wrapper .fa-eye,
    .input-wrapper .fa-eye-slash {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
      font-size: 18px;
      user-select: none;
    }

    /* Submit Button */
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
      transition: background-color 0.3s ease;
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
      transition: background-color 0.3s ease;
    }

    .home-button:hover {
      background-color: #ddd;
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">

      <!-- Social Media -->
      <div class="footer-social d-flex">
        <a href="#"><i class="fab fa-facebook-f me-3" style="color: #3b5998;"></i></a>
        <a href="#"><i class="fab fa-twitter me-3" style="color: #1da1f2;"></i></a>
        <a href="#"><i class="fab fa-linkedin-in me-3" style="color: #0077b5;"></i></a>
        <a href="#"><i class="fab fa-youtube" style="color: #ff0000;"></i></a>
      </div>

      <!-- Hamburger button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto me-4">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="About-us.html">ABOUT</a></li>
          <li class="nav-item"><a class="nav-link" href="Our-room.html">OUR ROOM</a></li>
          <li class="nav-item"><a class="nav-link" href="Gallery.html">GALLERY</a></li>
          <li class="nav-item"><a class="nav-link" href="Blog.html">BLOG</a></li>
          <li class="nav-item"><a class="nav-link" href="Contact-us.html">CONTACT US</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Sign In</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        </ul>
      </div>

    </div>
  </nav>
</header>

<section class="register-section">
  <div class="register-container">
    <h2>Sign In</h2>

    <?php if ($error): ?>
      <p class="message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off" novalidate>
      <!-- Hidden fake inputs to prevent autofill -->
      <input style="display:none" type="text" name="fakeusernameremembered" />
      <input style="display:none" type="password" name="fakepasswordremembered" />

      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            autocomplete="new-password"
            required
          />
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter your password"
            autocomplete="new-password"
            required
          />
          <i class="fa-solid fa-eye" id="togglePassword" title="Show/Hide Password"></i>
        </div>
      </div>

      <button type="submit" class="btn-submit">Login</button>
    </form>

    <p class="bottom-text">Don't have an account? <a href="register.php">Register Here</a></p>

    <form action="index.html" method="get">
      <button type="submit" class="home-button">Back to Home</button>
    </form>
  </div>
</section>

<script>
  // Clear inputs on page load para hindi mag-retain ang value
  window.onload = function() {
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
  };

  // Toggle password visibility
  const togglePassword = document.querySelector("#togglePassword");
  const passwordInput = document.querySelector("#password");

  togglePassword.addEventListener("click", function () {
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);

    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</body>
</html>
