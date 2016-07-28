<?php

/*
require_once 'Twig-1.24.1/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('../Views/');
$twig = new Twig_Environment($loader, array(
    'cache' => 'Twig-1.24.1/lib/Twig/cache',
));
*/

# Reports uncaught exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    echo ("Error " . $errno . ': ' . $errstr . "<br>"
        . 'Line ' . $errline . ': ' . $errfile. "<br>");
    $traces = debug_backtrace();
    foreach($traces as $trace) {
        echo ($trace["file"] . ": " . $trace["line"] . " in " . $trace["function"] . "<br>");
    }
    echo "<br>";
}
set_error_handler("exception_error_handler");

session_start();
$cwd = getcwd();
$view = array();

// LEGACY - USE view() and *.plate templates
function hdr($title, $showmenu = true) {
    global $view;
    if(!$showmenu)
        $view["menu_style"] = "display:none";
    $view["title"] = $title;
    ob_start();
    register_shutdown_function('footer');
}
// LEGACY - DO NOT USE
function footer() {
    global $template;
    global $view;
    $output = ob_get_contents();
    ob_get_clean();
    $view = array_merge($view, array("child" => $output));
    if(isset($template)) {
        render_file($template, $view);
    } else {
        render_file("site", $view);
    }
}

function Printable($item) {
    $item = str_replace("\n", "<br>", $item);
    $item = str_replace("  ", "&nbsp&nbsp", $item);
    return $item;
}

function GetTag($tagvalue, &$values) {
    // Variable substitution
    if(isset($values[$tagvalue]))
        return $values[$tagvalue];
    $trimmed = trim($tagvalue);
    if(isset($values[$trimmed]))
        return $values[$trimmed];

    // Variable setting
    if(strpos($tagvalue,"!") !== false) {
        $start = strpos($tagvalue,"!") + 1;
        $mid = strpos($tagvalue, "=");
        $end = strlen($tagvalue);
        $name = trim(substr($tagvalue, $start, $mid - $start));
        $value = trim(substr($tagvalue, $mid + 1, $end - $mid));
        $values[$name] = $value;
    }

    // Loops
    global $looping_over;
    global $looping;
    global $loop_template;
    if(strpos($tagvalue,"loop-over") !== false) {
        $mid = strpos($tagvalue, ":");
        $end = strlen($tagvalue);
        $itemname = trim(substr($tagvalue, $mid + 1, $end - $mid));
        $looping = true;
        $looping_over = $values[$itemname];
    }
    if(strpos($tagvalue,"end-loop") !== false) {
        $ret = "";
        $looping = false;
        foreach($looping_over as $item) {
            $ret .= render_internal($loop_template, $item);
        }
        return $ret;
    }

    return "";
}

// Values needs to be passed by reference for {{!var = val}} syntax to work
// This function makes it possible to call render(..., array()) without an error
function render($string, $values) {
    return render_internal($string, $values);
}

// Tiny template renderer
function render_internal($string, &$values) {
    if(strpos($string,"%%template:") !== false) {
        $end = strpos($string, "%%", 11);
        $template = trim(substr($string, 11, $end - 11));
        $string = substr($string, $end + 2);
        $values["child"] = render_internal($string, $values);
        return render_file_to_string($template, $values);
    }

    global $looping;
    global $loop_template;
    $parts = array();
    $position = 0;
    while(true) {
        // Find next tag
        $start = strpos($string, "{{", $position);
        if($start === FALSE)
            break;
        $end = strpos($string, "}}", $start);
        if($end === FALSE)
            break;

        // Normal processing
        if(!$looping) {
            // Capture part of template between the last tag and this one
            $parts[] = substr($string, $position, $start - $position);

            // Get tag text
            $tag = substr($string, $start + 2, $end - $start - 2);

            // Get tag value
            $parts[] = GetTag($tag, $values);

            // Update string position
            $position = $end + 2;
        } else {
            // Loop handling

            $end = strpos($string, "{{ end-loop }}", $start);
            $loop_template = substr($string, $position, $end - $position);

            // Get tag value
            $parts[] = GetTag("{{ end-loop }}", $values);

            // Update string position
            $position = $end + 14;
        }
    }

    // Get last part of template
    $parts[] = substr($string, $position, strlen($string) - $position);

    // Stringify parts
    return implode($parts);
}

function render_file_to_string($filename, $values, $echo = true) {
    global $cwd;
    return render(file_get_contents($cwd . "/Views/" . $filename . ".plate"), $values);
}

function render_file($filename, $values) {
    echo render_file_to_string($filename, $values);
}

// Magically renders the view with the same name as the url (eg index.php -> index.plate)
function view($view_override = null) {
    global $view;
    global $twig;
    if(isset($view_override)) {
        render_file($view_override, $view);
    } else {
        $url = basename($_SERVER['PHP_SELF'], '.php');
        render_file($url, $view);
        //echo $twig->render($url, $view);
    }
}