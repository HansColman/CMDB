<?php
require_once 'model/Logger.php';

class SubscriptionTypeGateway extends Logger
{
    /**
     * This variable will keep the table for the logging
     * @var string
     */
    private static $table = 'subscriptiontype';
    
    /**
     * {@inheritDoc}
     * @see Logger::selectAll()
     */
    public function selectAll($order){
        if (empty($order)) {
            $order = "Category";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select stt.Type_ID, stt.Type, stt.Description, stt.Provider, c.Category, if(stt.active=1,\"Active\",\"Inactive\") as Active "
            . "from subscriptiontype stt "
            . "join Category c on stt.Category = c.ID order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();            
    }
    /**
     * {@inheritDoc}
     * @see Logger::activate()
     */
    public function activate($UUID, $AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update subscriptiontype set Deactivate_reason = NULL, Active = 1 where Type_ID = :ID";
        $q = $pdo->prepare($sql);
        $q->bindParam(":ID", $UUID);
        if ($q->execute()){
            $Value = "subscription type width Type: ".$this->getType($UUID)." and Description ".$this->getDesctiption($UUID);
            $this->logActivation(self::$table, $UUID, $Value, $AdminName);
        }
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
     */
    public function selectById($id){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select stt.Type_ID, stt.Type, stt.Description, stt.Provider, c.Category, if(stt.active=1,\"Active\",\"Inactive\") as Active "
                . "from subscriptiontype stt "
                . "join Category c on stt.Category = c.ID where stt.Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect(); 
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch()
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select stt.Type_ID, stt.Type, stt.Description, stt.Provider, c.Category, if(stt.active=1,\"Active\",\"Inactive\") as Active "
            . "from subscriptiontype stt "
            . "join Category c on stt.Category = c.ID where stt.Type like :search or stt.Description like :search or stt.Provider like :search or c.Category like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::delete()
     */
    public function delete($UUID, $reason, $AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update subscriptiontype set Deactivate_reason = :reason, Active = 0 where Type_ID = :ID";
        $q = $pdo->prepare($sql);
        $q->bindParam(":reason", $reason);
        $q->bindParam(":ID", $UUID);
        if ($q->execute()){
            $Value = "subscription type width Type: ".$this->getType($UUID)." and Description ".$this->getDesctiption($UUID);
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
    }
    /**
     * This function will create a new Subscription type
     * @param string $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     * @param string $AdminName The name of the administrator who did the action
     */
    public function Create($Type, $Description, $Provider,$Category,$AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into subscriptiontype (Type,Description,provider,Category) values (:type,:description,:provider,:category)";
        $q = $pdo->prepare($sql);
        $q->bindParam(":type", $Type);
        $q->bindParam(":description", $Description);
        $q->bindParam(":provider", $Provider);
        $q->bindParam(":category", $Category);
        if ($q->execute()){
            $Value = "subscription type width Type: ".$Type." and Description ".$Description;
            $UUIDQ = "Select Type_ID from subscriptiontype order by Type_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            Logger::logCreate(self::$table, $row["Type_ID"], $Value, $AdminName);
        }
    }
    /**
     * This function will edit an given Supsrption Type
     * @param int $id
     * @param string $Type
     * @param string $Description
     * @param string $Provider
     * @param int $Category
     * @param string $AdminName
     */
    public function Edit($id,$Type,$Description,$Provider,$Category,$AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $OldType = $this->getType($id);
        $OldDescription = $this->getDesctiption($id);
        $OldProvider = $this->getProvider($id);
        $OldCategoryID = $this->getCategoryID($id);
        $OldCategory = $this->getCategoryFromID($OldCategoryID);
        $NewCategory = $this->getCategoryFromID($Category);
        $changes = false;
        if(strcmp($OldType, $Type) != 0){
            $changes = true;
            $this->logUpdate(self::$table, $id, "Type", $OldType, $Type, $AdminName);
        }
        if (strcmp($OldDescription, $Description) != 0) {
            $changes = true;
            $this->logUpdate(self::$table, $id, "Description", $OldDescription, $Description, $AdminName);
        }
        if (strcmp($OldProvider, $Provider) != 0) {
            $changes = true;
            $this->logUpdate(self::$table, $id, "Description", $OldDescription, $Description, $AdminName);
        }
        if ($OldCategoryID <> $Category) {
            $changes = true;
            $this->logUpdate(self::$table, $id, "Category", $OldCategory, $NewCategory, $AdminName);
        }
        if ($changes){
            $sql = "update subscriptiontype set Type = :Type, Description = :Description, provider = :Provider, Category = :Cat where Type_ID = :ID";
            $q = $pdo->prepare($sql);
            $q->bindParam(':Type',$Type);
            $q->bindParam(':Description',$Description);
            $q->bindParam(':Provider',$Provider);
            $q->bindParam(':Cat',$Category);
            $q->bindParam(':ID',$id);
            $q->execute();
        }
    }
    /**
     * This function will return the Type of a Supsrption Type
     * @param int $uuid
     * @return string
     */
    private function getType($uuid){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type From subscriptiontype where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Description of a Supsrption Type
     * @param int $uuid
     * @return string
     */
    private function getDesctiption($uuid) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Description From subscriptiontype where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Description"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the provider of a Supsrption Type
     * @param int $uuid
     * @return string
     */
    private function getProvider($uuid) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select provider From subscriptiontype where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["provider"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Category of a Supsrption Type
     * @param int $uuid
     * @return string
     */
    private function getCategoryID($uuid) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Category From subscriptiontype st ".
            "where st.Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Category"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Category of a Supsrption Type
     * @param int $uuid
     * @return string
     */
    private function getCategoryFromID($catID) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Category from Category where ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$catID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Category"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
}

