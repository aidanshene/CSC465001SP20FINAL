<?php
require_once('../../secure_conn.php');
include('includes/header.php');?>
	<main>
	<?php
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];?>

    <?php } else {?>
        <h3>You have reached this page in error.</h3>
        <h3>Please click the link in the top left to return to the home page.</h3>
    <?php }?>
	</main>
<?php
include('includes/footer.php');?>
