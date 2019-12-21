<?php
require_once 'view.php';

class SubscriptionView extends View
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 
     * @param bool $AddAccess
     * @param array $rows
     * @param bool $UpdateAccess
     * @param bool $DeleteAccess
     * @param bool $ActiveAccess
     * @param bool $AssignAccess
     * @param bool $InfoAccess
     */
    public function print_listAll($AddAccess,$rows,$UpdateAccess,$DeleteAccess,$ActiveAccess,$AssignAccess,$InfoAccess){
        echo "<h2>subscriptions</h2>";
        echo "<div class=\"row\">";
        $Url = "Supbibtion.php?op=new";
        $this->print_addOnTop($AddAccess, $Url);
        $this->SearchForm("Supbibtion.php?op=search");
        echo "</div>";
        if (count($rows)>0){
            
        }else {
            echo "<div class=\"alert alert-danger\">No rows found, please add a new record</div>";
        }
    }
    
    public function print_searched($AddAccess, $rows, $UpdateAccess, $DeleteAccess, $ActiveAccess, $AssignAccess, $InfoAccess, $search){
        
    }
}

