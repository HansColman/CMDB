<?php
session_start();

require_once 'model/AdminGateway.php';
require_once 'view/view.php';

$Admin = new AdminGateway();
$UserID= $_POST["UserID"];
$Pwd= $_POST["Pwd"];
Try{
    $Admin->login($UserID, $Pwd);
    header('Location: main.php');
}catch (Exception $e){
    $view = new View();
    $view->print_error("Login error", $e->getMessage(),"index.php");
}