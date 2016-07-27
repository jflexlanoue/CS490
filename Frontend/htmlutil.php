<?php
session_start();
function hdr($title, $showmenu = true) {
    echo '
<!DOCTYPE html>
    <head>
        <title>' . $title . '</title>
        <script src="site.js"></script>
        <link rel="stylesheet" type="text/css" href="site.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.1.0/css/bulma.css">
    </head>
    <body>';
    if($showmenu)
        echo '

    <nav class="nav">
        <div class="nav-left">
            <a class="nav-item" href="index.php">
                Exam System
            </a>
        </div>

        <div class="nav-right nav-menu">
            <a class="nav-item" href="index.php?logout=1">
                Logout
            </a>
        </div>
    </nav>
    ';
}
function footer() {
    echo '
</body>
</html>
    ';
}