<?php 

include_once("../owner/OwnerController.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # code...
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $unit = $_POST['unit'];

    $ownerController = new OwnerController();

    // Check if the unit already exists
    $check = $ownerController->checkUnit($unit);

    if ($check) {
        // Unit already exists
        $error = "Unit already exists";
    } else {
        // Proceed with registration
        $result = $ownerController->Register($name, $password, $email, $phone, $unit);

        if ($result) {
            // Registration successful, redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Handle registration failure (optional)
            $error = "Registration failed. Please try again.";
        }
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

    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <?php if (isset($error)): ?>
                    <div style="color: red;" class="text-center mt-3 mb-3"><?php echo $error; ?></div>
                <?php endif; ?>  

                <div class="container py-5">
                    <h1 class="text-center pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Owner Registration</h1>
                    <form action="ownerregister.php" method="post">

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="name" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                        </div>

                        <div class="mb-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="floatingPhone" name="phone" placeholder="Phone Number" 
                            required pattern="^\d{3}-\d{3}-\d{4}$" title="Phone number format: 123-456-7890">
                        </div>

                        <div class="mb-2">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="floatingLastName" name="unit" placeholder="Unit" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 p-2 mt-3" name="submit">Owner Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    
    <?php include_once("layouts/footer.php"); ?>
</body>
</html>