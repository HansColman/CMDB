<?php
/**
 * This is the Class that will generate the PDF
 * @copyright Hans Colman
 * @author Hans Colman
 */
class PDFGenerator
{
    /**
     * The Devices
     * @var array
     */    
    private $device;
    /**
     * The accounts
     * @var array 
     */
    private $account;
    /**
     * The HTML
     * @var string
     */
    private $html;
    /**
     * The language of the customer
     * @var string
     */
    private $language;
    /**
     * The receiver of the Asset
     * @var string
     */
    private $Reveiver;
    /**
     * The person that will sign
     * @var string
     */
    private $Signee;
    /**
     * The IT employee
     * @var string
     */
    private $ITEmployee;
    /**
     * The UserID of the person
     * @var string
     */
    private $UserID;
    /**
     * Check if this is the first row
     * @var bool
     */
    private $isFirst = TRUE;
    /**
     * @var integer
     */
    private $i = 0;
    /**
     * @var integer
     */
    private $j = 0;
    /**
     * The type of the Asset
     * @var string
     */
    private $type;
    /**
     * The constructor
     */
    public function __construct(){
        $this->device = array();
        $this->account =  array();
        $this->html = "<HTML>";
        $this->html .= "<head>";
        $this->html .= "<link href=\"../css/bootstrap.min.css\" rel=\"stylesheet\">";
        $this->html .= "<script src=\"../js/jquery-3.2.1.js\"> </script>";
        $this->html .= "</head>";
        $this->html .= "<body>";
        $this->html .= "<div class=\"container\">";
    }
    /**
     * This function will set the correct title
     * @param string $type
     */
    public function setTitle($type = NULL){
        if (isset($type)){
            $this->html .= "<H1>Release Form</h1>";
            $this->type = $type;
        }else {
            $this->html .= "<H1>Assign Form</h1>";
        }
    }
    /**
     * This function will set the User info
     * @param string $name
     * @param string $language
     * @param string $UserID
     */
    public function setReceiverInfo($name,$language,$UserID){
        $this->language = $language;
        $this->Reveiver = $name;
        $this->UserID = $UserID;
    }
    /**
     * This function will set the Asset info into the PDF
     * @param string $AssetCategory
     * @param string $AssetType
     * @param string $SerialNumber
     * @param string $AssetTag
     */
    public function setAssetInfo($AssetCategory,$AssetType,$SerialNumber,$AssetTag){
        $this->device = array_merge($this->device, array($this->i =>array("Category" => $AssetCategory, "AssetType" =>$AssetType, "SerialNumber" =>$SerialNumber, "AssetTag"=>$AssetTag)));
        $this->i++;
    }
    /**
     * This function will set the Account Info
     * @param string $UserID
     * @param string $Application
     * @param string $From
     * @param string $Until
     */
    public function setAccountInfo($UserID,$Application,$From,$Until){
        $this->account = array_merge($this->account, array($this->j => array("UserID" => $UserID,"Application" => $Application, "From" =>$From, "Until" => $Until)));
        $this->j++;
    }
    /**
     * This function will set the info on who will sign
     * @param string $Name
     */
    public function setEmployeeSingInfo($Name){
        $this->Signee = $Name;
    }
    /**
     * This function will set the info on who will sign as IT
     * @param string $ItEmployee
     */
    public function setITSignInfo($ItEmployee){
        $this->ITEmployee = $ItEmployee;
    }
    /**
     * This function will create the PDF
     */
    public function createPDf(){
        if (isset($this->type)){
            $filename = $_SERVER['DOCUMENT_ROOT'].'/CMDB/PDF-Files/ReleaseForm_'.$this->UserID."_".date('m-d-Y_hia').'.html';
            switch ($this->language){
                case "NL":
                    $this->html .= "<p>Beste ".$this->Reveiver." Gelieve te teken voor het terug geven van het volgende materiaal:</p>";
                    break;
                case "FR":
                    $this->html .= "<p>Dear ".$this->Reveiver." please sing for the returnment of the following material:</p>";
                    break;
                case "EN":
                    $this->html .= "<p>Dear ".$this->Reveiver." please sing for the returment of the following material:</p>";
                    break;
            }
        }else{
            $filename = $_SERVER['DOCUMENT_ROOT'].'/CMDB/PDF-Files/AssignForm_'.$this->UserID."_".date('m-d-Y_hia').'.html';
            switch ($this->language){
                case "NL":
                    $this->html .= "<p>Beste ".$this->Reveiver." Gelieve te teken voor het ontvangen van het volgende materiaal:</p>";
                    break;
                case "FR":
                    $this->html .= "<p>Dear ".$this->Reveiver." please sing for the recievment of the following material:</p>";
                    break;
                case "EN":
                    $this->html .= "<p>Dear ".$this->Reveiver." please sing for the recievment of the following material:</p>";
                    break;
            }
        }
        if (!empty($this->account)){
            foreach ($this->account as $account){
                if ($this->isFirst){
                    $this->setAccountTableHeader();
                }
                $this->html .="<tr>";
                $this->html .="<td>".$account['UserID']."</td>";
                $this->html .="<td>".$account['Application']."</td>";
                $this->html .="<td>".$account['From']."</td>";
                $this->html .="<td>".$account['Until']."</td>";
                $this->html .="</tr>";
                $this->isFirst = FALSE;
            }
            $this->isFirst = TRUE;
            $this->html .="</tbody>";
            $this->html .="</table>";
        }
        if (!empty($this->device)){    
            foreach ($this->device as $device){
                if ($this->isFirst){
                    $this->setDeviceTableHeader();
                }
                $this->html .="<tr>";
                $this->html .="<td>".$device['Category']."</td>";
                $this->html .="<td>".$device['AssetType']."</td>";
                $this->html .="<td>".$device['AssetTag']."</td>";
                $this->html .="<td>".$device['SerialNumber']."</td>";
                $this->html .="</tr>";
                $this->isFirst = FALSE;
            }
            $this->isFirst = TRUE;
            $this->html .="</tbody>";
            $this->html .="</table>";
        }
        if (!empty($this->Signee) and !empty($this->ITEmployee)){
            
            switch ($this->language){
                case "NL":
                    $this->html .= "<h3>Info van wie er tekent</h3>";
                    break;
                case "FR":
                    $this->html .= "<h3>FR</h3>";
                    break;
                case "EN":
                    $this->html .= "<h3>EN</h3>";
                    break;
            }
            $this->html .="Please sing here: <br>";
            $this->html .= "<table class=\"table table-striped table-bordered\">";
            $this->html .= "<thead>";
            $this->html .= "<tr>";
            $this->html .= "<th>Employee Info</th>";
            $this->html .= "<th>IT Employee Info</th>";
            $this->html .= "</tr>";
            $this->html .= "</thead>";
            $this->html .= "<tbody>";
            $this->html .="<tr>";
            $this->html .="<td>".$this->Signee."</td>";
            $this->html .="<td>".$this->ITEmployee."</td>";
            $this->html .="</tr>";
            $this->html .="<tr>";
            $this->html .="<td><textarea rows=\"4\" cols=\"50\"> </textarea></td>";
            $this->html .="<td><textarea rows=\"4\" cols=\"50\"> </textarea></td>";
            $this->html .="</tr>";
            $this->html .="</tbody>";
            $this->html .="</table>";
        }
        $this->html .="</div>";
        $this->html .="</body>";
        $this->html .="</html>";
        $file = fopen($filename, "a+") or die("Unable to open file!");
        fwrite($file, $this->html);
        fclose($file);     
        //print $this->html;
    }
    /**
     * This function will setThedevice Table header
     */
    private function setDeviceTableHeader(){
        if (isset($this->type)){
            switch ($this->language){
                case "NL":
                    $this->html .= "<h3>Gegevens van het terug gebracht matteriaal</h3>";
                    break;
                case "FR":
                    $this->html .= "<h3>Info of the returned device</h3>";
                    break;
                case "EN":
                    $this->html .= "<h3>Info of the returned device</h3>";
                    break;
            }
        }else{
            switch ($this->language){
                case "NL":
                    $this->html .= "<h3>Gegevens van het ontvangen matteriaal</h3>";
                    break;
                case "FR":
                    $this->html .= "<h3>Info about the received device</h3>";
                    break;
                case "EN":
                    $this->html .= "<h3>Info about the received device</h3>";
                    break;
            }
        }
        $this->html .="<table class=\"table table-striped table-bordered\">";
        $this->html .= "<thead>";
        $this->html .= "<tr>";
        $this->html .= "<th>Category</th>";
        $this->html .= "<th>Asset Type</th>";
        $this->html .= "<th>AssetTag</th>";
        $this->html .= "<th>SerialNumber</th>";
        $this->html .= "</tr>";
        $this->html .= "</thead>";
        $this->html .= "<tbody>";
    }
    private function setAccountTableHeader(){
        switch ($this->language){
            case "NL":
                $this->html .= "<h3>Gegevens van de account</h3>";
                break;
            case "FR":
                $this->html .= "<h3>Info of the account</h3>";
                break;
            case "EN":
                $this->html .= "<h3>Info of the account</h3>";
                break;
        }
        $this->html .="<table class=\"table table-striped table-bordered\">";
        $this->html .= "<thead>";
        $this->html .= "<tr>";
        $this->html .= "<th>UserID</th>";
        $this->html .= "<th>Application</th>";
        $this->html .= "<th>From</th>";
        $this->html .= "<th>Until</th>";
        $this->html .= "</tr>";
        $this->html .= "</thead>";
        $this->html .= "<tbody>";
    }
}

