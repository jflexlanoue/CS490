<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("student");
hdr("Student");
view();