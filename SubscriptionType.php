<?php
include_once 'header.php';
require_once 'controller/SubscriptionTypeController.php';
$controller = new SubscriptionTypeController();
$controller->handleRequest();
include 'footer.php';
?>