<?php
$title = 'Sign Up';
include('includes/header.php');
if(isset($_POST['register'])) {
    if (!empty($_POST['fName']))
        $first_name = filter_var(trim($_POST['fName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['first_name'] = 'Please enter your first name.<br>';

    if (!empty($_POST['lName']))
        $last_name = filter_var(trim($_POST['lName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['last_name'] = 'Please enter your last name.<br>';

    if (!empty($_POST['eml']))
        $email = filter_var(trim($_POST['eml']), FILTER_SANITIZE_EMAIL);
    else
        $incomplete['email'] = 'Please enter a valid email address.<br>';

    if (!empty($_POST['dob']))
        $date_of_birth = filter_var(trim($_POST['dob']), FILTER_SANITIZE_NUMBER_INT);
    else
        $incomplete['date_of_birth'] = 'Please enter a valid date.<br>';

    if (!isset($_POST['gender']))
        $gender = $_POST['gender'];
    else
        $incomplete['gender'] = 'Please select your gender.';

    if (!empty($_POST['uName']))
        $username = filter_var(trim($_POST['uName']), FILTER_SANITIZE_STRING);
    else
        $incomplete['username'] = 'Please enter a valid username.<br>';

    if (!empty($_POST['pwd']))
        $password = filter_var(trim($_POST['pwd']), FILTER_SANITIZE_STRING);
    else
        $incomplete['password'] = 'Please enter and verify your password.<br>';

    if (!empty($_POST['pwdV']))
        $password_verif = filter_var(trim($_POST['pwdV']), FILTER_SANITIZE_STRING);
    else
        $incomplete['password'] = 'Please enter and verify your password.<br>';

    if ($password != $password_verif)
        $incomplete['password_verif'] = 'Your passwords do not match.<br>';

    if(!isset($incomplete)) {
        echo '<p>Thank you, ' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name). ', for registering for Music Shop:<br>';
        echo 'You entered <strong>'. htmlspecialchars($date_of_birth) . '</strong> for your date of birth, and <strong>' . htmlspecialchars($gender) . '</strong> for your gender<br>';
        echo 'Additionally, your email is <strong>' . htmlspecialchars($email) . '</strong>, and you chose <strong>' . htmlspecialchars($username) . '</strong>.<br></p>';
        exit;
    }
}
?>
<section class="registration-form-container">
    <div class="registration-form">
        <form action="signup.php" method="POST">
            <label><input type="text" name="fName" placeholder="first name" autofocus></label>
            <br>
            <br>
            <label><input type="text" name="lName" placeholder="last name"></label>
            <br>
            <br>
            <label><input type="email" name="eml" placeholder="email address"></label>
            <br>
            <br>
            <label><input type="date" name="dob"></label>
            <br>
            <br>
            <label class="gender"><input type="radio" name="gender" value="Male"> Male</label>
            <label class="gender"><input type="radio" name="gender" value="Female"> Female</label>
            <label class="gender"><input type="radio" name="gender" value="Non-Applicable"> N/A</label>
            <br>
            <br>
            <br>
            <br>
            <label><input type="text" name="uName" placeholder="username"></label>
            <br>
            <br>
            <label><input type="password" name="pwd" placeholder="password"></label>
            <br>
            <br>
            <label><input type="password" name="pwdVer" placeholder="verify password"></label>
            <br>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</section>
<?php include('includes/footer.php'); ?>