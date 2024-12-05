<?php

include_once("../include/db.php");
include_once('VisitorController.php');

$visitor = new VisitorController();

$visitor_id = $_GET['visitor_id']; // Get the visitor_id from the URL

// Call the viewQRCode function to get the QR code data
$result = $visitor->viewQRCode($visitor_id);

// Fetch the result as an associative array
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor QR Code</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Font Awesome (Optional for Icons) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Include jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body class="bg-light">
    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center mb-2">
            <h1 class="display-4">Visitor QR Code</h1>
            <p class="lead">Download your visitor QR Code and details below.</p>
        </div>

        <div class="card shadow p-4 w-100" style="max-width: 500px;">
            <figure class="text-center">
                <?php if (isset($row['qr_code'])): ?>
                    <img src="<?php echo htmlspecialchars($row['qr_code']); ?>" alt="Visitor QR Code" class="img-fluid mb-3" style="max-width: 300px;">
                    <figcaption class="text-muted">QR Code for visitor</figcaption>
                <?php else: ?>
                    <p class="text-danger">QR Code not found.</p>
                <?php endif; ?>
            </figure>
            
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <b>Visitor Name:</b> <?php echo htmlspecialchars($row['name']); ?>
                </li>
                <li class="list-group-item">
                    <b>Visitor Email:</b> <?php echo htmlspecialchars($row['email']); ?>
                </li>
                <li class="list-group-item">
                    <b>Visitor Phone:</b> <?php echo htmlspecialchars($row['phone']); ?>
                </li>
                <li class="list-group-item">
                    <b>Visit Date:</b> <?php echo htmlspecialchars($row['visit_date']); ?>
                </li>
            </ul>

            <div class="text-center mt-4">
                <button id="downloadPdf" class="btn btn-primary btn-lg">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('downloadPdf').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // 背景
        doc.setFillColor(240, 240, 240);
        doc.rect(10, 10, 190, 280, 'F');

        // 标题
        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.setTextColor(0, 51, 102);
        doc.text("Visitor QR Code", 20, 20);
        doc.line(20, 25, 190, 25);

        // 内容
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text("Visitor Name: <?php echo htmlspecialchars($row['name']); ?>", 20, 40);
        doc.text("Visitor Email: <?php echo htmlspecialchars($row['email']); ?>", 20, 50);
        doc.text("Visitor Phone: <?php echo htmlspecialchars($row['phone']); ?>", 20, 60);
        doc.text("Visit Date: <?php echo htmlspecialchars($row['visit_date']); ?>", 20, 70);

        // QR Code
        const qrImage = document.querySelector('img');
        if (qrImage) {
            doc.addImage(qrImage.src, 'PNG', 20, 90, 60, 60);
        }

        // 保存 PDF
        doc.save('visitor_qrcode.pdf');
    });
    </script>
</body>
</html>
