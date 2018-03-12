<?php
class PDFGenerator
{
    private $device;
    private $html;
    private $language;
    private $Reveiver;
    private $Signee;
    private $ITEmployee;
    private $UserID;
    private $isFirst = TRUE;
    private $i = 0;
    private $type;
    
    public function __construct(){
        $this->device = array();
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
     * @param unknown $type
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
        $this->i ++;
    }
    /**
     * 
     * @param unknown $Name
     */
    public function setEmployeeSingInfo($Name){
        $this->Signee = $Name;
    }
    /**
     * 
     * @param unknown $ItEmployee
     */
    public function setITSignInfo($ItEmployee){
        $this->ITEmployee = $ItEmployee;
    }
    
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
     * 
     */
    private function setDeviceTableHeader(){
        switch ($this->language){
            case "NL":
                $this->html .= "<h3>Gegevens van het ontvangen matteriaal</h3>";
                break;
            case "FR":
                $this->html .= "<h3>Device info</h3>";
                break;
            case "EN":
                $this->html .= "<h3>Device info</h3>";
                break;
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
}

