<?php $event = $_GET['event'] ?? ''; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register for Event</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #f0f4ff, #d9e4ff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border: none;
      border-radius: 15px;
      padding: 30px;
      background-color: #ffffff;
    }
    .event-image {
      width: 100%;
      height: 300px;
      object-fit: contain;
      border-radius: 15px 15px 0 0;
    }
    h2 {
      font-weight: bold;
      color: #2c3e50;
    }
    label {
      font-weight: 500;
    }
    .btn-success {
      width: 100%;
      font-weight: bold;
      padding: 10px;
      border-radius: 8px;
    }
    .text-danger {
      font-size: 0.875rem;
      display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">

          <img src="images/college_logo.png" alt="Event Image" class="event-image">

          <h2 class="mb-4 text-center">Register for "<?php echo htmlspecialchars($event); ?>"</h2>
          <form action="register_event.php" method="POST" novalidate>

            <input type="hidden" name="event_name" value="<?php echo htmlspecialchars($event); ?>">

            <!-- Name Field -->
            <div class="mb-3">
              <label for="name">Your Name</label>
              <input type="text" name="name" id="name" required class="form-control" placeholder="e.g. Aniket Singh">
              <div class="text-danger" id="nameError">Please enter your name (letters and spaces only).</div>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
              <label for="email">Your Email</label>
              <input type="email" name="email" id="email" required class="form-control" placeholder="e.g. aniket@example.com">
              <div class="text-danger" id="emailError">Please enter a valid email address.</div>
            </div>

            <!-- Class Field -->
            <div class="mb-3">
              <label for="class">Class</label>
              <input type="text" name="class" id="class" required class="form-control" placeholder="e.g. SYBSCIT">
              <div class="text-danger" id="classError">Please enter your class (letters and spaces only).</div>
            </div>

            <!-- Roll Number Field -->
            <div class="mb-3">
              <label for="roll">Roll Number</label>
              <input type="number" name="roll" id="roll" required class="form-control" placeholder="e.g. 57">
              <div class="text-danger" id="rollError">Please enter a valid roll number (numbers only).</div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Submit</button>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Regex patterns for validation
    const namePattern = /^[a-zA-Z\s]+$/; // Only letters and spaces
    const classPattern = /^[a-zA-Z\s]+$/; // Only letters and spaces
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Valid email
    const rollPattern = /^[0-9]+$/; // Only numbers

    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
      let valid = true;

      // Name Validation
      const name = document.getElementById('name');
      const nameError = document.getElementById('nameError');
      if (!namePattern.test(name.value.trim())) {
        nameError.style.display = 'block';
        valid = false;
      } else {
        nameError.style.display = 'none';
      }

      // Email Validation
      const email = document.getElementById('email');
      const emailError = document.getElementById('emailError');
      if (!emailPattern.test(email.value.trim())) {
        emailError.style.display = 'block';
        valid = false;
      } else {
        emailError.style.display = 'none';
      }

      // Class Validation
      const classInput = document.getElementById('class');
      const classError = document.getElementById('classError');
      if (!classPattern.test(classInput.value.trim())) {
        classError.style.display = 'block';
        valid = false;
      } else {
        classError.style.display = 'none';
      }

      // Roll Validation
      const roll = document.getElementById('roll');
      const rollError = document.getElementById('rollError');
      if (!rollPattern.test(roll.value.trim())) {
        rollError.style.display = 'block';
        valid = false;
      } else {
        rollError.style.display = 'none';
      }

      // Prevent form submission if invalid
      if (!valid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
