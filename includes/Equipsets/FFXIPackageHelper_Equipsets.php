<?php

// SQL
// item_equipment
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


class FFXIPackageHelper_Equipsets  {
    public function __construct() {
    }


    static function onParserInit( Parser $parser ) {
        $parser->setHook('Equipsets','FFXIPackageHelper_Equipsets::showequipset' );
        return true;
	}

    public static function showequipset( $input, array $params, Parser $parser, PPFrame $frame ) {

        // $parser->getOutput()->updateCacheExpiry(0);
        // $parser->getOutput()->addModules(['FFXIPackageHelper_dynamicContent']);

        //$html = searchForm();

        $test = new FFXIPackageHelper_Equipsets();
        $html = $test->searchForm();


        return 	$html;
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
                    <div id=\"FFXIPackageHelper_tabs_recipeSearch_queryresult\"></div>
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


    public function equipsets(){
        $html = "<div class=\"FFXIPackageHelper_Equipsets_container\" >
            <div>
                <table class=\"FFXIPackageHelper_Equipsets_table\">
                    <tr>
                        <th></th>
                        <th>Slot</th>
                        <th>Item</th>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Main</td>
                        <td>heasfdasdgagafad</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Change</button></td>
                        <td>Sub</td>
                        <td>nefsdfsck</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Range</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Ammo</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Head</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Neck</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Ear1</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Ear2</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Body</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Hands</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Ring1</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Ring2</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                                        <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Back</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Waist</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Legs</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_customButton\">Add</button></td>
                        <td>Feet</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                </table>
            </div>
            <div class=\"FFXIPackageHelper_Equipsets_showset\">
                <p class=\"STR\"><b>Equipment Stats</b></p>
                <p class=\"STR\">STR</p>
                <p class=\"STR\">DEX</p>
                <p class=\"STR\">VIT</p>
                <p class=\"STR\">AGI</p>
                <p class=\"STR\">INT</p>
                <p class=\"STR\">MND</p>
                <p class=\"STR\">CHR</p>
            </div>
        </div>
        ";

        return $html;
    }
}

?>