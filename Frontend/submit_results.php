<?php
session_start();
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole('student');

print_r($_POST);
