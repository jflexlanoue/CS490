<?php
	session_start();
	session_unset();
	hdr("Location: index.php");
?>