<?php
    ob_start();
    // Lấy dữ liệu file
    $file = $_FILES["img"];

    // Kiểm tra xem file có phải là ảnh không
    if (!is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    // Lấy tên file
    $filename = $file['name'];

    move_uploaded_file($file['tmp_name'], $filename);
    $new_filename = 'img_to_search.png';
    rename($filename, $new_filename);

    system('echo "yes" | python ai.py');

    $imageDirectory = '/NienLuanNganh/images/TC/';
    ob_end_clean();

    header('Location: ../result.php');
    ?>