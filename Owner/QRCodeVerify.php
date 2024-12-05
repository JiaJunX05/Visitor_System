<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include HTML5 QR Code -->
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            background: #f8f9fa;
        }
        #result {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once("layouts/header.php"); ?>

    <div class="container py-5">
        <!-- Page Title -->
        <h2 class="text-center mb-4">QR Code Scanner</h2>
        <div class="row justify-content-center">
            <!-- Scanner Section -->
            <div class="col-md-6">
                <div id="reader"></div>
                <div id="result" class="alert alert-success mt-3 d-none"></div>
                <div id="details" class="mt-3"></div>
            </div>
        </div>
    </div>

    <?php include_once("layouts/footer.php"); ?>

    <script>
        // 成功扫描后的处理函数
        function onScanSuccess(decodedText, decodedResult) {
            const parsedData = JSON.parse(decodedText)[0]; // Extract the string from the array
            console.log(`Code scanned = ${parsedData}`, decodedResult);

            // Redirect to display.html with the scanned data in query parameters
            window.location.href = `display.html?visitor_code=${encodeURIComponent(parsedData)}`;
        }

        // 扫描错误处理
        function onScanError(errorMessage) {
            console.error("QR Scan Error:", errorMessage); // 可选：记录错误日志
        }

        // 发送扫描数据到后端验证
        function validateQRCode(scannedData) {
            const parsedData = JSON.parse(scannedData);
            const newCode = parsedData["visitor_code"];

            fetch('validate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ scannedData: newCode })
            })
            .then(response => response.json())
            .then(data => {
                const resultElement = document.getElementById('result');
                if (data.status === 'success') {
                    resultElement.textContent = 'QR Code Validated Successfully!';
                    resultElement.classList.remove('d-none', 'alert-danger');
                    resultElement.classList.add('alert-success');
                } else {
                    resultElement.textContent = data.message;
                    resultElement.classList.remove('d-none', 'alert-success');
                    resultElement.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error validating QR code:', error);
                const resultElement = document.getElementById('result');
                resultElement.textContent = 'Error validating QR code. Please try again.';
                resultElement.classList.remove('d-none', 'alert-success');
                resultElement.classList.add('alert-danger');
            });
        }

        // 初始化 QR Code 扫描器
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: 250 },
            false
        );

        // 渲染扫描器，仅在检测到二维码时才读取
        html5QrcodeScanner.render(validateQRCode, onScanError);
    </script>
</body>
</html>