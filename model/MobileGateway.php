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
    }
    /**
     * {@inheritDoc}
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Type";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select IMEI, at.Vendor, at.Type, if(m.active=1,\"Active\",\"Inactive\") Active, IFNULL(i.Name,\"Not in use\") ussage from Mobile m ".
                "Join assettype at on m.mobileType = at.Type_ID ".
                "left join identity i on m.Identity = i.Iden_ID order by " . $order;
        $q = $pdo->prepare ( $sql );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }
        Logger::disconnect ();
    }
    /**
     * {@inheritDoc}
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
    /**
     * {@inheritDoc}
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /**
     * This function will return all Mobile types
     * @return array
     */
    public function ListAllTypes(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Vendor, Type from assetCategory where category = " .
                "(Select ID from category where Category = 'Mobile') and active = 1";
        $q = $pdo->prepare ( $sql );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }
        Logger::disconnect ();
    }
    /**
     * This function will return all SubscriptionTypes
     */
    public function ListAllSubscriptions(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type, Description from subscriptiontype where category = " .
            "(Select ID from category where Category = 'Mobile Subscription') and active = 1";
        $q = $pdo->prepare ( $sql );
        if ($q->execute ()) {
            return $q->fetchAll ( PDO::FETCH_ASSOC );
        }
        Logger::disconnect ();
    }
    /**
     * This function will return the list of all Identities that does not have any mobile assigned
     * @param string $IMEI The AssetTag of the current Device
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
}