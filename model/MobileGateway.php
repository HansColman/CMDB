<?php
require_once 'Logger.php';
/**
 * This Class is the db Connection for Mobile
 * @author Hans Colman
 * @copyright Hans Colman
 */
class MobileGateway extends Logger{
    /**
     * This variable will keep the table for the logging
     * @var string
     */
    private static $table = 'mobile';
    /**
     * {@inheritDoc}
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /**
     * {@inheritDoc}
     */
    public function delete($UUID, $reason, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update Mobile set active = 0, Deactivate_reason = :reason where IMEI = :imei";
        $q = $pdo->prepare ( $sql );
        $q->bindParam(':reason',$reason);
        $q->bindParam('imei', $UUID);
        if($q->execute()){
            $Value =  "Mobile with IMEI" .$UUID;
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectAll()
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Type";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select IMEI, CONCAT(at.Vendor,\" \",at.Type) Type, if(m.active=1,\"Active\",\"Inactive\") Active, IFNULL(i.Name,\"Not in use\") ussage from Mobile m ".
                "Join assettype at on m.mobileType = at.Type_ID ".
                "left join identity i on m.Identity = i.Iden_ID order by " . $order;
        $q = $pdo->prepare ( $sql );
        if ($q->execute ()) {
            return $q->fetchAll (PDO::FETCH_ASSOC);
        }
        Logger::disconnect ();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch()
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select IMEI, CONCAT(at.Vendor,\" \",at.Type) Type, if(m.active=1,\"Active\",\"Inactive\") Active, IFNULL(i.Name,\"Not in use\") ussage from Mobile m ".
            "Join assettype at on m.mobileType = at.Type_ID ".
            "left join identity i on m.Identity = i.Iden_ID where at.Vendor like :search or at.Type like :search or IMEI like :search or i.name like :search";
        $q = $pdo->prepare ( $sql );
        $q->bindParam(':search',$searhterm);
        if ($q->execute ()) {
            return $q->fetchAll (PDO::FETCH_ASSOC);
        }
        Logger::disconnect ();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $UUID = intval($id);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT IMEI, CONCAT(at.Vendor,\" \",at.Type) Type, at.Type_ID, if(m.active=1,\"Active\",\"Inactive\") Active, IFNULL(i.Name,\"Not in use\") ussage, c.Category, IMEI as SerialNumber, IMEI as AssetTag ". 
            "FROM Mobile m ".
            "INNER JOIN assettype at on m.mobileType = at.Type_ID ".
            "INNER JOIN category c on at.category = c.ID ".
            "LEFT OUTER JOIN identity i on m.Identity = i.Iden_ID ".
            "WHERE IMEI = ?";
        $q = $pdo->prepare($sql);
        $q->bindValue(1, $UUID, PDO::PARAM_INT);
        if ($q->execute()) {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect ();
    }
    /**
     * This function will return all Mobile types
     * @return array
     */
    public function ListAllTypes(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Vendor, Type from assettype where category = " .
                "(Select ID from category where Category = 'Mobile') and active = 1";
        $q = $pdo->prepare ( $sql );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }
        Logger::disconnect ();
    }
    /**
     * This function will return all not used Subscriptions
     * @return array
     */
    public function ListAllSubscriptions(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT s.Sub_ID, s.PhoneNumber, st.Type, st.Provider "
                ."from subscription S join SubscriptionType st on s.Sub_ID = st.Type_ID and s.Category = 3 " 
                ."WHERE s.Active = 1 and IMEI is null";
        $q = $pdo->prepare ($sql);
        if ($q->execute()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }
        Logger::disconnect ();
    }
    /**
     * This function will assign the subscription to the mobile
     * @param int $IMEI
     * @param int $SubID
     * @param string $AdminName
     */
    public function assingSubscription($IMEI, $SubID, $AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "update Subscription set IMEI = :IMEI where Sub_ID= :SUBIE";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':IMEI', $IMEI );
        $q->bindParam ( ':SUBIE', $SubID );
        if ($q->execute()) {
            $MobileInfo = "Mobile with IMEI ".$IMEI;
            require_once 'SubsriptionGateway.php';
            $sub = new SubsriptionGateway();
            $Type = $sub->getSubriptionID($SubID);
            $subinfo = "Subscrption with phonenumber ".$sub->getPhoneNumber($SubID)." and type ".$sub->getSubscriptionType($Type);
            $this->logAssignDevice2Identity("subscription", $IMEI, $subinfo, $MobileInfo, $AdminName);
            $this->logAssignIdentity2Device(self::$table, $SubID, $MobileInfo, $subinfo, $AdminName);
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the list of all Identities that does not have any mobile assigned
     * @param int $IMEI The Serial of the mobile
     * @return array
     */
    public function listAllIdentities($IMEI){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "Select Iden_ID, Name, UserID from Identity ".
            "where Iden_ID not in (select identity from mobile a WHERE a.IMEI = :assetTag union select Iden_ID from identity where Iden_ID = 1)";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':assetTag', $IMEI );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }else{
            return array();
        }
        Logger::disconnect ();
    }
    /**
     * This function will check if an Item with arleady exist in the Db
     * @param int $IMEI The Serial of the mobile
     * @param int $type The UUID of the AssetTpe
     * @return boolean
     */
    public function CheckDoubleEntry($IMEI, $type){
        $result = FALSE;
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select IMEI, MobileType "
            . "from Mobile "
            . "where IMEI = :serial and MobileType = :type";
        $q = $pdo->prepare($sql);
        $q->bindParam(':serial',$IMEI);
        $q->bindParam(':type',$type);
        $q->execute();
        if ($q->rowCount()>0){
            $result = TRUE;
        }
        Logger::disconnect();
        return $result;
    }
    /**
     * This function will create a new mobile
     * @param int $IMEI The Serial of the mobile
     * @param int $type The UUID of the AssetTpe
     * @param string $AdminName
     */
    public function add($IMEI, $type, $AdminName) {
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "Insert into mobile (IMEI, MobileType) values(:imei,:type)";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':imei', $IMEI );
        $q->bindParam ( ':type', $type );
        if ($q->execute ()) {
            $MobileInfo =  "Mobile with IMEI: ".$IMEI." and type ".$this->getMobileType($IMEI);
            self::logCreate(self::$table, $IMEI, $MobileInfo, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * This function will update the type of a given Mobile
     * @param int $IMEI
     * @param int $type
     * @param string $AdminName
     */
    public function edit($IMEI, $type, $AdminName){
        $OldType = $this->getMobileType($IMEI);
        $NewType = $this->getAssetType($type);
        if($type <> $this->getMobileTypeID($IMEI)){
            $pdo = Logger::connect ();
            $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $sql = "Update MobileType = :type where IMEI = :imei";
            $q = $pdo->prepare ( $sql );
            $q->bindParam ( ':imei', $IMEI );
            $q->bindParam ( ':type', $type );
            if ($q->execute ()) {
                $this->logUpdate(self::$table, $IMEI, "Type", $OldType, $NewType, $AdminName);
            }
            Logger::disconnect();
        }
    }
    /**
     * This function will return all assigned Identities
     * @param int $UUID
     * @return array
     */
    public function getAssignedIdenty($UUID){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "select i.Iden_ID,i.Name, i.UserID, i.language "
            ."from identity i "
            ."join Mobile a on a.Identity = i.Iden_ID "
            ."where a.IMEI = :assettag";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':assettag', $UUID );
        if ($q->execute()) {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect ();
    }
    /**
     * This function will assing an Identity to an Mobile
     * @param int $IMEI
     * @param int $Identity
     * @param string $AdminName
     */
    public function assingIdentity($IMEI,$Identity,$AdminName){
        require_once 'IdentityGateway.php';
        $Iden = new IdentityGateway();
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "update mobile set Identity = :identity where IMEI= :IMEI";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':IMEI', $IMEI );
        $q->bindParam ( ':identity', $Identity );
        if ($q->execute()) {
            $MobileInfo = "Mobile with IMEI ".$IMEI;
            $IdenInfo = "Identity with ".$Iden->getFirstName($Identity)." ".$Iden->getLastName($Identity);
            $this->logAssignDevice2Identity(self::$table, $IMEI, $MobileInfo, $IdenInfo, $AdminName);
            $this->logAssignIdentity2Device("identity",$Identity,$IdenInfo,$MobileInfo,$AdminName);
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the subsription
     * @param int $UUID
     * @return array
     */
    public function getSubsriptions($UUID){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "select i.PhoneNumber, st.Type, st.Description, st.Provider, i.Active "
            ."from Subscription i "
            ."join Mobile a on a.IMEI = i.IMEI "
            ."join SubscriptionType st on i.SubscriptionType = st.Type_ID "
            ."where a.IMEI = :assettag";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':assettag', $UUID );
        if ($q->execute()) {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect ();
    }
    /**
     * This function will release the Identity from the Mobile
     * @param int $IMEI The Serial of the mobile
     * @param int $IdenID The ID of the Identity
     * @param string $AdminName 
     */
    public function releaseIdenity($IMEI,$IdenID,$AdminName){
        require_once 'IdentityGateway.php';
        $Iden = new IdentityGateway();
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "update mobile set Identity = 1 where IMEI= :IMEI";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':IMEI', $IMEI );
        if ($q->execute()) {
            $MobileInfo = "Mobile with IMEI ".$IMEI;
            $IdenInfo = "Identity with ".$Iden->getFirstName($IdenID)." ".$Iden->getLastName($IdenID);
            $this->logRelaseDeviceFromIdentity(self::$table, $IMEI, $IdenInfo, $MobileInfo, $AdminName);
            $this->logRelaseIdentityFromDevice("identity", $IdenID, $MobileInfo, $IdenInfo, $AdminName);
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the AssetType info of an given Mobile
     * @param int $UUID The Serial of the mobile
     * @return string
     */
    private function getMobileType($UUID) {
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "select CONCAT(at.Vendor,\" \",at.Type) Type from mobile m " 
                ."join assettype at on m.MobileType = at.Type_ID "
                ."where m.IMEI = :imei";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':imei', $UUID );
        if ($q->execute ()) {
            $row = $q->fetch ( PDO::FETCH_ASSOC );
            return $row["Type"];
        } else {
            return "";
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the TypeID from a given Mobile
     * @param int $UUID
     * @return int
     */
    private function getMobileTypeID($UUID){
        $pdo = Logger::connect ();
        $pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $sql = "select m.MobileType from mobile m "
                ."where m.IMEI = :imei";
        $q = $pdo->prepare ( $sql );
        $q->bindParam ( ':imei', $UUID );
        if ($q->execute ()) {
            $row = $q->fetch ( PDO::FETCH_ASSOC );
            return $row["Type"];
        } else {
            return 0;
        }
        Logger::disconnect ();
    }
}