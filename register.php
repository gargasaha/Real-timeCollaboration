<?php
session_start();
if (isset($_SESSION["id"]) && $_SESSION["id"]) {
  header("Location: dashboard.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mysqli = new mysqli("localhost", "root", "9932", "devcollab");
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }
  $username = $mysqli->real_escape_string($_POST['username']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $password = $_POST['password'];
  $query = $mysqli->query('SELECT COUNT(*) as count FROM users WHERE username = "' . $username . '" OR email = "' . $email . '"');
  if ($query && $query->num_rows > 0) {
    $row = $query->fetch_assoc();
    if ($row['count'] > 0) {
      $feedback = "Username or email already exists.";
    } else {
      $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
          $_SESSION['id'] = $mysqli->insert_id;
          $_SESSION['username'] = $username;
          header("Location: dashboard.php");
          exit();
        } else {
          $feedback = "Error creating account: " . $stmt->error;
        }
        $stmt->close();
      } else {
        $feedback = "Error preparing statement: " . $mysqli->error;
      }
    }
  } else {
    $feedback = "Error checking existing users: " . $mysqli->error;
  }
  $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register - DevCollab</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(120deg, #1e3c72 0%, #2a5298 100%);
      font-family: 'Montserrat', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }

    .navbar {
      background: rgba(30, 60, 114, 0.7) !important;
      box-shadow: 0 2px 12px rgba(30, 60, 114, 0.08);
      backdrop-filter: blur(8px);
    }

    .navbar .navbar-brand,
    .navbar .nav-link {
      color: #fff !important;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .navbar .nav-link.active {
      text-decoration: underline;
    }

    .container {
      margin-top: 60px;
      margin-bottom: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 70vh;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.18);
      border-radius: 1.5rem;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.22);
      padding: 2.5rem 2rem;
      margin-top: 0;
      width: 100%;
      max-width: 420px;
    }

    .logo {
      font-size: 2.7rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 2px;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      text-shadow: 0 2px 8px rgba(30, 60, 114, 0.12);
    }

    .logo-icon {
      font-size: 2.3rem;
      color: #2a5298;
      background: #fff;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
      box-shadow: 0 2px 8px rgba(42, 82, 152, 0.13);
    }

    .glass-card h2 {
      color: #2a5298;
      font-weight: 700;
      margin-bottom: 1.7rem;
      letter-spacing: 1px;
      text-align: center;
      text-shadow: 0 2px 8px rgba(42, 82, 152, 0.08);
    }

    .form-label {
      color: #2a5298;
      font-weight: 500;
      margin-bottom: 0.3rem;
    }

    .form-control {
      border-radius: 0.7rem;
      border: 1px solid #e0e0e0;
      background: rgba(255, 255, 255, 0.8);
      font-size: 1rem;
      margin-bottom: 1.1rem;
      padding: 0.7rem 1rem;
    }

    .form-control:focus {
      border-color: #2a5298;
      box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, .13);
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
      padding: 0.7rem 0;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #2a5298 0%, #1e3c72 100%);
    }

    .feedback {
      color: #d9534f;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 0.5rem;
      padding: 0.7rem 1.2rem;
      margin-bottom: 1.2rem;
      text-align: center;
      font-weight: 500;
      border: 1px solid #f5c6cb;
      box-shadow: 0 2px 8px rgba(217, 83, 79, 0.07);
    }

    .text-link {
      color: #2a5298;
      text-decoration: underline;
      font-weight: 500;
    }

    @media (max-width: 576px) {
      .glass-card {
        padding: 1.5rem 0.7rem;
        max-width: 98vw;
      }

      .logo {
        font-size: 2rem;
      }

      .container {
        margin-top: 30px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary w-100" style="width:100vw;">
    <div class="container-fluid">
      <a class="navbar-brand" href="/Real-timeCollaboration/default.php">devCollab</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/Real-timeCollaboration/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Real-timeCollaboration/login.php">Sign In</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Real-timeCollaboration/register.php">Sign Up</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="row justify-content-center w-100">
      <div class="col-12 d-flex justify-content-center">
        <div class="glass-card">
          <div class="logo">
            <span class="logo-icon">💻</span> DevCollab
          </div>
          <h2>Create Account</h2>
          <?php if ($feedback): ?>
            <div class="feedback"><?= htmlspecialchars($feedback) ?></div>
          <?php endif; ?>
          <form method="post" action="register.php" autocomplete="off">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
              <button type="button" class="btn btn-warning" id="verifyEmailBtn" onclick="verifyEmail()">Verify
                Email</button>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg" onclick="return f===1;">Register</button>
            </div>
          </form>
          <div class="text-center mt-3">
            <small>Already have an account? <a href="login.php" class="text-link">Login</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    var f=0;
    function verifyEmail() {
      const btn = document.getElementById('verifyEmailBtn');
      btn.disabled = true;
      btn.innerText = "Sending email...";
      btn.insertAdjacentHTML('beforeend', ' <span class="spinner-border spinner-border-sm text-success ms-2" role="status" aria-hidden="true"></span>');
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-success mt-2';
      alertDiv.role = 'alert';
      const email = document.getElementById('email').value;
      const username = document.getElementById('username').value;
      if (!email) {
        alert('Please enter your email address first.');
        btn.disabled = false;
        btn.innerText = "Verify Email";
        return;
      }
      $.ajax({
        url: 'send_email_verification.php',
        type: 'POST',
        dataType: 'json',
        data: { email: email, username: username },
        success: function (response) {
          if(response.error) {
            alertDiv.innerText = response.error;
            document.getElementById('email').parentNode.insertBefore(alertDiv, document.getElementById('email').nextSibling);
            btn.disabled = false;
            btn.innerText = "Verify Email";
            email.innerText = "";
            console.error('Error sending verification email:', response.error);
            console.log('Response:', response);
            return;
          }
          const prevAlert = document.querySelector('.alert-success');
          if (prevAlert) prevAlert.remove();
          alertDiv.innerText = 'A verification email has been sent to ' + email + '. Please check your inbox.';
          const emailInput = document.getElementById('email');
          emailInput.parentNode.insertBefore(alertDiv, emailInput.nextSibling);
          btn.innerText = "Verifying";
          btn.insertAdjacentHTML('beforeend', ' <span class="spinner-border spinner-border-sm text-light ms-2" role="status" aria-hidden="true"></span>');
          console.log('Verification code sent:', response);
          let intervalId = setInterval(() => {
            $.ajax({
              url: 'verify_from_client.php',
              type: 'POST',
              data: { email: email, vid: response.verificationCode },
              success: function (data) {
                if (data === "verified") {
                  alertDiv.innerText = 'Email verified successfully!';
                  btn.innerText = "Verified";
                  btn.disabled = true;
                  f=1;
                  clearInterval(intervalId);
                }
              },
              error: function () {
                alertDiv.innerText = 'Error checking verification status.';
              }
            });
          }, 1000);
        },
        error: function (xhr, status, error) {
          alert('Error sending verification email: ' + xhr.responseText);
          btn.disabled = false;
          btn.innerText = "Verify Email";
        }
      });
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>