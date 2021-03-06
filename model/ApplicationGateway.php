<?php
require_once 'Logger.php';
/**
 * This is the Db Class for Application
 * @copyright Hans Colman
 * @author Hans Colman
 */
class ApplicationGateway extends Logger{
	/**
	 * This variable will keep the table for the logging
	 * @var string
	 */
    private static $table = 'application';
    /**
     * {@inheritDoc}
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update Application set Active = 1, Deactivate_reason = NULL where App_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $Value = "Application with ". $this->getName($UUID);
            $this->logActivation(self::$table, $UUID, $Value, $AdminName);
        }
    }
    /**
     * {@inheritDoc}
     */
    public function delete($UUID, $reason, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update Application set Active = 0, Deactivate_reason = :reason where App_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':reason',$reason);
        if ($q->execute()){
            $Value = "Application with ". $this->getName($UUID);
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
    }
    /**
     * {@inheritDoc}
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Name";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select App_ID, Name, if(active=1,\"Active\",\"Inactive\") Active "
                . "from application "
                . "order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * Return all active AccountTypes
     * @return array
     */
    public function getAllApplications() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select App_ID, Name from Application where Active = 1";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            //print_r($q->fetchAll(PDO::FETCH_ASSOC));
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return all the info of an given application
     * @param integer $id The ID of the Application
     * @return array
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Name, if(active=1,\"Active\",\"Inactive\") Active from Application where App_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Name, if(active=1,\"Active\",\"Inactive\") Active from Application where name like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            //print_r($q->fetchAll(PDO::FETCH_ASSOC));
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will create a new application
     * @param string $Name The name of the application
     * @param string $AdminName The name of the administrator that creates the Application
     */
    public function create($Name,$AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into Application (Name) Values (:name)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':name',$Name);
        if ($q->execute()){
            $Value = "Application with ". $Name;
            $UUIDQ = "Select App_ID from Application order by App_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            $this->logCreate(self::$table, $row["App_ID"], $Value, $AdminName);
        }
    }
    /**
     * This function will update the application.
     * @param integer $UUID The ID of the Application
     * @param string $Name The name of the application
     * @param string $AdminName The name of the administrator that creates the Application
     */
    public function update($UUID, $Name,$AdminName) {
        $OldName = $this->getName($UUID);
        if (strcmp($OldName, $Name) != 0){
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Account set Name = :name where App_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':name',$Name);
            if ($q->execute()){
            	$this->logUpdate(self::$table, $UUID, "Name", $OldName, $Name, $AdminName);
            }
            Logger::disconnect();
        }
    }
    /**
     * This will return the list of accounts for a given Application
     * @param integer $UUID The ID of the Application
     * @return array
     */
    public function listAllAccounts($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "select a.`Acc_ID`,a.`UserID`,app.`Name` Application "
                . "from account a "
                . "join application app on a.`Application` = app.`App_ID` "
                . "where app.App_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            //print_r($q->fetchAll(PDO::FETCH_ASSOC));
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will check if there is an application that exist with the given name and return true if so
     * @param string $Name The name of the Application
     * @param int $UUID
     * @return boolean
     */
    public function alreadyExist($Name,$UUID = 0){
    	$pdo = Logger::connect();
    	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	if ($UUID == 0){
	    	$sql =  "Select Name from Application where Name = :name" ;
	    	$q = $pdo->prepare($sql);
	    	$q->bindParam(':name',$Name);
	    	$q->execute();
	    	if ($q->rowCount()>0){
	    		return TRUE;
	    	}  else {
	    		return FALSE;
	    	}
    	}else {
    		$OldName = $this->getName($UUID);
    		if (strcmp($OldName, $Name) != 0){
    			$sql =  "Select Name from Application where Name = :name" ;
    			$q = $pdo->prepare($sql);
    			$q->bindParam(':name',$Name);
    			$q->execute();
    			if ($q->rowCount()>0){
    				return TRUE;
    			}  else {
    				return FALSE;
    			}
    		}else{
    			return FALSE;
    		}
    	}
    }
    /**
     * This function will return the name of a given application
     * @param integer $UUID The ID of the Application
     * @return string
     */
    private function getName($UUID) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Name from Application where App_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Name"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
