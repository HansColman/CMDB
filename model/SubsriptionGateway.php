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
            "if(s.active=1,\"Active\",\"Inactive\") as Active, c.id cat_id,c.Category from subscription s ".
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
        if($q->execute()){
            $Type = $this->getSubriptionID($UUID);
            $Value = "Subscrption with phonenumber ".$this->getPhoneNumber($UUID)." and type ".$this->getSubscriptionType($Type);
            $this->logActivation(self::$table, $UUID, $Value, $AdminName);
        }
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
            "if(s.active=1,\"Active\",\"Inactive\") as Active, c.ID cat_id,c.Category from subscription s ".
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
            "if(s.active=1,\"Active\",\"Inactive\") as Active, c.Category from subscription s ".
            "join subscriptiontype st on s.SubscriptionType = st.Type_ID ".
            "join category c on s.category = c.ID ".
            "left join Identity i on s.Identity = i.Iden_ID ".
            "left join Mobile m on s.IMEI = m.IMEI where PhoneNumber like :search or st.Type like :search or m.IMEI like :search or c.Category like :search";
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
        $sql = "Update subscription set Deactivate_reason = :reason, Active = 0 where Sub_ID=:uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':reason',$reason);
        $q->bindParam(':uuid',$UUID);
        if($q->execute()){
            $Type = $this->getSubriptionID($UUID);
            $Value = "Subscrption with phonenumber ".$this->getPhoneNumber($UUID)." and type ".$this->getSubscriptionType($Type);
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
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
        $Category = $this->getSubscriptionCategory($Type);
        $sql = "insert into subscription (PhoneNumber,SubscriptionType,Category) values(:phonenumber,:type,:category)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':phonenumber',$PhoneNumber);
        $q->bindParam(':type',$Type);
        $q->bindParam(':category',$Category);
        if($q->execute()){
            $Value = "Subscrption with phonenumber ".$PhoneNumber." and type ".$this->getSubscriptionType($Type);
            $UUIDQ = "Select Sub_ID from subscription order by Sub_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            Logger::logCreate(self::$table, $row["Sub_ID"], $Value, $AdminName);
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
        $sql = "Select Type_ID, Type, Description, c.Category from subscriptiontype st join category c on st.Category = c.ID where st.Active = 1";
        $q = $pdo->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
        Logger::disconnect();
    }
    /**
     * This function will return all assined identities
     * @param int $id
     * @return array
     */
    public function getAssignedIdenity($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Iden_ID, Name, UserID from Identity i "
            ."join subscription s on s.Identity = i.Iden_ID "
            ."where s.Sub_Id = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        $q->execute();
        return $q->fetch(PDO::FETCH_ASSOC);
        Logger::disconnect();
    }
    /**
     * This function will return all assined mobiles
     * @param int $id
     * @return array
     */
    public function getAssignedMobile($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select m.IMEI, concat(at.Vendor, \" \",at.Type) Type from mobile m "
            ."join assettype at on m.MobileType = at.Type_ID "
            ."join subscription s on s.IMEI = m.IMEI "
            ."where s.Sub_Id = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        $q->execute();
        return $q->fetch(PDO::FETCH_ASSOC);
        Logger::disconnect();
    }
    /**
     * This function will return the list of all Identities that does not have any subscription assigned
     * @param int $uuid
     * @return array
     */
    public function listAllIdentities($uuid){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "Select Iden_ID, Name, UserID from Identity ".
            "where Iden_ID not in (select IFNULL(identity,0) from subscription a WHERE a.Sub_ID = :id union select Iden_ID from identity where Iden_ID = 1)";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':id', $uuid );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }else{
            return array();
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the list of all Mobiles that does not have any subscription assigned
     * @param int $uuid
     * @return array
     */
    public function listAllMobiles($uuid){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "Select m.IMEI, concat(at.Vendor, \" \",at.Type ) Type from mobile m "
            .   "join assettype at on m.MobileType = at.Type_ID "
            .   "where IMEI not in (select IFNULL(IMEI,0) from subscription a WHERE a.Sub_ID = :id)";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':id', $uuid );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }else{
            return array();
        }
        Logger::disconnect ();
    }
    
    public function assign($id,$cat,$Identity,$IMEI,$AdminName) {
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $Type = $this->getSubriptionID($id);
        $Value = "Subscrption with phonenumber ".$this->getPhoneNumber($id)." and type ".$this->getSubscriptionType($Type);
        if ($cat == 3) {
            $sql = "update subscription set IMEI = :IMEI where Sub_ID = :id";
            $q = $pdo->prepare ( $sql );
            $q->bindParam ( ':id', $id );
            $q->bindParam ( ':IMEI', $IMEI );
            if ($q->execute ()) {
                $IdentityInfo = $this->get_MobileInfo($IMEI);
                $this->logAssignDevice2Identity(self::$table, $id, $Value, $IdentityInfo, $AdminName);
                $this->logAssignIdentity2Device("identity", $Identity, $IdentityInfo, $Value, $AdminName);
            }
        }
        if ($cat == 4){
            $sql = "update subscription set Identity = :IMEI where Sub_ID = :id";
            $q = $pdo->prepare ( $sql );
            $q->bindParam ( ':id', $id );
            $q->bindParam ( ':IMEI', $Identity );
            if ($q->execute ()) {
                $IdentityInfo = $this->get_IdentityInfo($Identity);
                $this->logAssignDevice2Identity(self::$table, $id, $Value, $IdentityInfo, $AdminName);
                $this->logAssignIdentity2Device("identity", $Identity, $IdentityInfo, $Value, $AdminName);
            }
        }
        Logger::disconnect ();
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
     * This function will return the category
     * @param int $SubTypeId
     * @return mixed|string
     */
    private function getSubscriptionCategory($SubTypeId){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Category From subscriptiontype where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$SubTypeId);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Category"];
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
    /**
     * This will retun the mobile info
     * @param int $IMEI
     * @return string
     */
    private function get_MobileInfo($IMEI) {
        require_once 'MobileGateway.php';
        $mobile = new MobileGateway();
        $mobilerow = $mobile->selectById($IMEI);
        foreach ($mobilerow as $row){
            return "Mobile with ".$IMEI." and type ".$row['Type'];
        }
    }
    /**
     * This function will return the IdenitityInfo
     * @param int $IdenId
     * @return string
     */
    private function get_IdentityInfo($IdenId) {
        require_once 'IdentityGateway.php';
        $idenity = new IdentityGateway();
        return "Identity with ".$idenity->getFirstName($IdenId)." ".$idenity->getLastName($IdenId)." and UserID ".$idenity->getUserID($IdenId);
    }
}

