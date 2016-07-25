<?php
session_start();

function hdr($title, $showmenu = true) {
    echo '
<!DOCTYPE html>
    <head>
        <title>' . $title . '</title>
        <script src="site.js"></script>
        <link rel="stylesheet" type="text/css" href="site.css">
    </head>
    <body>';

    if($showmenu)
        echo '
        <a href="edit_user.php" style="float:right">Account settings</a><br>
        <a href="index.php?logout=1" style="float:right">Logout</a>
    ';
}

function footer() {
    echo '
</body>
</html>
    ';
}