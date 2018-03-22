<?php
/**
 * This is the ValidationExecption Class
 * @copyright Hans Colman
 * @author Hans Colman
 */
class ValidationException extends \Exception {
    /**
     * The list of erros
     * @var array
     */
    private $errors = NULL;
    /**
     * The contructor
     * @param array $errors
     */
    public function __construct($errors) {
        parent::__construct("Validation error!");
        $this->errors = $errors;
    }
    /**
     * This function will return the errors
     * @return array
     */    
    public function getErrors() {
        return $this->errors;
    }
    
}