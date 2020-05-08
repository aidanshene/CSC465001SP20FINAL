<?php
session_start();
require_once('../../reg_conn.php');
if(isset($_SESSION['username'])) {
    $_SESSION = array();
    session_destroy();
    setcookie('PHPSESSID','',time()-3600,'/');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php');
    exit;
}
else {
    include('includes/header.php');?>
    <main>
        <h3>You have reached this page in error.</h3>
        <h3>Please click the link in the top left to return to the home page.</h3>
    </main>
    <?php include('includes/footer.php');
}?>



