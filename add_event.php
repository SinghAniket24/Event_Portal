<?php
include 'db/config.php';

$title = $_POST['title'];
$description = $_POST['description'];
$event_date = $_POST['event_date'];
$venue = $_POST['venue']; 
$image_path = $_POST['image_path'];

$sql = "INSERT INTO events (title, description, event_date, venue, image_path) 
        VALUES ('$title', '$description', '$event_date', '$venue', '$image_path')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Event added successfully!');
        window.location.href = 'admin_dashboard.php';
    </script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
