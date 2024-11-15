<?php


class FFXIPackageHelper_HTMLTabEquipSearch {

    public function __construct() {
      }

      public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_equipment_searchForm\">" .
                    "<table><tbody><tr><td>
                        <tr>
                            <td>Equipment <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"equipmentNameSearch\" size=\"25\" />
                        </tr>
                        <tr>
                            <td>Job " . $this->jobDropDown() . "
                            <br>Min. Item Level " . $this->minItemLevel("FFXIPackageHelper_dynamiccontent_selectMinItemLvl") . "</td>
                        </tr>
                        <tr>
                            <td><button id=\"FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                        </tr>
                        </td></tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_equipment_queryresult\"></div>
                </div>";
        return $html;
    }

    private function jobDropDown(){
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectJob\" defaultValue=\"0\">";
        $html .= "<option value=\"0\">Any</option>";
        $html .= "<option value=\"1\">Warrior</option>";
        $html .= "<option value=\"2\">Monk</option>";
        $html .= "</select>";
        return $html;
    }

    private function minItemLevel($classname){
        $html = "<select id=\"". $classname ."\" >";

        for ($i = 0; $i <= 75; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
        return $html;
    }
}

?>