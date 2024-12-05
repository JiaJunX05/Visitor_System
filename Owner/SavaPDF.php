<?php
// 检查是否有上传的文件
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../pdf/'; // 保存文件的目录
    $fileName = $_FILES['pdf']['name']; // 获取上传文件的原始名称
    $filePath = $uploadDir . basename($fileName);

    // 确保目录存在
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 保存文件到目录
    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath)) {
        echo "文件已成功保存到: " . $filePath;
    } else {
        echo "文件保存失败！";
    }
} else {
    echo "未上传文件或发生错误！";
}
?>
