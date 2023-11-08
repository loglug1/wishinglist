<?php
echo "You're home!";
?>
<form method=POST action='/'>
    <input type=hidden name=submission-action value=logout>
    <input type=submit name=submit value=Logout>
</form>