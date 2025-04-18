<?php
session_start();

// Check if admin is logged in, if not redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

include 'db/config.php';

// Handle event deletion
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM events WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Event deleted successfully.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all events
$query = "SELECT * FROM events";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Manage Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- External CSS if any -->
    <style>
        /* Basic styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef1f5;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            flex-wrap: wrap;
        }

        header img {
            height: 60px;
            margin-right: 15px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            text-align: center;
        }

        .logout-btn {
            position: absolute;
            right: 30px;
            top: 20px;
            background-color: #d9534f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c9302c;
        }

        main {
            padding: 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        h2 {
            margin-top: 0;
            color: #003366;
            font-size: 26px;
        }

        .add-event-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .add-event-btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #004080;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-btns a {
            display: inline-block;
            margin: 0 5px;
            padding: 7px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #f0ad4e;
        }

        .edit-btn:hover {
            background-color: #ec971f;
        }

        .delete-btn {
            background-color: #d9534f;
        }

        .delete-btn:hover {
            background-color: #c9302c;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 12px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Responsive design adjustments */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px;
            }

            .logout-btn {
                position: relative;
                top: 0;
                right: 0;
                margin-top: 10px;
            }

            .top-bar {
                flex-direction: column;
                align-items: center;
                width: 100%;
                margin-top: 20px;
            }

            h2 {
                font-size: 22px;
            }

            .add-event-btn {
                width: 100%;
                text-align: center;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px 10px;
            }

            td {
                display: block;
                padding: 10px 5px;
            }

            .action-btns {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .action-btns a {
                margin-bottom: 5px;
                width: 100%;
                text-align: center;
            }
        }

        /* Further responsiveness for very small screens */
        @media (max-width: 480px) {
            header img {
                height: 50px;
            }

            .logout-btn {
                padding: 8px 12px;
                font-size: 14px;
            }

            h2 {
                font-size: 18px;
            }

            .add-event-btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 6px 8px;
            }

            td {
                display: block;
                padding: 8px 5px;
            }

            .action-btns a {
                margin-bottom: 5px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header>
    <div style="display: flex; align-items: center;">
        <img src="images/college_logo.png" alt="College Logo">
        <h1>St. Xavier's College - Event Admin Panel</h1>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<main>
    <div class="top-bar">
        <h2>Manage Events</h2>
        <a href="admin.html" class="add-event-btn">+ Add New Event</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($event['title']) ?></td>
                    <td><?= htmlspecialchars($event['event_date']) ?></td>
                    <td><?= htmlspecialchars($event['venue']) ?></td>
                    <td class="action-btns">
                        <a href="edit_event.php?edit_id=<?= $event['id'] ?>" class="edit-btn">Edit</a>
                        <a href="?delete_id=<?= $event['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<footer>
    &copy; <?= date("Y") ?> St. Xavier's College | Admin Panel
</footer>

</body>
</html>

<?php $conn->close(); ?>
