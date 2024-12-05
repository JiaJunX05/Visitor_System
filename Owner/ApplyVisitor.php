<?php 

include_once("VisitorController.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # code...
    $name = $_POST['name'];
    $IC = $_POST['IC'];
    $car_number_plate = $_POST['car_number_plate'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $visit_date = $_POST['visit_date'];
    $valid_days = $_POST['valid_days'];
    $owner_id = $_SESSION['owner_id'];

    $visitor_code = $name . '_' . date('Ymd', strtotime($visit_date));

    $status = "Approved";

    $visitorController = new VisitorController();

    if ($visitorController->ApplyVisitor($name, $IC, $car_number_plate, $email, $phone, $visitor_code, $visit_date, $status, $owner_id, $valid_days)) {
        # code...
        $success = true;
    } else {
        $success = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once("layouts/header.php"); ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <?php if (isset($error)): ?>
                    <div style="color: red;" class="text-center mt-3 mb-3"><?php echo $error; ?></div>
                <?php endif; ?>  

                <div class="container py-5">
                    <h1 class="text-center pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Apply for Visitor</h1>
                    <form action="applyvisitor.php" method="post">

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="name">Visitor Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Visitor Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="IC">Visitor IC:</label>
                                    <input type="text" class="form-control" id="IC" name="IC" placeholder="Enter Visitor IC" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="car_number_plate">Car Number Plate:</label>
                            <input type="text" class="form-control" id="car_number_plate" name="car_number_plate" placeholder="Enter Car Number Plate" required>
                        </div>

                        <div class="mb-2">
                            <label for="email">Visitor Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Visitor Email" required>
                        </div>

                        <div class="mb-2">
                            <label for="phone">Visitor Phone:</label>
                            <input type="text" class="form-control" id="floatingPhone" name="phone" placeholder="Phone Number" 
                            required pattern="^\d{3}-\d{3}-\d{4}$" title="Phone number format: 123-456-7890">
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="visit_date">Visit Date:</label>
                                    <input type="date" class="form-control" id="visit_date" name="visit_date" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="valid_days">QR Code Validity (in days):</label>
                                    <input type="number" class="form-control" id="valid_days" name="valid_days" placeholder="Enter QR Code Validity (in days)" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 p-2" name="submit">Apply for Visitor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    
    <?php include_once("layouts/footer.php"); ?>
</body>
</html>