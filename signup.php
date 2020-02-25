<?php
$title = 'Sign Up';
include('includes/header.php');
if(isset($_POST['register'])) {


    if (!empty($_POST['fName']))
        $first_name = filter_var(trim($_POST['fName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['first_name'] = '<span class="warn">A valid first name is required.</span>';

    if (!empty($_POST['lName']))
        $last_name = filter_var(trim($_POST['lName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['last_name'] = '<span class="warn">A valid last name is required.</span>';

    if (!empty($_POST['eml']))
        $email = filter_var(trim($_POST['eml']), FILTER_SANITIZE_EMAIL);
    else
        $incomplete['email'] = '<span class="warn">A valid email is required.</span>';

    if (!empty($_POST['dob']))
        $date_of_birth = filter_var(trim($_POST['dob']), FILTER_SANITIZE_NUMBER_INT);
    else
        $incomplete['date_of_birth'] = '<span class="warn">A valid date of birth is required.</span>';

    if (isset($_POST['gender']))
        $gender = $_POST['gender'];
    else
        $incomplete['gender'] = '<span class="warn">A gender must be selected.</span>';

    if (!empty($_POST['uName']))
        $username = filter_var(trim($_POST['uName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['username'] = '<span class="warn">A username is required.</span>';

    if (!empty($_POST['pwd']))
        $password = filter_var(trim($_POST['pwd']), FILTER_SANITIZE_STRING);
    else
        $incomplete['password'] = '<span class="warn">Enter and verify your password.</span>';

    if (!empty($_POST['pwdVer']))
        $password_verif = filter_var(trim($_POST['pwdVer']), FILTER_SANITIZE_STRING);
    else
        $incomplete['password'] = '<span class="warn">Enter and verify your password.</span>';

    if ($password != $password_verif)
        $incomplete['password_verif'] = '<span class="warn">Passwords do not match.</span>';

    if(!isset($incomplete)) {
        echo '<p>Thank you, <strong>' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name). '</strong>, for registering for Music Shop:<br>';
        echo 'You entered <strong>'. htmlspecialchars($date_of_birth) . '</strong> for your date of birth, and <strong>' . htmlspecialchars($gender) . '</strong> for your gender<br>';
        echo 'Additionally, your email is <strong>' . htmlspecialchars($email) . '</strong>, and you chose <strong>' . htmlspecialchars($username) . '</strong> as your username.<br></p>';
        include('includes/footer.php');
        exit;
    }
}
?>
<section class="registration-form-container">
    <div class="registration-form">
        <form action="signup.php" method="POST">
            <?php if(isset($incomplete['first_name'])) echo $incomplete['first_name'] . '<br>'; ?>
            <label><input type="text" name="fName" <?php if(isset($first_name)) echo 'value="' . htmlspecialchars($first_name) . '"'; else echo 'placeholder="first name"'; ?>></label>
            <br>
            <br>
            <?php if(isset($incomplete['last_name'])) echo $incomplete['last_name'] . '<br>'; ?>
            <label><input type="text" name="lName" <?php if(isset($last_name)) echo 'value="' . htmlspecialchars($last_name) . '"'; else echo 'placeholder="last name"'; ?>></label>
            <br>
            <br>
            <?php if(isset($incomplete['email'])) echo $incomplete['email'] . '<br>'; ?>
            <label><input type="email" name="eml" <?php if(isset($email)) echo 'value="' . htmlspecialchars($email) . '"'; else echo 'placeholder="email"'; ?>></label>
            <br>
            <br>
            <?php if(isset($incomplete['date_of_birth'])) echo $incomplete['date_of_birth'] . '<br>'; ?>
            <label><input type="date" name="dob" <?php if(isset($date_of_birth)) echo 'value="' . htmlspecialchars($date_of_birth) . '"'; ?>></label>
            <br>
            <br>
            <?php if(isset($incomplete['gender'])) echo $incomplete['gender'] . '<br>'; ?>
            <label class="gender"><input type="radio" name="gender" value="M" <?php if(isset($gender) && $gender == 'M') echo ' checked'; ?>> Male</label>
            <label class="gender"><input type="radio" name="gender" value="F" <?php if(isset($gender) && $gender == 'F') echo ' checked'; ?>> Female</label>
            <label class="gender"><input type="radio" name="gender" value="N/A" <?php if(isset($gender) && $gender == 'N/A') echo ' checked'; ?>> N/A</label>
            <br>
            <br>
            <br>
            <?php if(isset($incomplete['username'])) echo $incomplete['username'] . '<br>'; ?>
            <label><input type="text" name="uName" <?php if(isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; else echo 'placeholder="username"'; ?>></label>
            <br>
            <br>
            <?php if(isset($incomplete['password'])) echo $incomplete['password'] . '<br>'; ?>
            <label><input type="password" name="pwd" placeholder="password"></label>
            <br>
            <br>
            <label><input type="password" name="pwdVer" placeholder="verify password"></label>
            <br>
            <label><input type="submit" name="register" value="Register"></label>
        </form>
    </div>
</section>
<?php include('includes/footer.php'); ?>