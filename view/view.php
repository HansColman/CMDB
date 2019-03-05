<?php
/**
 * This is the view class
 * @author Hans Colman
 *
 */
class view
{
    /**
     * 
     */
    private $dateFormat = "d-m-y";
    
    public function __construct()
    {
        
    }
    /**
     * 
     * @param string $title
     * @param string $message
     */
    public function print_error($title,$message){
        print "<h1>".htmlentities($title)."</h1>";
        print "<p>".htmlentities($message)."</p>";
    }
    
    public function setLogDateFormat($dateFormat){
        $this->dateFormat =$dateFormat;    
    }
    
    public function getLogDateFormat() {
        return $this->dateFormat;
    }
}

