<?php
include("Util/Garyutil.class.php");
include("Util/htmlutil.php");

util::VerifyRole('student');
echo '<a href="student.php" style="float:left">Return to main page</a>';
hdr("Exam Complete");
?>

<div style="text-align: center;">
    <h1>Exam Complete</h1>
</div>
