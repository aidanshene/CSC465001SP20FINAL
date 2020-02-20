<?php
    $title = 'Login';
    include('includes/header.php');
    ?>
<img class="lizard" src="images/login_lizard.gif" alt="Login Lizard">
<section class="login">
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="username" autofocus>
        <br>
        <br>
        <input type="password" name="password" placeholder="password">
        <br>
        <button type="submit" name="submit">Login</button>
        <a class="register" href="signup.php">Register</a>
    </form>
</section>
<?php include('includes/footer.php'); ?>