<?php


class FFXIPackageHelper_HTMLTabEquipSearch {

    public function __construct() {
      }

      public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_equipment_searchForm\">" .
                    "<div ><i><b>Disclosure:</b>  This table is for experimentation only. Currently there is no mechanism to filter the gear by era, so everything listed here is available in retail at lvl 75 and below." . 
                    "We are working to make the table more accurate.</i></div>
                    <table><tbody><tr><td>
                        <tr>
                            <td>Equipment <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"equipmentNameSearch\" size=\"25\" />
                        </tr>
                        <tr>
                            <td>Job " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_dynamiccontent_selectJob") . "
                            <br>Slot ". $this->slotTypeDropDown() . "
                            <br>Max Item Level " . $this->minItemLevel("FFXIPackageHelper_dynamiccontent_selectMinItemLvl") . "</td>
                        </tr>
                        <tr>
                            <td><button id=\"FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                        </tr>
                        </td></tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_equipment_queryresult\"></div>
                </div>";
        return $html;
    }

    private function minItemLevel($classname){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        
        $html .= "<option value=\"0\">None</option>";
        
        for ($i = 75; $i >= 1; $i--) {
            $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    private function slotTypeDropDown(){
        // main: 1=nothing in offhand, 3= can have offhand
        // sub 2
        // range 4
        // ammo 8
        // head 16
        // neck 512
        // ear 6144
        // body 32
        // hands 64
        // rings 24576
        // back 32768
        // waist 1024
        // legs 128
        // feet 256
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectSlotType\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        $html .= "<option value=\"0\">Any</option>";
        $html .= "<option value=\"1\">Main</option>";
        $html .= "<option value=\"2\">Sub</option>";
        $html .= "<option value=\"4\">Range</option>";
        $html .= "<option value=\"8\">Ammo</option>";
        $html .= "<option value=\"16\">Head</option>";
        $html .= "<option value=\"32\">Body</option>";
        $html .= "<option value=\"64\">Hands</option>";
        $html .= "<option value=\"128\">Legs</option>";
        $html .= "<option value=\"256\">Feet</option>";
        $html .= "<option value=\"512\">Neck</option>";
        $html .= "<option value=\"1024\">Waist</option>";
        $html .= "<option value=\"6144\">Ear</option>";
        $html .= "<option value=\"24576\">Ring</option>";
        $html .= "<option value=\"32768\">Back</option>";
        $html .= "</select>";
        return $html;
    }
}

?>