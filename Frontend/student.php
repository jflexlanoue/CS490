<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("student");

$view["title"] = "Student Home";
view();