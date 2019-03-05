<?php
require_once 'Database.php';
/**
 * This is the Logger Class
 * @copyright Hans Colman
 * @author Hans Colman
 * @abstract
 */
abstract class Logger extends Database{
    private $LogText = '';
    /**
     * This function will delete the given object
     * @param mixed $UUID The unique identifier of the object
     * @param string $reason The reason of deletion
     * @param string $AdminName The Person who did the deletion
     */
    abstract function delete($UUID, $reason,$AdminName);
    /**
     * This function will activate the given object
     * @param mixed $UUID The unique identifier of the object
     * @param string $AdminName The person who did the Activation
     */
    abstract function activate($UUID,$AdminName);
    /**
     * This function will select all object in a certain order
     * @param string $order The Column where the sort will be done on
     * @return array
     */
    abstract function selectAll($order);
    /**
     * This function will return will select only the given object
     * @param mixed $id The unique identifier of the object
     * @return array
     */
    abstract function selectById($id);
    /**
     * This function will return any matching row by the given search term
     * @param string $search the term to search
     * @return array
     */
    abstract function selectBySearch($search);
    /**
     * This function will check if the given assetTag is unique
     * @param string $AssetTag The assetTag of the device
     * @return boolean
     */
    public function isAssetTagUnique($AssetTag) {
        $result = true;
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select serialnumber "
                . "from asset at "
                . "where AssetTag = :assettag";
        $q = $pdo->prepare($sql);
        $q->bindParam(':assettag',$AssetTag);
        $q->execute();
        if ($q->rowCount()>0){
            $result = FALSE;
        }
        Database::disconnect();
        return $result;
    }
    /**
     * This function will log the creation of an object
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $Value Info on the object
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logCreate($Table,$UUID,$Value,$AdminName){
        $this->LogText = "The ".$Value." is created by ".$AdminName." in table ".$Table;
        $this->doLog($Table, $UUID);
    }
    /**
     * this function will log the update of a field in a table
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $field The indication of the column where the change is done
     * @param string $oldValue The old value
     * @param string $NewValue The new value
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logUpdate($Table,$UUID,$field,$oldValue,$NewValue,$AdminName){
        if (empty($oldValue)){
            $oldValue = "Empty";
        }
        if (empty($NewValue)){
            $NewValue = "Empty";
        }
        $this->LogText = "The ".$field." in table ".$Table." has been changed from ".$oldValue." to 
            ".$NewValue." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the deletion of an object
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $Value Information about the object
     * @param string $reason The reason why the deletion is done
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logDelete($Table,$UUID,$Value,$reason,$AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is deleted du to ".$reason." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the activation of an object
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $Value Information about the object
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logActivation($Table,$UUID,$Value,$AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is activated by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the assignment of an Identity to an Account
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $IdentityInfo info about the Identity
     * @param string $AccountInfo info about the Account
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logAssignIden2Account($Table,$UUID,$IdentityInfo,$AccountInfo,$AdminName){
        $this->LogText = "The ".$IdentityInfo." in table ".$Table." is assigned to ".$AccountInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the assignment of an account to an Identity
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $AccountInfo The info of the Account
     * @param string $IdentityInfo The info about the Identity
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logAssignAccount2Iden($Table,$UUID,$AccountInfo, $IdentityInfo, $AdminName){
        $this->LogText = "The ".$AccountInfo." in table ".$Table." is assigned to ".$IdentityInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the assignment of an Device to an Identity
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $DeviceInfo The info of the Device
     * @param string $IdentityInfo The info about the Identity
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logAssignDevice2Identity($Table,$UUID,$DeviceInfo,$IdentityInfo,$AdminName){
        $this->LogText = "The ".$DeviceInfo." in table ".$Table." is assigned to ".$IdentityInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the assignment of an Idenity to an Device
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @param string $IdentityInfo The info of the Identity
     * @param string $DeviceInfo The info about the device
     * @param string $AdminName The name of the administrator who did the action
     */
    protected function logAssignIdentity2Device($Table,$UUID,$IdentityInfo,$DeviceInfo,$AdminName){
        $this->LogText = "The ".$IdentityInfo." in table ".$Table." is assigned to ".$DeviceInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the Release of a device from an Identity
     * @param string $Table
     * @param mixed $UUID
     * @param string $IdentityInfo
     * @param string $DeviceInfo
     * @param string $AdminName
     */
    protected function logRelaseDeviceFromIdentity($Table,$UUID,$IdentityInfo,$DeviceInfo,$AdminName){
        $this->LogText = "The ".$IdentityInfo." in table ".$Table." is released from ".$DeviceInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This function will log the Release of a Identity from a device
     * @param string $Table
     * @param mixed $UUID
     * @param string $DeviceInfo
     * @param string $IdenityInfo
     * @param string $AdminName
     */
    protected function logRelaseIdentityFromDevice($Table,$UUID,$DeviceInfo,$IdenityInfo,$AdminName){
        $this->LogText = "The ".$DeviceInfo." in table ".$Table." is released from ".$IdenityInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This will Log the release from an Account from a Identity
     * @param string $Table
     * @param int $UUID
     * @param string $IdentityInfo
     * @param string $AccountInfo
     * @param string $AdminName
     */
    protected function logReleaseAccountFromIdentity($Table,$UUID,$IdentityInfo,$AccountInfo,$AdminName){
        $this->LogText = "The ".$IdentityInfo." in table ".$Table." is released from ".$AccountInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * This will log the release from an Identity from an Account
     * @param string $Table
     * @param int $UUID
     * @param string $AccountInfo
     * @param string $IdentityInfo
     * @param string $AdminName
     */
    protected function logReleaseIdentityFromAccount($Table,$UUID,$AccountInfo,$IdentityInfo,$AdminName){
        $this->LogText = "The ".$AccountInfo." in table ".$Table." is released from ".$IdentityInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }        
    /**
     * This function will do the logging
     * @param string $Table The table on where the action has been done
     * @param mixed $UUID The unique identifier of the object
     * @throws PDOException
     */
    private function doLog($Table,$UUID){
        try{
            $pdo = Database::connect();
            $LogDate = date("y-m-d h:i:s");
            switch ($Table){
                case "identity":
                    $sql = "INSERT INTO log (Identity,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "identitytype":
                    $sql = "INSERT INTO log (IdentityType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "account":
                    $sql = "INSERT INTO log (Account,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "accounttype":
                    $sql = "INSERT INTO log (AccountType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "role":
                    $sql = "INSERT INTO log (Role,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "roletype":
                    $sql = "INSERT INTO log (RoleType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "assettype":
                    $sql = "INSERT INTO log (AssetType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "devices":
                    $sql = "INSERT INTO log (AssetTag,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "menu":
                    $sql = "INSERT INTO log (menu,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "permissions":
                    $sql = "INSERT INTO log (permissions,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "role_perm":
                    $sql = "INSERT INTO log (role_perm_id,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "application":
                    $sql = "INSERT INTO log (Application,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "token":
                    $sql = "INSERT INTO log (AssetTag,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "kensington":
                    $sql = "INSERT INTO log (Kensington,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "admin":
                	$sql = "INSERT INTO log (Admin,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                	break;
                case "mobile":
                    $sql = "INSERT INTO log (IMEI,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                default :
                    throw new Exception("Class logger reports: Table ".$Table." not Know");
            }
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':log_text',$this->LogText);
            $q->bindParam(':log_date',$LogDate);
            $q->execute();
            Database::disconnect();
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $e){
            throw $e;
        }
    }
}
