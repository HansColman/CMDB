<?php
include_once 'header.php';
require 'controller/MobileController.php';
$controller = new MobileController();
$controller->handleRequest();
include 'footer.php';