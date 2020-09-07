<?php
require_once 'ValidationException.php';
/**
 * This is the Service Class
 * @copyright Hans Colman
 * @author Hans Colman
 */
abstract class Service {
    /**
     * This function will return all objects
     * @param string $order The name of the column the sorting will be done
     * @return array
     */
    abstract function getAll($order);
    /**
     * This function will return all details of the given object
     * @param mixed $id The unique ID of the Object
     * @return array
     */
    abstract function getByID($id);
    /**
     * This function will deactivate the given object
     * @param mixed $id The unique ID of the Object
     * @param string $reason The reason of deletion
     * @param string $AdminName The name of the person who is doing the deletion
     * @throws ValidationException
     * @throws PDOException
     */
    abstract function delete($id,$reason,$AdminName);
    /**
     * This function will activate the given object 
     * @param mixed $id The unique ID of the Object
     * @param string $AdminName The name of the person who is doing the activation
     * @throws PDOException
     */
    abstract function activate($id, $AdminName);
    /**
     * This function will search in all object for a given string
     * @param string $search The search term
     * @return array
     */
    abstract function search($search);
    /**
     * This function will Validate the Delete Parameter
     * @param String $reason
     * @throws ValidationException
     */
    protected function validateDeleteParams($reason){
        $errors = array();
        if (empty($reason)) {
            $errors[] = 'Please enter Reason';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }
    /**
     * This funtion will validate the release params
     * @param int $IdenId
     * @param mixed $id
     * @param string $Employee
     * @param string $ITEmployee
     * @throws ValidationException
     */
    protected  function validateReleaseParams($IdenId,$id,$Employee,$ITEmployee) {
        $errors = array();
        if (empty($Employee)) {
            $errors[] = 'Please fill in an Employee';
        }
        if (empty($ITEmployee)) {
            $errors[] = 'Please fill an IT Employee';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * This function will generete the Release PDF
     * @param int $IdentiyId
     * @param string $AssetTag
     * @param string $Employee
     * @param string $ITEmployee
     */
    public function generateReleasePdf($IdenRows,$DeviceRows,$Employee,$ITEmployee) {
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        $AssignForm->setTitle("Release");
        foreach ($IdenRows as $identity):
            $AssignForm->setReceiverInfo($identity['Name'], htmlentities($identity['language']),htmlentities($identity['UserID']));
        endforeach;
        foreach ($DeviceRows as $device):
            $AssignForm->setAssetInfo(htmlentities($device["Category"]), htmlentities($device["Type"]), htmlentities($device["SerialNumber"]), htmlentities($device["AssetTag"]));
        endforeach;
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
    /**
     * This function will generate the AssignForm PDF
     * @param string $AssetTag AssetTag of the Device
     * @param string $Employee The name of the employee
     * @param string $ITEmployee The name of the IT employe
     */
    public function generateAssignPDF($IdenRows,$DeviceRows,$Employee,$ITEmployee){
        require_once 'PDFGenerator.php';
        $AssignForm = new PDFGenerator();
        foreach ($IdenRows as $identity){
            $AssignForm->setReceiverInfo(htmlentities($identity['Name']), htmlentities($identity['language']),htmlentities($identity['UserID']));
        }
        foreach ($DeviceRows as $device){
            $AssignForm->setAssetInfo($device["Category"], htmlentities($device['Type']), htmlentities($device['SerialNumber']), htmlentities($device['AssetTag']));
        }
        $AssignForm->setEmployeeSingInfo($Employee);
        $AssignForm->setITSignInfo($ITEmployee);
        $AssignForm->createPDf();
    }
}
