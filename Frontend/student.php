<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole("student");
hdr("Student");
view();