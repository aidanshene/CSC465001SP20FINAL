<?php
require_once('../../secure_conn.php');
include('includes/header.php');
echo '<main>';
if (isset($_SESSION['userFolder'])){
    if(!empty($_POST['artist'])) {
        if (strlen($_POST['artist']) <= 30) {
            if (ctype_alnum($_POST['artist']))
                $artist = filter_var(trim($_POST['artist']), FILTER_SANITIZE_STRING);
            else
                $error['artist'] = '<span class="warn">Artist name must be alphanumeric.</span>';
        }
        else
            $error['artist'] = '<span class="warn">Artist name cannot contain more than 30 characters.</span>';
    }

    if(!empty($_POST['trackName'])) {
        if (strlen($_POST['trackName']) <= 30) {
            if (ctype_alnum($_POST['trackName']))
                $track_name = filter_var(trim($_POST['artist']), FILTER_SANITIZE_STRING);
            else
                $error['track_name'] = '<span class="warn">Track name must be alphanumeric.</span>';
        }
        else
            $error['track_name'] = '<span class="warn">Track name cannot contain more than 30 characters.</span>';
    }

    if(isset($_POST['upload'])) {
        if(isset($_FILES['user_upload'])) {
            $allowed = array ('audio/mpeg', 'audio/mp4', 'audio/wave', 'audio/flac', 'audio/ogg');
            if(in_array($_FILES['user_upload']['type'], $allowed)) {
                $folder = $_SESSION['userFolder'];
                $destination = $_FILES['user_upload']['tmp_name'];
                if (move_uploaded_file($_FILES['user_upload']['tmp_name'], "../../uploads/$folder/{$_FILES['user_upload']['name']}")) {
                    echo '<h2>The file ' . $_FILES['user_upload']['name'] . ' has been uploaded!</h2>';
                    $name = $_FILES['user_upload']['name'];
                    $type = $_FILES['user_upload']['type'];

                    //write to database
                    require_once ('../../mysqli_connect.php'); // Connect to the db.
                    $sql_insert_upload = 'INSERT into user_uploads (username, fileName, fileType, artist, trackName) 
                                          VALUES (?, ?, ?, ?, ?)';
                    $stmt_insert_upload = mysqli_prepare($dbc, $sql_insert_upload);
                    $username = $_SESSION['username'];
                    mysqli_stmt_bind_param($stmt_insert_upload, 'sssss', $username, $name, $type, $artist, $track_name);
                    mysqli_stmt_execute($stmt_insert_upload);
                    if (mysqli_stmt_affected_rows($stmt_insert_upload))
                        echo '<h4>And the file data has been saved.</h4>';
                    else
                        echo '<h4>We were unable to save your file data.</h4>';

                    if (file_exists($_FILES['user_upload']['tmp_name']) && is_file($_FILES['user_upload']['tmp_name']))
                        unlink ($_FILES['user_upload']['tmp_name']);
                }
            }
            else
                $error['upload'] = '<span class="warn">Upload must be an audio file.</span>';
        }
        if ($_FILES['user_upload']['error'] > 0) {
            echo '<p class="warning">The file could not be uploaded because: <strong>';
            // Print a message based upon the error.
            switch ($_FILES['user_upload']['error']) {
                case 1:
                    echo 'The file exceeds the upload_max_filesize setting in php.ini.';
                    break;
                case 2:
                    echo 'The file exceeds the MAX_FILE_SIZE setting in the HTML form.';
                    break;
                case 3:
                    echo 'The file was only partially uploaded.';
                    break;
                case 4:
                    echo 'No file was uploaded.';
                    break;
                case 6:
                    echo 'No temporary folder was available.';
                    break;
                case 7:
                    echo 'Unable to write to the disk.';
                    break;
                case 8:
                    echo 'File upload stopped.';
                    break;
                default:
                    echo 'A system error occurred.';
                    break;
            }
            echo '</strong></p>';
        }
    }
}
else {
    echo "<h4>We are sorry, but you must be logged in as a registered user to upload files.</h4>";
    echo "<h4>Try logging out and logging back in if you believe this is an error.</h4></main>";
    include('includes/footer.php');
    exit;
}
?>
        <h2>Upload a song:</h2>
        <form enctype="multipart/form-data" action="upload.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <fieldset>
                <legend>Select an audio file of 2M or smaller to be uploaded:</legend>
                <?php if(isset($error['artist'])) echo $error['artist'] . '<br>'; else echo '<br>';?>
                <label><input type="text" name="artist" placeholder="Artist"></label>
                <br>
                <?php if(isset($error['track_name'])) echo $error['track_name'] . '<br>'; else echo '<br>';?>
                <label><input type="text" name="trackName" placeholder="Track Name"></label>
                <br>
                <?php if(isset($error['upload'])) echo $error['upload'] . '<br>'; else echo '<br>';?>
                <label for="file">
                    <input type="file" name="user_upload" id="file"></label>
                <br>
                <input type="submit" name="upload" value="Upload">
            </fieldset>

        </form>
    </main>
<?php
include('includes/footer.php');?>