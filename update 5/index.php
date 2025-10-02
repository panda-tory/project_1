  <?php
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get old input and errors from session
$oldInput = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
$success = $_SESSION['success'] ?? '';

// Clear session messages after displaying
unset($_SESSION['old_input'], $_SESSION['form_errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Registration</title>
  <style>
    /* Your existing CSS */
    .error {
      color: #ff6b6b;
      font-size: 0.85em;
      margin-top: 5px;
      display: block;
    }
    .success {
      color: #51cf66;
      background: rgba(81, 207, 102, 0.1);
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }
    input.error-field {
      border-color: #ff6b6b;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="cta">CITE</h2>
    
    <?php if (!empty($success)): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form id="registerForm" action="submit.php" method="POST" class="form-box">
      <h1>Student Registration</h1>
      
      <!-- CSRF Token -->
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      
      <!-- Display form errors -->
      <?php if (!empty($errors)): ?>
        <div style="color: #ff6b6b; background: rgba(255,107,107,0.1); padding: 10px; border-radius: 5px; margin-bottom: 15px;">
          <strong>Please fix the following errors:</strong>
          <ul style="margin: 5px 0 0 20px;">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <label for="firstName">Firstname *</label>
      <input type="text" id="firstName" name="firstName" 
             value="<?= htmlspecialchars($oldInput['firstName'] ?? '') ?>" 
             placeholder="Firstname" required />
      <span class="error" id="firstNameError"></span>

      <label for="middleName">Middlename</label>
      <input type="text" id="middleName" name="middleName" 
             value="<?= htmlspecialchars($oldInput['middleName'] ?? '') ?>" 
             placeholder="Middlename" />

      <label for="lastname">Lastname *</label>
      <input type="text" id="lastname" name="lastname" 
             value="<?= htmlspecialchars($oldInput['lastname'] ?? '') ?>" 
             placeholder="Lastname" required />
      <span class="error" id="lastnameError"></span>

      <label for="suffix">Suffix</label>
      <input type="text" id="suffix" name="suffix" 
             value="<?= htmlspecialchars($oldInput['suffix'] ?? '') ?>" 
             placeholder="Suffix" />

      <label for="email">Email *</label>
      <input type="email" id="email" name="email" 
             value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" 
             placeholder="Your Email" required />
      <span class="error" id="emailError"></span>

      <label for="phone">Contact Number *</label>
      <input type="tel" id="phone" name="phone" 
             value="<?= htmlspecialchars($oldInput['phone'] ?? '') ?>" 
             pattern="^(?:\+63|0)\d{10}$" 
             placeholder="+63XXXXXXXXX or 0XXXXXXXXX" required />
      <span class="error" id="phoneError"></span>

      <label for="idNumber">ID Number *</label>
      <input type="text" id="idNumber" name="idNumber" 
             value="<?= htmlspecialchars($oldInput['idNumber'] ?? '') ?>" 
             pattern="^\d{4}-\d{2}-\d{3}$" 
             placeholder="XXXX-XX-XXX" required />
      <span class="error" id="idNumberError"></span>

      <label for="Batch">Batch *</label>
      <select id="Batch" name="Batch" required>
        <option value="">Select Batch</option>
        <option value="B33" <?= ($oldInput['Batch'] ?? '') === 'B33' ? 'selected' : '' ?>>B33</option>
        <option value="B34" <?= ($oldInput['Batch'] ?? '') === 'B34' ? 'selected' : '' ?>>B34</option>
        <option value="B35" <?= ($oldInput['Batch'] ?? '') === 'B35' ? 'selected' : '' ?>>B35</option>
      </select>
      <span class="error" id="batchError"></span>

      <label for="Technology">Technology *</label>
      <select id="Technology" name="Technology" required>
        <option value="">Select Technology</option>
        <option value="Computer Engineering Technology" <?= ($oldInput['Technology'] ?? '') === 'Computer Engineering Technology' ? 'selected' : '' ?>>Computer Engineering Technology</option>
        <option value="Mechanical Engineering Technology" <?= ($oldInput['Technology'] ?? '') === 'Mechanical Engineering Technology' ? 'selected' : '' ?>>Mechanical Engineering Technology</option>
        <option value="Electrical Engineering Technology" <?= ($oldInput['Technology'] ?? '') === 'Electrical Engineering Technology' ? 'selected' : '' ?>>Electrical Engineering Technology</option>
        <option value="Electronics Engineering Technology" <?= ($oldInput['Technology'] ?? '') === 'Electronics Engineering Technology' ? 'selected' : '' ?>>Electronics Engineering Technology</option>
      </select>
      <span class="error" id="technologyError"></span>

      <label class="checkbox">
        <input type="checkbox" name="terms" required <?= isset($oldInput['terms']) ? 'checked' : '' ?> />
        I agree to Terms of Service & Privacy Policy
      </label>
      <span class="error" id="termsError"></span>

      <button type="submit" class="register-btn">Submit Registration</button>
    </form>

    <footer>Designed by JLA</footer>
  </div>

  <script>
    // Real-time validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      let isValid = true;
      
      // Clear previous errors
      document.querySelectorAll('.error').forEach(el => el.textContent = '');
      document.querySelectorAll('input, select').forEach(el => el.classList.remove('error-field'));
      
      // Validate required fields
      const requiredFields = ['firstName', 'lastname', 'email', 'phone', 'idNumber', 'Batch', 'Technology'];
      requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
          document.getElementById(field + 'Error').textContent = 'This field is required';
          element.classList.add('error-field');
          isValid = false;
        }
      });
      
      // Email validation
      const email = document.getElementById('email');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email.value && !emailRegex.test(email.value)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address';
        email.classList.add('error-field');
        isValid = false;
      }
      
      // Phone validation
      const phone = document.getElementById('phone');
      const phoneRegex = /^(?:\+63|0)\d{10}$/;
      if (phone.value && !phoneRegex.test(phone.value)) {
        document.getElementById('phoneError').textContent = 'Phone must be in format: +63XXXXXXXXX or 0XXXXXXXXX';
        phone.classList.add('error-field');
        isValid = false;
      }
      
      // ID Number validation
      const idNumber = document.getElementById('idNumber');
      const idRegex = /^\d{4}-\d{2}-\d{3}$/;
      if (idNumber.value && !idRegex.test(idNumber.value)) {
        document.getElementById('idNumberError').textContent = 'ID Number must be in format: XXXX-XX-XXX';
        idNumber.classList.add('error-field');
        isValid = false;
      }
      
      // Terms validation
      const terms = document.querySelector('input[name="terms"]');
      if (!terms.checked) {
        document.getElementById('termsError').textContent = 'You must agree to the terms and conditions';
        isValid = false;
      }
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>