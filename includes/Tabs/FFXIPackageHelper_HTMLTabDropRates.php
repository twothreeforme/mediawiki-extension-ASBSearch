<?php


class FFXIPackageHelper_HTMLTabDropRates {

        private $query_limit;
        private $mobName;
        private $itemName;
        private $zoneName;
        private $showBCNMdrops;
        private $excludeNMs;
        private $levelRangeMIN;
        private $levelRangeMAX;
        private $thRatesCheck;
        private $includeSteal;
        private $includeFished;

    public function __construct( $query = null ) {
        if ( gettype($query) == 'array'){
            $this->query_limit = $query[0];
            $this->mobName = $query[1];
            $this->itemName = $query[2];
            $this->zoneName = $query[3];
            $this->showBCNMdrops = $query[4];
            $this->excludeNMs = $query[5];
            $this->levelRangeMIN = $query[6];
            $this->levelRangeMAX = $query[7];
            $this->thRatesCheck = $query[8];
            $this->includeSteal = $query[9];
            $this->includeFished = $query[10];
        }
    }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_droprates_searchForm\">" .
                    "<table><tbody>
                    <tr><td>
                        <table><tbody>
                        <tr>
                            <td>Mob/BCNM Name<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" value=\"$this->mobName\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Item Name<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"itemNameSearch\" value=\"$this->itemName\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Zone<br>" . FFXIPackageHelper_HTMLOptions::zonesDropDown() . "<br><br><button id=\"FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                            </tr>
                        </tbody></table>
                        </td>
                        <td style=\"vertical-align:top;\">".$this->showShareButton("FFXIPackageHelper_dynamiccontent_shareDropRateQuery") . 
                            "<br><br>Level: Min->". FFXIPackageHelper_HTMLTableHelper::selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMIN", 85) .
                            " Max->". FFXIPackageHelper_HTMLTableHelper::selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMAX", 85) .
                            "<br><br>" . $this->selectionOptions() . 
                        "</td>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_droprates_queryresult\">". $this->postQueryResults() ."</div>
                </div>";
        return $html;
    }

    private function zoneNamelist(){
        $db = new DatabaseQueryWrapper();
        $zonelist = $db->getZoneList();

        foreach ($zonelist as $row) {
			$temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) || ExclusionsHelper::zoneIsTown($temp) ) { continue; }
			$result[$temp]=$row->name;
			//print_r($result[$temp] .", " . $row->name);
		}
		$result[' ** Search All Zones ** '] = "searchallzones";
		ksort($result);
		return $result ;
    }

    // private function zonesDropDown(){
    //     // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select>
    //     $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectZoneName\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
    //     $zoneNamesList = $this->zoneNameList();
    //     foreach ($zoneNamesList as $key => $value) {
    //        // print_r($key . $value);
    //         if ( $this->zoneName != "" && $this->zoneName == $key ) $html .= "<option value=\"" . $value . "\" selected=\"selected\">" . $key . "</option>";
    //         else $html .= "<option value=\"" . $value . "\">" . $key . "</option>";
    //     }

    //     $html .= "</select>";
    //     return $html;
    // }

    private function selectionOptions(){
        $html = "";

        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxShowTH\" type=\"checkbox\"";
        if ( $this->thRatesCheck == 1 ) $html .= "checked=\"checked\"";
        $html .= "> Show TH Rates</input></label><br>";

        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxBCNM\" type=\"checkbox\"";
        if ( $this->showBCNMdrops == 1 ) $html .= "checked=\"checked\"";
        $html .= "> Include BCNMs</input></label><br>";

        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxExcludeNM\" type=\"checkbox\"";
        if ( $this->excludeNMs == 1 ) $html .= "checked=\"checked\"";
        $html .= "> Exclude NMs</input></label><br>";

        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxIncludeSteal\" type=\"checkbox\"";
        if ( $this->includeSteal == 1 ) $html .= "checked=\"checked\"";
        $html .= "> Include 'Steal'</input></label><br>";

        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxIncludeFished\" type=\"checkbox\"";
        if ( $this->includeFished == 1 ) $html .= "checked=\"checked\"";
        $html .= "> Include 'Fished'</input></label><br>";
        return $html;
    }

    private function showShareButton($id){
        return FFXIPackageHelper_HTMLTableHelper::shareButton($id);
    }

    private function postQueryResults(){
        if ( $this->mobName != "" || $this->itemName != "" || $this->zoneName != "") {

            $html = FFXIPackageHelper_QueryController::queryDropRates([
                $this->query_limit,
                $this->mobName,
                $this->itemName,
                $this->zoneName,
                $this->showBCNMdrops,
                $this->excludeNMs,
                $this->levelRangeMIN,
                $this->levelRangeMAX,
                $this->thRatesCheck,
                $this->includeSteal,
                $this->includeFished
                ]);
            return ParserHelper::wikiParse($html);
        }
        return "";
    }
}

?>