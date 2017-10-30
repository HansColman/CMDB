<?php
include_once 'header.php';
require_once 'controller/DeviceController.php';
//require_once 'Classes/IndentityController.php';
$Category = isset($_GET["Category"])?$_GET["Category"]: NULL;
$_SESSION["Category"] = $Category;
$_SESSION["Class"] = "Device";
//$_SESSION[""]
$controller = new DeviceController();
$controller->handleRequest();
include 'footer.php';