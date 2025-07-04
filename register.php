<?php
session_start();
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = mysqli_connect("localhost", "root", "9932", "devcollab");
  if (!$conn) {
    $feedback = "Database connection failed.";
  } else {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $feedback = "Username or email already exists.";
    } else {
      // Hash password
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed);
      if ($stmt->execute()) {
        $_SESSION['id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
      } else {
        $feedback = "Registration failed. Please try again.";
      }
    }
    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en"></html>
<head>
  <meta charset="UTF-8">
  <title>Register - DevCollab</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(120deg, #1e3c72 0%, #2a5298 100%);
      font-family: 'Montserrat', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .glass-card {
      background: rgba(255,255,255,0.15);
      border-radius: 1.5rem;
      box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.18);
      padding: 2.5rem 2rem;
      margin-top: 2rem;
    }
    .logo {
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 2px;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .logo-icon {
      font-size: 2.2rem;
      color: #2a5298;
      background: #fff;
      border-radius: 50%;
      padding: 0.3rem 0.5rem;
      box-shadow: 0 2px 8px rgba(42,82,152,0.15);
    }
    .glass-card h2 {
      color: #2a5298;
      font-weight: 700;
      margin-bottom: 1.5rem;
      letter-spacing: 1px;
    }
    .form-label {
      color: #2a5298;
      font-weight: 500;
    }
    .form-control {
      border-radius: 0.7rem;
      border: 1px solid #e0e0e0;
      background: rgba(255,255,255,0.7);
      font-size: 1rem;
    }
    .form-control:focus {
      border-color: #2a5298;
      box-shadow: 0 0 0 0.2rem rgba(42,82,152, .15);
      background: #fff;
    }
    .btn-primary {
      background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
      border: none;
      border-radius: 0.7rem;
      font-weight: 600;
      font-size: 1.1rem;
      letter-spacing: 1px;
      transition: background 0.3s;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #2a5298 0%, #1e3c72 100%);
    }
    .feedback {
      color: #d9534f;
      background: rgba(255,255,255,0.7);
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 500;
    }
    .text-link {
      color: #2a5298;
      text-decoration: underline;
      font-weight: 500;
    }
    @media (max-width: 576px) {
      .glass-card {
        padding: 1.5rem 0.7rem;
      }
      .logo {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="glass-card">
          <div class="logo">
            <span class="logo-icon">💻</span> DevCollab
          </div>
          <h2 class="text-center">Create Account</h2>
          <?php if ($feedback): ?>
            <div class="feedback"><?= htmlspecialchars($feedback) ?></div>
          <?php endif; ?>
          <form method="post" autocomplete="off">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">Register</button>
            </div>
          </form>
          <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php" class="text-link">Login</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>