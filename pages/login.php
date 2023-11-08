<form method=POST action='/'>
    <label for=username>Username: </label><input type=text id=username name=username><br>
    <label for=password>Password: </label><input type=password id=password name=password><br>
    <input type=hidden name=submission-action value=login>
    <input type=submit name=submit value=Login>
</form>
Don't have an account? 
<?php linkTo("register", "Create One");
?>
