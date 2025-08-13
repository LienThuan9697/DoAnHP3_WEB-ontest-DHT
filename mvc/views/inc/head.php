<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    
    <title><?php echo $data["Title"] ?? "DHT OnTest – Hệ thống quản lý bài thi cá nhân hóa"; ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="DHT OnTest – Hệ thống hỗ trợ tạo đề, quản lý bài thi và thi trắc nghiệm cá nhân hóa hiệu quả, bảo mật và dễ sử dụng.">
    <meta name="author" content="DHT OnTest Dev Team">
    
    <!-- Meta Mạng xã hội -->
    <meta property="og:title" content="DHT OnTest – Hệ thống quản lý bài thi cá nhân hóa">
    <meta property="og:site_name" content="DHT OnTest">
    <meta property="og:description" content="Hệ thống hỗ trợ giáo viên và nhà trường tạo đề, tổ chức thi trắc nghiệm online thông minh, bảo mật, hiệu quả.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://localhost/Quanlythitracnghiem">
    <meta property="og:image" content="./public/media/favicons/og-image.jpg">

    <!-- Đường dẫn mặc định -->
    <base href="<?php echo app_path ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./public/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="./public/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./public/media/favicons/apple-touch-icon-180x180.png">

    <!-- Plugin Styles -->
    <?php
    if (!empty($data["Plugin"]["datepicker"])) {
        echo '<link rel="stylesheet" href="./public/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">';
    }
    if (!empty($data["Plugin"]["flatpickr"])) {
        echo '<link rel="stylesheet" href="./public/js/plugins/flatpickr/flatpickr.min.css">';
    }
    if (!empty($data["Plugin"]["select"])) {
        echo '<link rel="stylesheet" href="./public/js/plugins/select2/css/select2.min.css">';
    }
    if (!empty($data["Plugin"]["slick"])) {
        echo '<link rel="stylesheet" href="./public/js/plugins/slick-carousel/slick.css">';
        echo '<link rel="stylesheet" href="./public/js/plugins/slick-carousel/slick-theme.css">';
    }
    if (!empty($data["Plugin"]["sweetalert2"])) {
        echo '<link rel="stylesheet" href="./public/js/plugins/sweetalert2/sweetalert2.min.css">';
    }
    ?>

    <!-- Font & Core CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" id="css-main" href="./public/css/dashmix.css">
    <link rel="stylesheet" id="css-main" href="./public/css/custom.css">

    <!-- jQuery -->
    <script src="./public/js/lib/jquery.min.js"></script>
</head>
