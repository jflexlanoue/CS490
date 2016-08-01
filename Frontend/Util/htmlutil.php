<?php
session_start();

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


require_once 'Twig-1.24.1/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('Views');

$twig = new Twig_Environment($loader, array(
    'cache' => false,
));

$twig->addExtension(new Twig_Extensions_Extension_Text());

$cwd = getcwd();
$view = array();

function Printable($item) {
    $item = trim($item);
    $item = str_replace("&lt;br/&gt;", "<br>", $item);
    $item = str_replace("\n", "<br>", $item);
    $item = str_replace("\t", "&nbsp&nbsp", $item);
    $item = str_replace("\t", "&nbsp&nbsp", $item);
    $item = str_replace("\u001a", "", $item);
    $item = str_replace("\u0000", "", $item);
    $item = str_replace("", "", $item);
    $item = str_replace("  ", "&nbsp&nbsp", $item);
    return $item;
}

$filter = new Twig_SimpleFilter('printable', function ($string) {
    return Printable($string);
}, array('pre_escape' => 'html', 'is_safe' => array('html')));
$twig->addFilter($filter);

// Magically renders the view with the same name as the url (eg index.php -> index.plate)
function view($view_override = null) {
    global $view;
    global $twig;
    if(isset($view_override)) {
        echo $twig->render($view_override . ".twig", $view);
    } else {
        $url = basename($_SERVER['PHP_SELF'], '.php');
        echo $twig->render($url . ".twig", $view);
    }
}

// Our old template renderer - Moved to Twig
/*
$stack_internal = array();
$stack = array();
$stack_level = 0;

function Startblock() {
    global $stack;
    global $stack_internal;
    global $stack_level;
    $stack_level++;
    array_push($stack_internal, $stack);
    $stack = array();
    $stack["looping"] = false;
}

function &Getstack($index = null) {
    global $stack;
    return $stack;
}

function Getvar($index) {
    global $stack;
    global $stack_level;
    if(!isset($stack[$index]))
    {
        //echo "Not set (" . $stack_level . "): " . $index . "<br>";
        return false;
    }
    return $stack[$index];
}

function Endblock() {
    global $stack;
    global $stack_internal;
    global $stack_level;
    $stack_level--;
    $stack = array_pop($stack_internal);
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
    if(strpos($tagvalue,"loop-over") !== false) {
        Startblock();
        global $stack;
        $mid = strpos($tagvalue, ":");
        $end = strlen($tagvalue);
        $itemname = trim(substr($tagvalue, $mid + 1, $end - $mid));
        $stack["looping"] = true;
        $stack["looping_over"] = $values[$itemname];
    }
    if(strpos($tagvalue,"end-loop") !== false) {
        global $stack;
        $ret = "";
        $stack["looping"] = false;
        foreach(Getvar("looping_over") as $item) {
            $ret .= render_internal($stack["loop_template"], $item);
        }
        Endblock();
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

        global $stack;
        // Normal processing
        if(!Getvar("looping")) {
            // Capture part of template between the last tag and this one

            //$stack["parts"][]
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
            $stack["loop_template"] = substr($string, $position, $end - $position);

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
*/
