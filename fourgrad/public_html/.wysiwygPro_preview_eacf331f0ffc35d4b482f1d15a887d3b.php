<?php
if ($_GET['randomId'] != "_ZMPpKpNy10fcJwDVRY1dt3WlJHRzKBuW7raFC5x4vWYuIwIfbXMWLdlWDAnLvwg") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
