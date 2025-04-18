<?php include 'db/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Xavier's College Events</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #e6ecf3);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: #002147;
    }
    .navbar-brand {
      color: #ffffff;
      font-size: 1.5rem;
      font-weight: bold;
    }
    .navbar-brand:hover {
      color: #007bff;
    }

    .hero-section {
      background: #e9eef5;
      padding: 50px 20px;
      text-align: center;
      margin-top: 70px;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }
    .hero-section h1 {
      font-size: 2.5rem;
      color: #002147;
      font-weight: bold;
    }
    .hero-section p {
      font-size: 1.1rem;
      color: #555;
      margin-top: 15px;
    }
    .hero-section img {
      height: 100px;
      width: auto;
      object-fit: contain;
      margin-top: 20px;
    }

    .section-heading {
      text-align: center;
      font-size: 2rem;
      font-weight: bold;
      color: #002147;
      margin-top: 40px;
    }

    /* Event Card Styles */
    .event-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      overflow: hidden;
      border-radius: 15px;
      position: relative;
      height: 100%;
    }

    .event-card:hover {
      transform: scale(1.1);
      box-shadow: 40px 42px 40px rgba(17, 4, 86, 0.2);
    }

    .event-img {
      height: 200px;
      object-fit: cover;
      border-radius: 15px 15px 0 0;
      width: 100%;
    }

    .event-body {
      padding: 20px;
      background: #fff;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .event-title {
      font-size: 1.4rem;
      font-weight: bold;
      color: #002147;
    }

    .event-info p {
      margin: 4px 0;
      font-size: 0.95rem;
      color: #333;
    }

    .event-desc {
      margin-top: 10px;
      font-size: 0.95rem;
      color: #555;
    }

    .btn-register {
      margin-top: 15px;
      background-color: #0056b3;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 1rem;
      display: inline-block;
      transition: background-color 0.3s ease;
      width: fit-content;
    }

    .btn-register:hover {
      background-color: #003d80;
    }

    .footer {
      background-color: #002147;
      color: #fff;
      padding: 40px 0;
      margin-top: 60px;
    }

    .footer h5 {
      color: #fff;
      margin-bottom: 15px;
      font-size: 1.2rem;
    }

    .footer p {
      color: #ccc;
      font-size: 0.95rem;
    }

    .footer a {
      color: #007bff;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">Xavier's College Events</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <img src="images/college_logo.png" alt="Xavier's College Logo">
    <h1>Welcome to Xavier's College Events</h1>
    <p>Join us for exciting cultural, technical, and social events. Stay updated and be a part of the vibrant community at Xavier's College!</p>
  </section>

  <!-- Events Section -->
  <div class="container">
    <h2 class="section-heading">Upcoming Events</h2>
    <div class="row">
      <?php
        $today = date("Y-m-d");
        $result = $conn->query("SELECT * FROM events WHERE event_date >= '$today' ORDER BY event_date ASC");

        if ($result->num_rows > 0):
          while($row = $result->fetch_assoc()):
      ?>
      <div class="col-md-4 d-flex mb-4">
        <div class="card event-card w-100">
          <?php if (!empty($row['image_path'])): ?>
            <img src="<?= $row['image_path']; ?>" class="event-img" alt="Event Image">
          <?php else: ?>
            <img src="https://via.placeholder.com/400x200?text=Event+Image" class="event-img" alt="Event Image">
          <?php endif; ?>
          <div class="event-body">
            <h5 class="event-title"><?= htmlspecialchars($row['title']); ?></h5>
            <div class="event-info">
              <p><strong>Date:</strong> <?= date("d M Y", strtotime($row['event_date'])); ?></p>
              <p><strong>Venue:</strong> <?= htmlspecialchars($row['venue']); ?></p>
            </div>
            <div class="event-desc">
              <p><?= htmlspecialchars($row['description']); ?></p>
            </div>
            <a href="register.php?event=<?= urlencode($row['title']); ?>" class="btn-register">Register</a>
          </div>
        </div>
      </div>
      <?php endwhile; else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">No events at this time. Please check back soon!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>About Xavier's College</h5>
          <p>Xavier’s College is one of the most prestigious educational institutions, offering a wide array of cultural, technical, and social events that cater to students across different fields of interest.</p>
        </div>
        <div class="col-md-6">
          <h5>Contact Us</h5>
          <p>
            Xavier’s College<br>
            Mumbai, Maharashtra – 400001<br>
            Email: <a href="mailto:contact@xavierscollege.edu">contact@xavierscollege.edu</a><br>
            Phone: +91 00000 00000<br>
            <a href="#">Visit our website</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
