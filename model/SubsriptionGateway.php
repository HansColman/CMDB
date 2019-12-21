<?php
require_once 'Logger.php';

class SubsriptionGateway extends Logger
{
    /**
     * @var string This variable will keep the table for the logging
     */
    private static $table = 'subscription';
    public function __construct()
    {
        
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectAll()
     */
    public function selectAll($order)
    {
        if (empty($order)) {
            $order = "Category";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Sub_ID, PhoneNumber, st.Type, c.Category, IFNULL(i.Name,\"Not in use\") ussage, m.IMEI, ".
            "if(s.active=1,\"Active\",\"Inactive\") as Active from subscription s ".
            "join subscriptiontype st on s.SubscriptionType = st.Type_ID ".
            "join category c on s.category = c.ID ".
            "left join Identity i on s.Identity = i.Iden_ID ".
            "left join Mobile m on s.IMEI = m.IMEI ".
            "order by ".$order;
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
    public function activate($UUID, $AdminName)
    {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update subscription set Deactivate_reason = NULL, Active = 1 where Sub_ID=:uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->execute();
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
     */
    public function selectById($id)
    {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Sub_ID, PhoneNumber, st.Type, c.Category, IFNULL(i.Name,\"Not in use\") ussage, m.IMEI, ".
            "if(s.active=1,\"Active\",\"Inactive\") as Active from subscription s ".
            "join subscriptiontype st on s.SubscriptionType = st.Type_ID ".
            "join category c on s.category = c.ID ".
            "left join Identity i on s.Identity = i.Iden_ID ".
            "left join Mobile m on s.IMEI = m.IMEI where Sub_ID= :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$id);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC); 
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch()
     */
    public function selectBySearch($search)
    {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $searhterm = "%$search%";
        $sql = "Select Sub_ID, PhoneNumber, st.Type, c.Category, IFNULL(i.Name,\"Not in use\") ussage, m.IMEI, ".
            "if(s.active=1,\"Active\",\"Inactive\") as Active from subscription s ".
            "join subscriptiontype st on s.SubscriptionType = st.Type_ID ".
            "join category c on s.category = c.ID ".
            "left join Identity i on s.Identity = i.Iden_ID ".
            "left join Mobile m on s.IMEI = m.IMEI where PhoneNumber like :search or st.Type like :search or m.IMEI like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC); 
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::delete()
     */
    public function delete($UUID, $reason, $AdminName)
    {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update subscription set Deactivate_reason = :reason, Active = 1 where Sub_ID=:uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':reason',$reason);
        $q->bindParam(':uuid',$UUID);
        $q->execute();
        Logger::disconnect();
    }
    /**
     * This function will create a new Subscription
     * @param string $PhoneNumber
     * @param int $Type
     * @param string $AdminName
     */
    public function create($PhoneNumber, $Type,$AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into subscription (PhoneNumber,SubscriptionType) values(:phonenumber,:type)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':phonenumber',$PhoneNumber);
        $q->bindParam(':type',$Type);
        if($q->execute()){
            $Value = "Subscrption with phonenumber ".$PhoneNumber." and type ".$this->getSubscriptionType($Type);
            $UUIDQ = "Select Sup_ID from subscription order by Sub_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            Logger::logCreate(self::$table, $row["Sup_ID"], $Value, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * This function will update an given subscription
     * @param int $uuid
     * @param string $PhoneNumber
     * @param int $Type
     * @param string $AdminName
     */
    public function update($uuid,$PhoneNumber,$Type,$AdminName){
        $OldPhoneNumber = $this->getPhoneNumber($uuid);
        $OldSubID = $this->getSubriptionID($uuid);
        $OldSubscription = $this->getSubscriptionType($OldSubID);
        $updated = False;
        if(strcmp($PhoneNumber, $OldPhoneNumber) <> 0){
            $updated = TRUE;
            $this->logUpdate(self::$table, $uuid, "phonennumber", $OldPhoneNumber, $PhoneNumber, $AdminName);
        }
        if($OldSubID != $Type){
            $updated = TRUE;
            $this->logUpdate(self::$table, $uuid, "Type", $OldSubscription, $this->getSubscriptionType($Type), $AdminName);
        }
        if($updated){
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "update subscription set PhoneNumber = :phonenumber, SubscriptionType = :type where Sub_Id=:uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':phonenumber',$PhoneNumber);
            $q->bindParam(':type',$Type);
            $q->bindParam(':uuid',$uuid);
            $q->execute();
            Logger::disconnect();
        }
    }
    /**
     * This function will return all active Subscription types
     */
    public function getAllSubscriptionTypes(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description from identitytype it where Active = 1";
        $q = $pdo->prepare($sql);
        return $q->fetchAll(PDO::FETCH_ASSOC); 
    }
    /**
     * This function will get the subscription type by its ID
     * @param int $SubTypeId
     */
    private function getSubscriptionType($SubTypeId){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type From subscriptiontype where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$SubTypeId);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will get the PhoneNumber type
     * @param int $uuid
     */
    private function getPhoneNumber($uuid){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select PhoneNumber From subscription where Sub_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["PhoneNumber"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will get the SubID type
     * @param int $uuid
     */
    private function getSubriptionID($uuid){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select SubscriptionType From subscription where Sub_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$uuid);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["SubscriptionType"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
}

