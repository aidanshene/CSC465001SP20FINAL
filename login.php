<?php
$title = 'Music Shop - Login';
include('includes/header.php');
?>
<section class="center-image-container">
    <div class="login-image">
        <img class="lizard" src="images/login_lizard.gif" alt="Login Lizard">
    </div>
</section>
<section class="login-container">
    <form class="login" action="login.php" method="POST">
        <label><input type="text" name="username" placeholder="username" autofocus></label>
        <br>
        <br>
        <label><input type="password" name="password" placeholder="password"></label>
        <br>
        <button type="submit" name="submit">Login</button>
        <a class="register" href="signup.php">Register</a>
    </form>
</section>
<?php include('includes/footer.php'); ?>