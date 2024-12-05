<?php

include_once("../include/db.php");
include_once("visitorController.php");

if (!isset($_SESSION['owner_id'])) {
    header("Location: login.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];

// 创建 VisitorController 对象
$Visitor = new VisitorController();

// 调用 getVisitors 方法获取访客信息
$result = $Visitor->getVisitors($owner_id);

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

    <div class="container text-center mt-5">
        <h1 class="text-center pb-2 mb-4 text-primary-emphasis border-bottom border-danger" style="font-weight: 700;">Welcome to Owner Panel</h1>

        <div class="table-responsive">
            <table class="table table-hover table-striped mt-3">
                <thead>
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visit Date</th>
                        <th>QR Code</th>
                        <th>Action</th>
                        <!-- 添加更多列根据你的需求 -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                                <td>
                                    <a href="viewqrcode.php?visitor_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-sm">
                                        View QR Code
                                    </a>
                                </td>
                                <td>
                                    <a href="updatevisitordate.php?visitor_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">
                                        Change Visit Date
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No visitors found for this owner.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include_once("layouts/footer.php"); ?>
</body>
</html>