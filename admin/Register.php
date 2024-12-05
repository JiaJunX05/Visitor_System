<?php 

include_once("AdminController.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # code...
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $adminController = new AdminController();

    // Check if the username already exists
    $check = $adminController->checkAccExist($username);

    if ($check) {
        // Username already exists
        $error = "Username already exists";
    } else {
        // Proceed with registration
        $result = $adminController->Register($username, $password, $email);
        
        if ($result) {
            // Registration successful, redirect to login
            header("Location: login.php");
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
<body class="bg-primary-subtle">
    <div class="container text-center">
        <div class="row mt-5">
            <div class="col">
                <?php if (isset($error)): ?>
                    <div style="color: red;" class="text-center mt-3 mb-3"><?php echo $error; ?></div>
                <?php endif; ?>  

                <h1 class="text-center pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Sign Up</h1>
                <form action="register.php" method="post">
                    <div class="form-floating mb-3 mt-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                        <label for="username">
                            <img src="https://cdn-icons-png.freepik.com/256/4945/4945750.png?ga=GA1.1.1815414687.1712627600&semt=ais_hybrid" alt="" style="width: 20px; margin-right: 5px; margin-top: -3px;">
                            Username
                        </label>
                    </div>

                    <div class="form-floating mb-3 mt-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                        <label for="password">
                            <img src="https://cdn-icons-png.freepik.com/256/2889/2889676.png?ga=GA1.1.1815414687.1712627600&semt=ais_hybrid" alt="" style="width: 20px; margin-right: 5px; margin-top: -3px;">
                            Password
                        </label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                        <label for="floatingInput">
                            <img src="https://cdn-icons-png.freepik.com/256/732/732200.png?ga=GA1.1.1815414687.1712627600&semt=ais_hybrid" alt="" style="width: 20px; margin-right: 5px; margin-top: -3px;">
                            Email address
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 p-2" name="submit">SIGN UP</button>                

                    <div class="text-center mt-4">
                        <p class="text-secondary" style="font-size: 20px;">Already have an account? <a href="login.php" style="text-decoration: none; color: red;">Sign In</a></p>
                    </div>
                </form>    
            </div>
        </div>
    </div>   
</body>
</html>