<?php
$title = 'Music Shop - Sign Up';
require_once '../../mysqli_connect.php';
include('includes/header.php');

if(isset($_POST['register'])) { // Input validation and variable declaration for sticky recursive form.
    if (!empty($_POST['fName'])) { // Checks if the first name value is null.
        if (strlen($_POST['fName']) <= 25) { // Checks if the first name value is within varchar(25).
            if (ctype_alpha($_POST['fName'])) // Checks if the first name value is alphabetic.
                $first_name = filter_var(trim($_POST['fName']), FILTER_SANITIZE_STRING); // Stores the valid and filtered first name.
            else
                $incomplete['first_name'] = '<span class="warn">First name must be alphabetical.</span>';
        }
        else
            $incomplete['first_name'] = '<span class="warn">First name cannot contain more than 25 characters.</span>';
    }
    else
        $incomplete['first_name'] = '<span class="warn">First name is a required field.</span>';

    if (!empty($_POST['lName'])) { // Checks if the last name value is null.
        if (strlen($_POST['lName']) <= 25) { // Checks if the last name value is within varchar(25).
            if (ctype_alpha($_POST['lName'])) // Checks if the last name value is alphabetic.
                $last_name = filter_var(trim($_POST['lName']), FILTER_SANITIZE_STRING); // Stores the valid and filtered last name.
            else
                $incomplete['last_name'] = '<span class="warn">Last name must be alphabetical.</span>';
        }
        else
            $incomplete['last_name'] = '<span class="warn">Last name cannot contain more than 25 characters.</span>';
    }
    else
        $incomplete['last_name'] = '<span class="warn">Last name is a required field.</span>';

    if (!empty($_POST['eml'])) { // Checks if the email value is null.
        if (strlen($_POST['eml']) <= 50) { // Checks if the email value is within varchar(50).
            if (filter_var($_POST['eml'], FILTER_VALIDATE_EMAIL)) { // Checks if the email value is in the proper format.
                $email = filter_var(trim($_POST['eml']), FILTER_SANITIZE_EMAIL); // Stores the valid and filtered email.

                $sql_check_email = 'SELECT *
                                    FROM users
                                    WHERE userEmail = ?'; // Query database for rows containing a matching email value.
                $stmt_check_email = mysqli_prepare($dbc, $sql_check_email);
                mysqli_stmt_bind_param($stmt_check_email, 's', $email);
                mysqli_stmt_execute($stmt_check_email);
                mysqli_stmt_store_result($stmt_check_email);
                $email_rows = mysqli_stmt_num_rows($stmt_check_email);
                mysqli_free_result($stmt_check_email); // Clear stored result for next query.

                if ($email_rows != 0) // Checks if there was a pre-existing row in the database.
                    $incomplete['email'] = '<span class="warn">Email specified is already in use.</span>';
            }
            else
                $incomplete['email'] = '<span class="warn">Email must be in a valid format.</span>';
        }
        else
            $incomplete['email'] = '<span class="warn">Email cannot contain more than 50 characters.</span>';
    }
    else
        $incomplete['email'] = '<span class="warn">Email is a required field.</span>';

    if (!empty($_POST['dob'])) // Checks if the date of birth value is null.
        $date_of_birth = filter_var(trim($_POST['dob']), FILTER_SANITIZE_NUMBER_INT); // Stores the valid and filtered date of birth.
    else
        $incomplete['date_of_birth'] = '<span class="warn">Date of birth is a required field.</span>';

    if (isset($_POST['gender']))
        $gender = filter_var(trim($_POST['gender']), FILTER_SANITIZE_STRING); // Stores the valid and filtered gender.
    else
        $incomplete['gender'] = '<span class="warn">Gender is a required field.</span>';

    if (!empty($_POST['uName'])) { // Checks if the username value is null.
        if (strlen($_POST['uName']) <= 20 && strlen($_POST['uName']) >= 3) { // Checks if the username value contains between 3 and 20 characters.
            if(preg_match('/^\w+$/', $_POST['uName'])) { // Checks if the username value matches given regex requirements for characters.
                $username = filter_var(trim($_POST['uName']), FILTER_SANITIZE_STRING); // Stores the valid and filtered username.

                $sql_check_username = 'SELECT *
                                   FROM users
                                   WHERE username = ?'; // Query database for rows containing a matching username value.
                $stmt_check_username = mysqli_prepare($dbc, $sql_check_username);
                mysqli_stmt_bind_param($stmt_check_username, 's', $username);
                mysqli_stmt_execute($stmt_check_username);
                mysqli_stmt_store_result($stmt_check_username);
                $username_rows = mysqli_stmt_num_rows($stmt_check_username);
                mysqli_free_result($stmt_check_username); // Clear stored result for next query.

                if ($username_rows != 0) // Checks if there was a pre-existing row in the database.
                    $incomplete['username'] = '<span class="warn">Username specified is already in use.</span>';
            }
            else
                $incomplete['username'] = '<span class="warn">Username must be alphanumeric and can contain underscores.</span>';
        }
        else
            $incomplete['username'] = '<span class="warn">Username must contain between 3 and 20 characters.</span>';
    }
    else
        $incomplete['username'] = '<span class="warn">Username is a required field..</span>';

    if (!empty($_POST['pwd'])) // Checks if first password value is null.
        $password = $_POST['pwd'];
    else
        $incomplete['password'] = '<span class="warn">Enter and verify your password.</span>';

    if (!empty($_POST['pwdVer'])) // Checks if the second password value is null.
        $password_verify = $_POST['pwdVer'];
    else
        $incomplete['password'] = '<span class="warn">Enter and verify your password.</span>';

    if ($password != $password_verify) // Checks if the first password value is a match with the second password value.
        $incomplete['password_verify'] = '<span class="warn">Passwords do not match.</span>';

    if(!isset($incomplete)) { // Checks if any field of the form is incomplete.
        $sql_insert_account = 'INSERT INTO users (username, userFName, userLName, userEmail, userDOB, userGender, userDOR, pwdHash)
			                   VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)'; // Query the database to insert a new row for the account.
        $stmt_insert_account = mysqli_prepare($dbc, $sql_insert_account);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt_insert_account, 'sssssss', $username, $first_name, $last_name, $email, $date_of_birth, $gender, $password_hash);
        mysqli_stmt_execute($stmt_insert_account);

        if (mysqli_stmt_affected_rows($stmt_insert_account)) { // Checks if the insertion query was run successfully and displays a success message.
            echo '<p>Thank you, <strong>' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name). '</strong>, for registering for Music Shop:<br>';
            echo 'You entered <strong>'. htmlspecialchars($date_of_birth) . '</strong> for your date of birth, and <strong>' . htmlspecialchars($gender) . '</strong> for your gender<br>';
            echo 'Additionally, your email is <strong>' . htmlspecialchars($email) . '</strong>, and you chose <strong>' . htmlspecialchars($username) . '</strong> as your username.<br></p>';
        }
        else
            echo 'error';

        include('includes/footer.php');
        exit;
    }
}
?>
<section class="registration-form-container">
    <div class="registration-form">
        <form action="signup.php" method="POST">
            <?php if(isset($incomplete['first_name'])) echo $incomplete['first_name'] . '<br>';?>
            <label><input type="text" name="fName" <?php if(isset($first_name)) echo 'value="' . htmlspecialchars($first_name) . '"'; else echo 'placeholder="first name"';?>></label>
            <br>
            <?php if(isset($incomplete['last_name'])) echo $incomplete['last_name'] . '<br>'; else echo '<br>';?>
            <label><input type="text" name="lName" <?php if(isset($last_name)) echo 'value="' . htmlspecialchars($last_name) . '"'; else echo 'placeholder="last name"';?>></label>
            <br>
            <?php if(isset($incomplete['email'])) echo $incomplete['email'] . '<br>'; else echo '<br>';?>
            <label><input type="text" name="eml" <?php if(isset($email)) echo 'value="' . htmlspecialchars($email) . '"'; else echo 'placeholder="email"';?>></label>
            <br>
            <?php if(isset($incomplete['date_of_birth'])) echo $incomplete['date_of_birth'] . '<br>'; else echo '<br>';?>
            <label><input type="text" name="dob" <?php if(isset($date_of_birth)) echo 'value="' . htmlspecialchars($date_of_birth) . '"'; else echo 'placeholder="date of birth"';?> onfocus="(this.type='date')" onblur="(this.type='text')"></label>
            <br>
            <?php if(isset($incomplete['gender'])) echo $incomplete['gender'] . '<br>'; else echo '<br>';?>
            <label class="gender"><input type="radio" name="gender" value="M" <?php if(isset($gender) && $gender == 'M') echo ' checked';?>> Male</label>
            <label class="gender"><input type="radio" name="gender" value="F" <?php if(isset($gender) && $gender == 'F') echo ' checked';?>> Female</label>
            <label class="gender"><input type="radio" name="gender" value="N" <?php if(isset($gender) && $gender == 'N') echo ' checked';?>> N/A</label>
            <br>
            <br>
            <?php if(isset($incomplete['username'])) echo $incomplete['username'] . '<br>'; else echo '<br>';?>
            <label><input type="text" name="uName" <?php if(isset($username)) echo 'value="' . htmlspecialchars($username) . '"'; else echo 'placeholder="username"'; ?>></label>
            <br>
            <?php if(isset($incomplete['password'])) echo $incomplete['password'] . '<br>'; else if(isset($incomplete['password_verify'])) echo $incomplete['password_verify'] . '<br>'; else echo '<br>';?>
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