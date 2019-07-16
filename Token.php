<?php
include_once 'header.php';
require_once 'controller/TokenController.php';
$controller = new TokenController();
$controller->handleRequest();
$_SESSION["Class"] = "Token";
include 'footer.php';
