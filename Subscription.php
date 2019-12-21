<?php
include_once 'header.php';
require_once 'controller/SubscriptionController.php';
$controller = new SubscriptionController();
$controller->handleRequest();
include 'footer.php';
?>