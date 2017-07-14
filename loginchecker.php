<?php
session_start();

require_once 'model/AdminGateway.php';
$Admin = new AdminGateway();
print_r($_POST);
$UserID= $_POST["UserID"];
$Pwd= $_POST["Pwd"];
Try{
    $Admin->login($UserID, $Pwd);
    header('Location: main.php');
}catch (Exception $e){
    print $e->getMessage();
}