<?php
session_start();
function hdr($title, $showmenu = true) {
    global $template;
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

    if(isset($template))
        ob_start();
}
function footer() {
    global $template;
    if(isset($template)) {
        $output = ob_get_contents();
        ob_get_clean();
        echo render_file($template, array("content" => $output));
    }

    echo '
</body>
</html>
    ';
}

// Tiny Mustache renderer
function render($string, $values) {
    foreach ($values as $key => $value) {
        $string = str_replace("{{" . $key . "}}", $value, $string);
    }
    return $string;
}

function render_file($filename, $values) {
    return render(file_get_contents($filename . ".plate"), $values);
}