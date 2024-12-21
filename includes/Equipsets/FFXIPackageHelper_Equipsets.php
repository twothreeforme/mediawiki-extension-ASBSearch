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
    private $imgPath;

    public function __construct() {
        global $wgServer, $wgScriptPath;
        $this->imgPath = $wgServer . $wgScriptPath . "/index.php/Special:Filepath/";
    }


    static function onParserInit( Parser $parser ) {
        $parser->setHook('Equipsets','FFXIPackageHelper_Equipsets::showequipset' );
        return true;
	}

    public static function showequipset( $input, array $params, Parser $parser, PPFrame $frame ) {

        // $parser->getOutput()->updateCacheExpiry(0);

        //$html = searchForm();

        $test = new FFXIPackageHelper_Equipsets();
        $html = $test->showTables();


        return 	$html;
    }

    public function showTables(){
        $html = "<div id=\"FFXIPackageHelper_tabs_equipment_searchForm\">" .
                    "<table><tbody><tr><td>
                        <tr>
                            <td>Equipment <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"equipmentNameSearch\" size=\"25\" />
                        </tr>
                        <tr>
                            <td>Job " . $this->jobDropDown() . "
                            <br>Min. Item Level " . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_dynamiccontent_selectMinItemLvl") . "</td>
                        </tr>
                        <tr>
                            <td><button id=\"FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                        </tr>
                        </td></tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_recipeSearch_queryresult\"></div>
                </div>";
        return $html;
    }

    private function resCircle($res){
        return "<img class=\"\" src=\"" . $this->imgPath . "Trans_" . $res . ".gif\" width=\"20\" height=\"20\">";
    }

    public function equipment(){
        $iSize = 64;

        $maxedSub = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxMaxSub\" type=\"checkbox\" checked=\"checked\"><i>(max)</i></input></label>";

        $html = "<div class=\"FFXIPackageHelper_Equipsets_container\" >
        <table class=\"FFXIPackageHelper_Equipsets_showset\">
            <tr>
                <td colspan=\"2\">
                    <div class=\"FFXIPackageHelper_Equipsets_selectOptions\">
                    <p>Race  " . FFXIPackageHelper_HTMLOptions::raceDropDown("FFXIPackageHelper_equipsets_selectRace") . "</p>
                    <p>Main " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectMJob") . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_equipsets_selectMLevel") . "</p>
                    <p>Sub " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectSJob") . FFXIPackageHelper_HTMLOptions::subLevelRange("FFXIPackageHelper_equipsets_selectSLevel") . $maxedSub ."</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                <div class=\"FFXIPackageHelper_Equipsets_showstats\">
                    <p><center><b>Statistics</b></center></p>

                    <table class=\"FFXIPackageHelper_Equipsets_showstats_basestats\">
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>HP&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statHP\">0</p></td></tr>
                        <tr><td>MP&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statMP\">0</p></td></tr>
                        <tr><td>STR&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statSTR\">0</p></td></tr>
                        <tr><td>DEX&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statDEX\">0</p></td></tr>
                        <tr><td>VIT&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statVIT\">0</p></td></tr>
                        <tr><td>AGI&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statAGI\">0</p></td></tr>
                        <tr><td>INT&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statINT\">0</p></td></tr>
                        <tr><td>MND&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statMND\">0</p></td></tr>
                        <tr><td>CHR&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statCHR\">0</p></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>DEF&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statDEF\">0</p></td></tr>
                        <tr><td>ATT&emsp;</td><td><p id=\"FFXIPackageHelper_Equipsets_statATT\">0</p></td></tr>
                    </table>
                </div>
                </td>
                <td>
                    <table class=\"FFXIPackageHelper_Equipsets_equipmentgrid\">
                        <tr>
                            <td><img class=\"equipsetsGridImage\" id=\"grid0\" data-value=\"0\" src=\"" . $this->imgPath . "Main.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid1\" data-value=\"1\" src=\"" . $this->imgPath . "Sub.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid2\" data-value=\"2\" src=\"" . $this->imgPath . "Range.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid3\" data-value=\"3\" src=\"" . $this->imgPath . "Ammo.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                        </tr>
                        <tr>
                            <td><img class=\"equipsetsGridImage\" id=\"grid4\" data-value=\"4\" src=\"" . $this->imgPath . "Head.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid5\" data-value=\"5\" src=\"" . $this->imgPath . "Neck.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid6\" data-value=\"6\" src=\"" . $this->imgPath . "Ear1.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid7\" data-value=\"7\" src=\"" . $this->imgPath . "Ear2.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                        </tr>
                        <tr>
                            <td><img class=\"equipsetsGridImage\" id=\"grid8\" data-value=\"8\" src=\"" . $this->imgPath . "Body.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid9\" data-value=\"9\" src=\"" . $this->imgPath . "Hands.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid10\" data-value=\"10\" src=\"" . $this->imgPath . "Ring1.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid11\" data-value=\"11\" src=\"" . $this->imgPath . "Ring2.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                        </tr>
                        <tr>
                            <td><img class=\"equipsetsGridImage\" id=\"grid12\" data-value=\"12\" src=\"" . $this->imgPath . "Back.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid13\" data-value=\"13\" src=\"" . $this->imgPath . "Waist.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid14\" data-value=\"14\" src=\"" . $this->imgPath . "Legs.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                            <td><img class=\"equipsetsGridImage\" id=\"grid15\" data-value=\"15\" src=\"" . $this->imgPath . "Feet.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan=\"2\">
                <div class=\"FFXIPackageHelper_Equipsets_showstats_res\">
                    <table >
                        <tr>
                            <td style=\"width:100px;\">". $this->resCircle("Fire") ."<p id=\"FFXIPackageHelper_Equipsets_statFire\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Wind") ."<p id=\"FFXIPackageHelper_Equipsets_statWind\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Ice") ."<p id=\"FFXIPackageHelper_Equipsets_statIce\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Light") ."<p id=\"FFXIPackageHelper_Equipsets_statLight\"></p></td>
                        </tr>
                        <tr>
                            <td style=\"width:100px;\">". $this->resCircle("Water") ."<p id=\"FFXIPackageHelper_Equipsets_statWater\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Earth") ."<p id=\"FFXIPackageHelper_Equipsets_statEarth\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Lightning") ."<p id=\"FFXIPackageHelper_Equipsets_statLightning\"></p></td>
                            <td style=\"width:100px;\">". $this->resCircle("Dark") ."<p id=\"FFXIPackageHelper_Equipsets_statDark\"></p></td>
                        </tr>
                    </table>
                </div>
            </td></tr>
        </table>

        <div>
            <table class=\"FFXIPackageHelper_Equipsets_table\">
                <tr>
                    <td>Main</td>
                    <td id=\"grid0_value\">Empty</td>
                </tr>
                <tr>
                    <td>Sub</td>
                    <td id=\"grid1_value\">Empty</td>
                </tr>
                <tr>
                    <td>Range</td>
                    <td id=\"grid2_value\">Empty</td>
                </tr>
                <tr>
                    <td>Ammo</td>
                    <td id=\"grid3_value\">Empty</td>
                </tr>
                <tr>
                    <td>Head</td>
                    <td id=\"grid4_value\">Empty</td>
                </tr>
                <tr>
                    <td>Neck</td>
                    <td id=\"grid5_value\">Empty</td>
                </tr>
                <tr>
                    <td>Ear1</td>
                    <td id=\"grid6_value\">Empty</td>
                </tr>
                <tr>
                    <td>Ear2</td>
                    <td id=\"grid7_value\">Empty</td>
                </tr>
                <tr>
                    <td>Body</td>
                    <td id=\"grid8_value\">Empty</td>
                </tr>
                <tr>
                    <td>Hands</td>
                    <td id=\"grid9_value\">Empty</td>
                </tr>
                <tr>
                    <td>Ring1</td>
                    <td id=\"grid10_value\">Empty</td>
                </tr>
                <tr>
                    <td>Ring2</td>
                    <td id=\"grid11_value\">Empty</td>
                </tr>
                <tr>
                    <td>Back</td>
                    <td id=\"grid12_value\">Empty</td>
                </tr>
                <tr>
                    <td>Waist</td>
                    <td id=\"grid13_value\">Empty</td>
                </tr>
                <tr>
                    <td>Legs</td>
                    <td id=\"grid14_value\">Empty</td>
                </tr>
                <tr>
                    <td>Feet</td>
                    <td id=\"grid15_value\">Empty</td>
                </tr>
            </table>
        </div>

        </div>
        ";

        return $html;
    }
}

?>