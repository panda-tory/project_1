<?php
session_start();

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

$user = $_SESSION['userData'] ?? null;

if (!$user) {
    error_log("No user data found in session for confirmation page");
    
    $_SESSION['error'] = "No registration data found. Please complete the registration form.";
    header("Location: index.php");
    exit();
}

$confirmationId = 'REG-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
$_SESSION['confirmation_id'] = $confirmationId;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Confirmation</title>
  <style>
    
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0d40adff;
      color: white;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      overflow-y: auto;
    }

    .summary-container {
      background-color: #2e2e2e;
      padding: 30px;
      border-radius: 10px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .summary-container h2 {
      text-align: center;
      font-size: 1.8em;
      margin-bottom: 20px;
      color: #69c8ffff;
    }

    .confirmation-id {
      text-align: center;
      background: #1a1a1a;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      font-family: monospace;
    }

    .summary-container ul {
      list-style: none;
      padding: 0;
    }

    .summary-container li {
      background-color: #3a3a3a;
      margin-bottom: 10px;
      padding: 10px 15px;
      border-radius: 5px;
      font-size: 1em;
      color: #fff;
      word-wrap: break-word;
    }

    .action-buttons {
      display: flex;
      gap: 10px;
      margin-top: 30px;
    }

    .done-btn, .print-btn {
      background-color: #41a5d3ff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      font-size: 1em;
      cursor: pointer;
      flex: 1;
      text-align: center;
    }

    .print-btn {
      background-color: #4CAF50;
    }

    @media print {
      body {
        background: white;
        color: black;
        padding: 0;
      }
      .summary-container {
        background: white;
        color: black;
        box-shadow: none;
        max-width: 100%;
      }
      .action-buttons {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="summary-container">
    <h2>Registration Confirmation</h2>
    
    <div class="confirmation-id">
      Confirmation ID: <?= htmlspecialchars($confirmationId) ?>
    </div>
    
    <ul>
      <li><strong>Firstname:</strong> <?= htmlspecialchars($user['firstName']) ?></li>
      <li><strong>Middlename:</strong> <?= htmlspecialchars($user['middleName'] ?? 'N/A') ?></li>
      <li><strong>Lastname:</strong> <?= htmlspecialchars($user['lastname']) ?></li>
      <li><strong>Suffix:</strong> <?= htmlspecialchars($user['suffix'] ?? 'N/A') ?></li>
      <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
      <li><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></li>
      <li><strong>ID Number:</strong> <?= htmlspecialchars($user['idNumber']) ?></li>
      <li><strong>Batch:</strong> <?= htmlspecialchars($user['Batch']) ?></li>
      <li><strong>Technology:</strong> <?= htmlspecialchars($user['Technology']) ?></li>
      <li><strong>Registration Date:</strong> <?= date('F j, Y, g:i a') ?></li>
      <li><strong>Agreed to Terms:</strong> ✅</li>
    </ul>

    <div class="action-buttons">
      <button onclick="printConfirmation()" class="print-btn">Print</button>
      <button onclick="goBack()" class="done-btn">Done</button>
    </div>
  </div>

  <script>
    function printConfirmation() {
      window.print();
    }

    function goBack() {
      fetch('clear-session.php')
        .then(response => {
          if (response.ok) {
            window.location.href = 'index.php';
          } else {
            alert('Error clearing session. Please try again.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Network error. Please try again.');
        });
    }
  </script>
</body>
</html>