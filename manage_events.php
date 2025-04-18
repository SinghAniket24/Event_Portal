<?php
include 'db/config.php';

// Handle form submission for adding and updating events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update event logic
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $venue = $_POST['venue'];
        $image_path = $_POST['image_path'];

        $sql = "UPDATE events SET title = '$title', description = '$description', event_date = '$event_date', venue = '$venue', image_path = '$image_path' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Event updated successfully.<br><a href='manage_events.php'>Go back</a>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Add new event logic
        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $venue = $_POST['venue'];
        $image_path = $_POST['image_path'];

        $sql = "INSERT INTO events (title, description, event_date, venue, image_path) VALUES ('$title', '$description', '$event_date', '$venue', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            echo "New event added successfully.<br><a href='manage_events.php'>Go back</a>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Handle event deletion
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM events WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Event deleted successfully.<br><a href='manage_events.php'>Go back</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all events from the database
$query = "SELECT * FROM events";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add some basic styling for the modal and table */
        #eventsTable {
            width: 100%;
            border-collapse: collapse;
        }

        #eventsTable th, #eventsTable td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 60%;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <header>
        <h1>Event Management Dashboard</h1>
        <nav>
            <a href="admin.html">Register Events</a>
            <a href="manage_events.php">Manage Events</a>
        </nav>
    </header>

    <main>
        <h2>Manage Registered Events</h2>

        <button onclick="openEventModal()">Add New Event</button>

        <table id="eventsTable">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $event['title'] ?></td>
                        <td><?= $event['event_date'] ?></td>
                        <td><?= $event['venue'] ?></td>
                        <td>
                            <button onclick="openEventModal(<?= $event['id'] ?>)">Edit</button>
                            <button onclick="deleteEvent(<?= $event['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Modal for adding/editing event -->
        <div id="eventModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEventModal()">&times;</span>
                <h3 id="modalTitle">Add Event</h3>
                <form id="eventForm" action="manage_events.php" method="POST">
                    <input type="hidden" id="eventId" name="id">
                    <label for="eventName">Event Name:</label>
                    <input type="text" id="eventName" name="title" required><br><br>
                    
                    <label for="eventDate">Event Date:</label>
                    <input type="date" id="eventDate" name="event_date" required><br><br>
                    
                    <label for="eventLocation">Event Location:</label>
                    <input type="text" id="eventLocation" name="venue" required><br><br>
                    
                    <label for="eventDescription">Description:</label>
                    <textarea id="eventDescription" name="description" required></textarea><br><br>

                    <label for="eventImage">Event Image Path:</label>
                    <input type="text" id="eventImage" name="image_path" required><br><br>
                    
                    <button type="submit">Save Event</button>
                </form>
            </div>
        </div>
    </main>

    <script>
    // Open the event modal for adding or editing events
    function openEventModal(eventId = null) {
        if (eventId) {
            // If editing an event, populate the form with current event data
            fetch(`get_event.php?id=${eventId}`)
                .then(response => response.json())
                .then(event => {
                    document.getElementById('modalTitle').innerText = "Edit Event";
                    document.getElementById('eventId').value = event.id;
                    document.getElementById('eventName').value = event.title;
                    document.getElementById('eventDate').value = event.event_date;
                    document.getElementById('eventLocation').value = event.venue;
                    document.getElementById('eventDescription').value = event.description;
                    document.getElementById('eventImage').value = event.image_path;
                });
        } else {
            document.getElementById('modalTitle').innerText = "Add Event";
            document.getElementById('eventForm').reset();
        }

        document.getElementById('eventModal').style.display = 'block';
    }

    // Close the event modal
    function closeEventModal() {
        document.getElementById('eventModal').style.display = 'none';
    }

    // Handle event deletion
    function deleteEvent(eventId) {
        if (confirm('Are you sure you want to delete this event?')) {
            window.location.href = `manage_events.php?delete_id=${eventId}`;
        }
    }

    // Close the modal when clicking outside the modal content
    window.onclick = function(event) {
        if (event.target === document.getElementById('eventModal')) {
            document.getElementById('eventModal').style.display = 'none';
        }
    }
    </script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
