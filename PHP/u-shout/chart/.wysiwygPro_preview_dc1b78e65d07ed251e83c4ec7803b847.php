<?php
if ($_GET['randomId'] != "WQGEbsCA5eCYUnJcdBHq4dqPWn_uwHPYALWLCwiBvESbMqprjoJVO7HeLT_RRIwH") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
