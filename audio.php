<?php
session_start();
if (!isset($_SESSION['userFolder'])) {
    require('includes/header.php');
    echo "<main><h2>We are sorry, but you must be logged in to play songs</h2>";
    echo "<h3>Use the Login link on the left to login</h2></main>";
    include('includes/footer.php');
    exit;
} else {
    $folder = $_SESSION['userFolder'];
    $name = FALSE;;
    if(isset($_GET['file'])) {
        $ext = strtolower(substr($_GET['file'], -4));
        if(($ext == '.mp3') OR ($ext == '.mp4') OR ($ext == '.wav') OR ($ext == '.m4a') OR ($ext == '.ogg')) {
            $file = "../../uploads/$folder/{$_GET['file']}";
            echo $file;
            if (file_exists($file) && (is_file($file)))
                $name = $_GET['file'];
        }
    }
    if (!$name) {
        require('includes/header.php');
        echo '<h4>You have reached this page in error.</h4>';
        include('includes/footer.php');
        exit;
    }

    header('Content-Type: ' . mime_content_type($file));
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: filename="' . $name . '"');
    header('Cache-Control: no-cache');
    header('Content-Length: ' . filesize($file));
    readfile($file);
}