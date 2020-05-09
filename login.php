<?php
require_once('../../secure_conn.php');
include('includes/header.php');
if(isset($_POST['login'])) {
    if (!empty($_POST['username'])) {
        if (filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING))
            $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        else
            $error['username'] = '<span class="warn">Invalid username.</span>';
    } else
        $error['username'] = '<span class="warn">Enter a username.</span>';

    if (!empty($_POST['password']))
        $password = $_POST['password'];
    else
        $error['password'] = '<span class="warn">Enter a password.</span>';

    while (!isset($error)) {
        require_once('../../mysqli_connect.php');
        $sql_check_username = 'SELECT username, role, userFolder, pwdHash
                               FROM users
                               WHERE username = ?';
        $stmt_check_username = mysqli_prepare($dbc, $sql_check_username);
        mysqli_stmt_bind_param($stmt_check_username, 's', $username);
        mysqli_stmt_execute($stmt_check_username);
        $username_result = mysqli_stmt_get_result($stmt_check_username);
        $username_rows = mysqli_num_rows($username_result);
        mysqli_free_result($stmt_check_username);
        if ($username_rows != 0) {
            $username_result = mysqli_fetch_assoc($username_result);
            $password_hash = $username_result['pwdHash'];
            if (password_verify($password, $password_hash)) {
                $_SESSION['username'] = $username_result['username'];
                $_SESSION['role'] = $username_result['role'];
                $_SESSION['userFolder'] = $username_result['userFolder'];
                header('Location: http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/landing.php');
                exit;
            } else
                $error['password'] = '<span class="warn">Incorrect password.</span>';
        } else
            $error['username'] = '<span class="warn">Username does not exist.</span>';
    }
}
if(!isset($_SESSION['username'])) {?>
    <section class="center-image-container">
        <div class="login-image">
            <img class="lizard" src="images/login_lizard.gif" alt="Login Lizard">
        </div>
    </section>
    <section class="login-container">
        <form class="login" action="login.php" method="POST">
            <?php if(isset($error['username'])) echo $error['username'] . '<br>';?>
            <label><input type="text" name="username" <?php if(isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; else echo 'placeholder="username"';?>></label>
            <br>
            <?php if(isset($error['password'])) echo $error['password'] . '<br>'; else echo '<br>';?>
            <label><input type="password" name="password" placeholder="password"></label>
            <br>
            <input type="submit" name="login" value="Login">
            <a class="register" href="signup.php">Register</a>
        </form>
    </section>
<?php } else {?>
        <h3>You have reached this page in error.</h3>
        <h3>You are already logged in as <?php echo htmlspecialchars($_SESSION['username']) . '.';?></h3>
<?php
}
include('includes/footer.php');?>