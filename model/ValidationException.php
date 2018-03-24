<?php
/**
 * This is the Class for Validation Exception
 * @copyright Hans Colman
 * @author Hans Colman
 */
class ValidationException extends Exception {
    /**
     * The List of Erros
     * @var array
     */
    private $errors = NULL;
    /**
     * Contructor
     * @param array $errors
     * @see Exception
     */
    public function __construct($errors) {
        parent::__construct("Validation error!");
        $this->errors = $errors;
    }
    /**
     * This function will return all errors
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
}

?>
