<?php
session_start();
include('includes/title.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Aidan Shene -->
    <meta charset="utf-8">
    <title>Music Shop<?php if(isset($title)) echo '&mdash;' . $title;?></title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" media="screen">
</head>
<body class="grid">
<header class="navigation-sidebar-container">
    <nav class="navigation-sidebar">
        <?php $current_page = basename($_SERVER['SCRIPT_FILENAME']);?>
        <ul class="navigation-sidebar-items">
            <li><a class="logo" href="index.php"></a></li>
            <?php if(isset($_SESSION['username'])) {?>
                <li><a href="upload.php" <?php if($current_page == 'upload.php') echo 'id="here"';?>>Upload</a></li>
                <li><a href="landing.php" <?php if($current_page == 'landing.php') echo 'id="here"';?>>Profile</a></li>
                <?php if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Owner') {?>
                    <li><a href="admin.php" <?php if($current_page == 'admin.php') echo 'id="here"';?>>Admin</a></li>
                <?php }?>
                <li><a href="logout.php">Logout</a></li>
            <?php } else {?>
                <li><a href="login.php">Login</a></li>
            <?php }?>
            <li><a href="contact.php" <?php if($current_page == 'contact.php') echo 'id="here"';?>>Contact</a></li>
        </ul>
    </nav>
</header>