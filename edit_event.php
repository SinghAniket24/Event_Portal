<?php
include 'db/config.php';

if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $sql = "SELECT * FROM events WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $venue = $_POST['venue'];
        $image_path = $_POST['image_path'];

        $sql = "UPDATE events SET 
                title = '$title', 
                description = '$description', 
                event_date = '$event_date', 
                venue = '$venue', 
                image_path = '$image_path' 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirect to admin dashboard with success alert
            echo "<script>
                    alert('✅ Event updated successfully.');
                    window.location.href = 'admin_dashboard.php'; // Redirect to admin dashboard
                  </script>";
        } else {
            echo "<div class='error-msg'>❌ Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #e0f7fa, #e1bee7, #f3e5f5);
        background-attachment: fixed;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 30px;
        background: rgba(63, 81, 181, 0.9);
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    header img {
        height: 50px;
    }

    header h1 {
        font-size: 24px;
        margin: 0;
        flex-grow: 1;
        text-align: center;
    }

    main {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 20px;
        min-height: 100vh;
    }

    .card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        padding: 40px;
        max-width: 700px;
        width: 100%;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h2 {
        text-align: center;
        color: #3f51b5;
        margin-bottom: 30px;
    }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
    }

    input[type="text"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background-color: #f1f1f1;
        margin-bottom: 20px;
        font-size: 15px;
        transition: background-color 0.3s ease;
    }

    input:focus,
    textarea:focus {
        background-color: #fff;
        outline: 2px solid #9575cd;
    }

    textarea {
        resize: vertical;
    }

    button {
        width: 100%;
        background: linear-gradient(to right, #512da8, #673ab7);
        color: white;
        padding: 14px;
        font-size: 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background: linear-gradient(to right, #4527a0, #5e35b1);
    }

    .success-msg,
    .error-msg {
        text-align: center;
        font-weight: 600;
        margin: 20px auto;
        padding: 12px 20px;
        width: fit-content;
        border-radius: 10px;
    }

    .success-msg {
        background: #d4edda;
        color: #2e7d32;
    }

    .error-msg {
        background: #f8d7da;
        color: #c62828;
    }

    a {
        color: #4a148c;
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        header h1 {
            font-size: 20px;
        }

        .card {
            padding: 25px;
        }
    }
  </style>
</head>
<body>

<header>
    <img src="images/college_logo.png" alt="College Logo">
    <h1>St. Xavier's College Admin Panel</h1>
</header>

<main>
    <div class="card">
        <h2>Edit Event</h2>
        <form method="POST" action="edit_event.php">
            <input type="hidden" name="id" value="<?= $event['id'] ?>">

            <label for="title">Event Title</label>
            <input type="text" id="title" name="title" value="<?= $event['title'] ?>" required>

            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" value="<?= $event['event_date'] ?>" required>

            <label for="venue">Event Venue</label>
            <input type="text" id="venue" name="venue" value="<?= $event['venue'] ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?= $event['description'] ?></textarea>

            <label for="image_path">Image Path</label>
            <input type="text" id="image_path" name="image_path" value="<?= $event['image_path'] ?>" required>

            <button type="submit">Update Event</button>
        </form>
    </div>
</main>

</body>
</html>

<?php $conn->close(); ?>
