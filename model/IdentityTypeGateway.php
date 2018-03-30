<?php
require_once 'Logger.php';
/**
 * This is the Db Class for Application
 * @copyright Hans Colman
 * @author Hans Colman
 */
class IdentityTypeGateway extends Logger {
    /**
     * @var string This variable will keep the table for the logging
     */
    private static $table = 'identitytype';
    /**
     * This function will return all Active IdentiyTypes
     * @return Array
     */
    public function getAllTypes(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description from identitytype where Active = 1";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectAll($order)
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Type";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from identitytype it order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch($search)
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from identitytype "
                . "where Type like :search or Description like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::activate($UUID,$AdminName)
     */
    public function activate($UUID, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update IdentityType set Active = 1, Deactivate_reason = NULL where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = "IdentityType with ".  $this->getType($UUID)." ".  $this->getDescription($UUID);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     */
    public function delete($UUID, $reason, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update IdentityType set Active = 0, Deactivate_reason = :reason where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Value = "IdentityType with ".  $this->getType($UUID)." ".  $this->getDescription($UUID);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
            print "UUID: ".$UUID." reason: ".$reason."<br>";
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will add a new IdentityType
     * @param string $type
     * @param string $description
     * @param string $AdminName The name of the administrator that did the creation
     */
    public function create($type,$description, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Insert into IdentityType (Type,Description) values (:type,:description)";
            $q = $pdo->prepare($sql);
            $q->bindParam(':type',$type);
            $q->bindParam(':description',$description);
            if ($q->execute()){
                $Value = "IdentityType width Type: ".$type." and Description ".$description;
                $UUIDQ = "Select Type_ID from IdentityType order by Type_ID desc limit 1";
                $stmnt = $pdo->prepare($UUIDQ);
                $stmnt->execute();
                $row = $stmnt->fetch(PDO::FETCH_ASSOC);
                Logger::logCreate(self::$table, $row["Type_ID"], $Value, $AdminName);
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from identitytype it where Type_ID= :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This will update an givven IdentotyType
     * @param int $UUID
     * @param string $type
     * @param string $description
     * @param string $AdminName The name of the administrator that did the update
     */
    public function update($UUID,$type,$description,$AdminName){
        $OldType = $this->getType($UUID);
        $OldDescription = $this->getDescription($UUID);
        //Detect Changes
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if (strcasecmp($OldDescription, $description) != 0){
                $this->logUpdate(self::$table, $UUID, "Description", $OldDescription, $description, $AdminName);
                $sql =  "Update IdentityType set Description = :description where Type_ID = :uuid" ;
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':description',$description);
                $q->execute();
            }
            if (strcasecmp($OldType,$type) != 0){
                $this->logUpdate(self::$table, $UUID, "Type", $OldType, $type, $AdminName);
                $sql =  "Update IdentityType set Type = :Type where Type_ID = :uuid" ;
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':Type',$type);
                $q->execute();
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will check if the same Identity Type exist.
     * @param string $Type
     * @param string $Description
     * @return boolean
     * @throws PDOException
     */
    public function CheckDoubleEntry($Type,$Description) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select * from IdentityType where Type =:Type and Description = :Description";
            $q = $pdo->prepare($sql);
            $q->bindParam(':Type',$Type);
            $q->bindParam(':Description',$Description);
            $q->execute();
            if ($q->rowCount()>0){
                return TRUE;
            }  else {
                return FALSE;
            }
        }  catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Type
     * @param int $UUID
     * @return string
     */
    private function getType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from IdentityType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Description
     * @param int $UUID
     * @return string
     */
    private function getDescription($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Description from IdentityType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Description"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
