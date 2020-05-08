<?php
require_once('../../secure_conn.php');
include('includes/header.php');
?>
    <main>
        <section>
            <form action="admin.php" action="POST">
                <input type="text" name="username">
                <br>
                <br>
                <select name="role">
                    <?php
                    require_once('../../mysqli_connect.php');
                    $sql_get_roles = 'SELECT DISTINCT role
                                      FROM users';
                    $stmt_get_roles = mysqli_prepare($dbc, $sql_get_roles);
                    mysqli_stmt_execute($stmt_get_roles);
                    $roles_result = mysqli_stmt_get_result($stmt_get_roles);
                    mysqli_free_result($stmt_get_roles);
                    $roles_result = mysqli_fetch_assoc($roles_result);
                    foreach($roles_result as $role) {?>
                        <option value="<? echo htmlspecialchars($role['role']);?>"><? echo htmlspecialchars($role['role']);?></option>
                    <?php }
                    ?>
                </select>
            </form>
        </section>
    </main>
