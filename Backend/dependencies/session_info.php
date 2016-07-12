<?php

require 'database.php';

$result["username"] = $_SESSION["username"];
$result["authenticated"] = $_SESSION["authenticated"];
$result["permission"] = $_SESSION["permission"];