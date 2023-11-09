<?php global $params; ?>
<form method=POST action='/'>
    <label for=firstName>First Name: </label><input type=text id=firstName name=firstName <?php echo (isset($params['firstName'])) ? "value='{$params['firstName']}'" : ""; ?> required><br>
    <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName <?php echo (isset($params['lastName'])) ? "value='{$params['lastName']}'" : ""; ?> required><br>
    <?php if (isset($params['usernameTaken'])) {echo '<span class=w3-text-red>Username taken!</span><br>';} ?>
    <label for=username>Username: </label><input type=text id=username name=username <?php echo (isset($params['username'])) ? "value='{$params['username']}'" : ""; ?> required><br>
    <?php if (isset($params['diffPasswords'])) {echo "<span class=w3-text-red>Passwords don't match!</span><br>";} ?>
    <label for=password>Password: </label><input type=password id=password name=password required><br>
    <label for=password2>Re-enter Password: </label><input type=password id=password2 name=password2 required><br>
    <input type=hidden name=submission-action value=register>
    <input type=submit id=submit name=submit value=Register>
</form>
<h2>
    Already have an account? 
    <?php linkTo("login", "Login");?>
</h2>