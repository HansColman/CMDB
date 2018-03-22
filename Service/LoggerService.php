<?php
require_once 'model/LoggerGateway.php';
/**
 * This is the Service Class for Logs
 * @copyright Hans Colman
 * @author Hans Colman
 */
class LoggerService {
    /**
     * The LoggerGateway
     * @var LoggerGateway
     */
    private $loggerGatteway = NULL;
    
    public function __construct() {
        $this->loggerGatteway = new LoggerGateway();
    }
    /**
     * this function will return all the log of a given object
     * @param string $table
     * @param mixed $uuid
     * @throws PDOException
     * @return array
     */
    public function listAllLogs($table, $uuid) {
        try {
            return $this->loggerGatteway->getLog($table, $uuid);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
}
