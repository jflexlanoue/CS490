<?php
include("Garyutil.class.php");
include("htmlutil.php");

util::VerifyRole("student");
hdr("Student");
?>

<div style="text-align: center;">
    <h1>Student</h1>

    <div><a href="exam_list.php">Take Exam</a></div>
    <div><a href="results.php">View Grades</a></div>

</div>