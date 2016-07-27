<?php

# Reports uncaught exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    echo ("Error " . $errno . ': ' . $errstr . "\r"
        . 'Line ' . $errline . ': ' . $errfile);
    debug_print_backtrace();
}
set_error_handler("exception_error_handler");

session_start();
register_shutdown_function('footer');
$cwd = getcwd();

function hdr($title, $showmenu = true) {
    global $template_options;
    global $view;

    $view = array();
    $template_options = array();

    if(!$showmenu)
        $template_options["menu_style"] = "display:none";

    $template_options["title"] = $title;
    ob_start();
}

function footer() {
    global $template;
    global $template_options;
    $output = ob_get_contents();
    ob_get_clean();

    if(isset($template)) {
        $template_options = array_merge($template_options, array("main" => render_file_to_string($template, array("content" => $output))));
    } else {
        $template_options = array_merge($template_options, array("main" => $output));
    }
    render_file("site", $template_options);
}

// Tiny Mustache renderer
function render($string, $values) {
    foreach ($values as $key => $value) {
        $string = str_replace("{{" . $key . "}}", $value, $string);
    }
    return $string;
}

function render_file_to_string($filename, $values, $echo = true) {
    global $cwd;
    return render(file_get_contents($cwd . "/Views/" . $filename . ".plate"), $values);
}

function render_file($filename, $values) {
    global $cwd;
    echo render(file_get_contents($cwd . "/Views/" . $filename . ".plate"), $values);
}
function view() {
    global $view;
    $url = basename($_SERVER['PHP_SELF'], '.php');
    render_file($url, $view);
}