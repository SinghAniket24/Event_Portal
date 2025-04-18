<?php
include 'db/config.php';
require('fpdf/fpdf.php');

// Sanitize input function
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Collect and sanitize form data
$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$event_name = sanitize_input($_POST['event_name']);
$class = sanitize_input($_POST['class']);
$roll = sanitize_input($_POST['roll']);

// Insert using prepared statement
$stmt = $conn->prepare("INSERT INTO registrations (name, email, event_name, class, roll) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $event_name, $class, $roll);

if ($stmt->execute()) {
    $registration_id = $stmt->insert_id;
    $stmt->close();

    // Fetch event details
    $event_stmt = $conn->prepare("SELECT * FROM events WHERE title = ?");
    $event_stmt->bind_param("s", $event_name);
    $event_stmt->execute();
    $event_result = $event_stmt->get_result();

    if ($event_result && $event_result->num_rows > 0) {
        $event = $event_result->fetch_assoc();
        $event_stmt->close();

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Styles
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->SetLineWidth(0.4);

        // Header
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 15, 'Event Entry Ticket', 0, 1, 'C');

        // Event Image (if exists)
        if (!empty($event['image_path']) && file_exists($event['image_path'])) {
            $pdf->Image($event['image_path'], 10, 25, 190, 60);
            $pdf->Ln(65);
        } else {
            $pdf->Ln(10);
        }

        // Divider
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);

        // Event Details Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Event Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 8, 'Event:', 0, 0);
        $pdf->Cell(100, 8, $event_name, 0, 1);
        $pdf->Cell(50, 8, 'Venue:', 0, 0);
        $pdf->Cell(100, 8, $event['venue'], 0, 1);
        $pdf->Cell(50, 8, 'Date:', 0, 0);
        $pdf->Cell(100, 8, date('d-m-Y'), 0, 1);
        $pdf->Ln(2);
        $pdf->MultiCell(0, 8, 'Description: ' . $event['description']);
        $pdf->Ln(5);

        // Divider
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);

        // Registration Info Grid
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Your Ticket Info', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);

        // Left Column
        $pdf->Cell(50, 8, 'Name:', 0, 0);
        $pdf->Cell(80, 8, $name, 0, 1);

        $pdf->Cell(50, 8, 'Class:', 0, 0);
        $pdf->Cell(80, 8, $class, 0, 1);

        $pdf->Cell(50, 8, 'Roll No:', 0, 0);
        $pdf->Cell(80, 8, $roll, 0, 1);

        $pdf->Cell(50, 8, 'Email:', 0, 0);
        $pdf->Cell(80, 8, $email, 0, 1);

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'Please present this ticket at the event entrance.', 0, 1, 'C');

        // Save PDF
        $pdf_path = 'tickets/Ticket_' . $registration_id . '.pdf';
        $pdf->Output('F', $pdf_path);

        // Confirmation Page
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration Successful</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f0f0;
                    padding: 40px;
                    text-align: center;
                }
                .box {
                    max-width: 600px;
                    margin: auto;
                    padding: 20px;
                    background: white;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    border-radius: 12px;
                }
                h2 {
                    color: #333;
                }
                img {
                    width: 100%;
                    max-height: 300px;
                    object-fit: cover;
                    border-radius: 8px;
                }
                .btn {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 12px 20px;
                    background-color: #28a745;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-size: 16px;
                }
                .btn:hover {
                    background-color: #218838;
                }
                .details {
                    text-align: left;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="box">
                <h2>You're Registered!</h2>
                <p>Hello <strong><?php echo $name; ?></strong>, your ticket for <strong><?php echo $event_name; ?></strong> has been generated.</p>
                <?php if (!empty($event['image_path'])): ?>
                    <img src="<?php echo $event['image_path']; ?>" alt="Event Image">
                <?php endif; ?>

                <div class="details">
                    <p><strong>Venue:</strong> <?php echo $event['venue']; ?></p>
                    <p><strong>Date:</strong> <?php echo date('d-m-Y'); ?></p>
                    <p><strong>Class:</strong> <?php echo $class; ?></p>
                    <p><strong>Roll No:</strong> <?php echo $roll; ?></p>
                </div>

                <a class="btn" href="<?php echo $pdf_path; ?>" download>Download Your Ticket</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Event not found.";
    }
} else {
    echo "Error: " . $stmt->error;
    $stmt->close();
}

$conn->close();
?>
