<?php
include("Garyutil.class.php");

$id = 15;

$data = ["id" => $id];

$response = util::ForwardDeleteRequest("question.php", $data);

var_dump($response);
