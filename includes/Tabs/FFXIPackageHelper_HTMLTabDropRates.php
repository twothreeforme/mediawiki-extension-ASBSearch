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

    public function __construct(array $query) {
        $this->query_limit = $query[0];
        $this->mobName = $query[1];
        $this->itemName = $query[2];
        $this->zoneName = $query[3];
        $this->showBCNMdrops = $query[4];
        $this->excludeNMs = $query[5];
        $this->levelRangeMIN = $query[6];
        $this->levelRangeMAX = $query[7];
        $this->thRatesCheck = $query[8];
    }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_droprates_searchForm\">" .
                    "<table><tbody><tr><td>
                        <table><tbody>
                        <tr>
                            <td>Mob/BCNM Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Item Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"itemNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            
                            <td>Zone<br>" . $this->zonesDropDown() . "<br><br><button id=\"FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button>&#9;<button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_shareDropRateQuery\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\" >Share</button></td>
                            </tr>
                        </tbody></table>
                    </td>
                        <td>Level: Min->". $this->selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMIN") ." Max->". $this->selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMAX") ."<br>" . $this->selectionOptions() . "</td>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_droprates_queryresult\"></div>
                </div>";
        return $html;
    }

    private function zoneNamelist(){
        $db = new DBConnection();
        $zonelist = $db->getZoneList();

        foreach ($zonelist as $row) {
			$temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) ) { continue; }
			$result[$temp]=$row->name;
			//print_r($result[$temp] .", " . $row->name);
		}
		$result[' ** Search All Zones ** '] = "searchallzones";
		ksort($result);
		return $result ;
    }

    private function zonesDropDown(){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select>
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectZoneName\" >";
        $zoneNamesList = $this->zoneNameList();
        foreach ($zoneNamesList as $key => $value) {
            //print_r($key . $value);
            $html .= "<option value=\"" . $value . "\">" . $key . "</option>";
        }

        $html .= "</select>";
        return $html;
    }

    private function selectionOptions(){
        // <label class="container">One
        //     <input type="checkbox" checked="checked">
        //     <span class="checkmark"></span>
        // </label>
        $html = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxShowTH\" type=\"checkbox\"> Show TH Rates</label><br>";
        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxBCNM\" type=\"checkbox\"> Include BCNMs</label><br>";
        $html .= "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxExcludeNM\" type=\"checkbox\"> Exclude NMs</label>";

        return $html;
    }

    private function selectLvlDropDown($classname){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select> FFXIPackageHelper_dynamiccontent_selectMinCraftLvl
        $html = "<select id=\"". $classname ."\" >";

        for ($i = 0; $i <= 85; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
        return $html;
    }
}

?>