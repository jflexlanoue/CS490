<?php
include("util.class.php");


$util = new util();


var_dump($util->LogInUser("admin", "admin"));

?>
<br/><br/>
<?php

var_dump($util->IsLoggedIn());

