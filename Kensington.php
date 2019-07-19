<?php
include_once 'header.php';
require_once 'controller/KensingtonController.php';
$_SESSION["Class"] = "Kensington";
$controller = new KensingtonController();
$controller->handleRequest();
include 'footer.php';
