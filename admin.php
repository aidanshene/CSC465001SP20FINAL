<?php
require_once('../../secure_conn.php');
include('includes/header.php');?>
    <main>
<?php
if(isset($_POST['update'])) { // Checks if the role update submission was clicked.
    if (!empty($_POST['username'])) {
        if (preg_match('/^\w+$/', $_POST['username']))
            $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        else
            $error['username'] = '<span class="warn">Invalid username.</span>';
    } else
        $error['username'] = '<span class="warn">Enter a username.</span>';

    $role = filter_var(trim($_POST['role']), FILTER_SANITIZE_STRING);

    if(!isset($error)) {
        require_once('../../mysqli_connect.php');
        $sql_check_username = 'SELECT username, role
                               FROM users
                               WHERE username = ?';
        $stmt_check_username = mysqli_prepare($dbc, $sql_check_username);
        mysqli_stmt_bind_param($stmt_check_username, 's', $username);
        mysqli_stmt_execute($stmt_check_username);
        $username_result = mysqli_stmt_get_result($stmt_check_username);
        $username_rows = mysqli_num_rows($username_result);
        mysqli_free_result($stmt_check_username);
        $username_result = mysqli_fetch_assoc($username_result);
        if($username_result['role'] == $role)
            $error['role'] = '<span class="warn">User already has this role.</span>';
        elseif($username_rows != 0) {
            $sql_update_role = 'UPDATE users
                                SET role = ?
                                WHERE username = ?';
            $stmt_update_role = mysqli_prepare($dbc, $sql_update_role);
            mysqli_stmt_bind_param($stmt_update_role, 'ss', $role, $username);
            mysqli_stmt_execute($stmt_update_role);

            if (mysqli_stmt_affected_rows($stmt_update_role)) {
                $success = '<span>Role set to <strong>' . htmlspecialchars($role) . '</strong> for <strong>' . htmlspecialchars($username) . '</strong></span>';
                $username = NULL;
                $role = NULL;
            }
            else
                echo 'error';
        }
        else
            $error['username'] = '<span class="warn">Username does not exist.</span>';
    }
}
elseif(isset($_POST['upload'])) { // Checks if the upload file submission was clicked.
    if (isset($_FILES['admin_upload'])) {
        $filename = $_FILES['admin_upload']['name'];
        $destination = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/CSC465001SP20FINAL/images/' . $filename;
        if (move_uploaded_file($_FILES['admin_upload']['tmp_name'], $destination)) {
            $success = '<span>' . htmlspecialchars($filename) . ' successfully uploaded.</span>';
        }
        elseif ($_FILES['admin_upload']['error'] > 0) {
            echo '<p class="error">The file could not be uploaded because: <strong>';

            // Print a message based upon the error.
            switch ($_FILES['admin_upload']['error']) {
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
            } // End of switch.
            echo '</strong></p>';
        } // End of error IF.
        else {
            echo '<h3>Some unknown error has occurred.</h3>';
        }
    } //isset $_FILES
//release the uploaded file resource
if(file_exists($_FILES['admin_upload']['tmp_name']) && is_file($_FILES['admin_upload']['tmp_name']))
    unlink($_FILES['admin_upload']['tmp_name']);
}
if(isset($_SESSION['username']) && ($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Admin')) {
        if(isset($success)) echo '<h4>' . $success . '</h4>';?>
        <section class="admin">
            <form action="admin.php" method="POST">
                <br>
                <h4>Change a user's role:</h4>
                <?php if(isset($error['username'])) echo $error['username'] . '<br>'; else echo '<br>';?>
                <label><input type="text" name="username" <?php if(isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; else echo 'placeholder="username"';?>></label>
                <br>
                <?php if(isset($error['role'])) echo $error['role'] . '<br>'; else echo '<br>';?>
                <label>Role
                <select name="role">
                    <option value="User" <?php if(isset($role) && $role == 'User') echo ' selected';?>>User</option>
                    <option value="Admin" <?php if(isset($role) && $role == 'Admin') echo ' selected';?>>Admin</option>
                    <?php if($_SESSION['role'] == 'Owner') {?>
                    <option value="Owner" <?php if(isset($role) && $role == 'Owner') echo ' selected';?>>Owner</option>
                    <?php }?>
                </select></label>
                <br>
                <br>
                <input type="submit" name="update" value="Update">
            </form>
        </section>
        <section class="admin">
            <form enctype="multipart/form-data" action="admin.php" method="POST">
                <br>
                <h4>Upload a site file:</h4>
                <br>
                <input type="file" name="admin_upload">
                <br>
                <br>
                <input type="submit" name="upload" value="Upload">
            </form>
        </section>
<?php } else {?>
        <h3>You have reached this page in error.</h3>
        <h3>You do not have permission for this page.</h3>
<?php }?>
    </main>
<?php
include('includes/footer.php');?>
