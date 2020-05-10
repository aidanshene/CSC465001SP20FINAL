<?php
require_once('../../secure_conn.php');
include('includes/header.php');?>
	<main>
	<?php
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $folder = $_SESSION['userFolder'];?>
        <section>
            <h4>Welcome back, <?php echo htmlspecialchars($username);?></h4>
            <?php
            require_once('../../mysqli_connect.php');
            $sql_get_uploads = 'SELECT fileName, fileType, artist, trackName
                                FROM user_uploads
                                WHERE username = ?';
            $stmt_get_uploads = mysqli_prepare($dbc, $sql_get_uploads);
            mysqli_stmt_bind_param($stmt_get_uploads, 's', $username);
            mysqli_stmt_execute($stmt_get_uploads);
            $upload_results = mysqli_stmt_get_result($stmt_get_uploads);
            mysqli_free_result($stmt_get_uploads);
            if(mysqli_stmt_affected_rows($stmt_get_uploads)) {
                while($row = mysqli_fetch_assoc($upload_results)) {?>
                    <a href="audio.php?file=<?= $row['fileName']?>" target="_blank"><?= $row['artist'] . ' - ' . $row['trackName']?></a>
                    <br>
                    <br>
                    <?php }
            }
            else
                echo '<h4>No files found</h4>';?>
        </section>
    <?php } else {?>
        <section>
            <h3>You have reached this page in error.</h3>
            <h3>Please click the link in the top left to return to the home page.</h3>
        </section>
    <?php }?>
	</main>
<?php
include('includes/footer.php');?>
