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
        <a href="index.php?logout=1" style="float:right">Logout</a><br>
    ';
}
function footer() {
    echo '
</body>
</html>
    ';
}