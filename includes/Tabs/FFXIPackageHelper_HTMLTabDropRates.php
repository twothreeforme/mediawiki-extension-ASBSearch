<?php


class FFXIPackageHelper_HTMLTabDropRates {
    public function __construct() {
      }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_droprates_searchForm\"><h3>Search Form</h3>" .
                    "<table><tbody><tr><td>
                        <table><tbody>
                        <tr>
                            <td>Mob/BCNM Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Item Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"itemNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Zone " . $this->zonesDropDown() . "<br><button id=\"FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                        </tr>
                        </tbody></table>
                    </td>
                        <td>Level: Min-><select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select> Max-> <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMAX\"></select><br>" . $this->selectionOptions() . "</td>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_droprates_queryresult\"></div>
                </div>";
        return $html;
    }

    public function lvlDropDown(){

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
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectZonenName\" >";
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

    
}

?>