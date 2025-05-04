<?php

use MediaWiki\MediaWikiServices;

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

    // private $race;
    // private $mlvl;
    // private $slvl;
    // private $mjob;
    // private $sjob;
    // private $equipment;

    public function __construct($query = null) {
        // if ( gettype($query) == 'array'){
        //     $this->race = $query[0];
        //     $this->mlvl = $query[1];
        //     $this->slvl = $query[2];
        //     $this->mjob = $query[3];
        //     $this->sjob = $query[4];
        //     $this->equipment = $query[5];
        // }
    }

     public function querySection(){
        $maxedSub = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxMaxSub\" type=\"checkbox\" checked=\"checked\"><i>(max)</i></input></label>";
        $html = "<div class=\"FFXIPackageHelper_Equipsets_selectOptions\">" .
                    // "<span>Character  " . FFXIPackageHelper_HTMLOptions::userCharsDropDown("FFXIPackageHelper_equipsets_selectUserChar") . "</span>
                    // <button id=\"FFXIPackageHelper_dynamiccontent_addCharacter\" class=\"FFXIPackageHelper_dynamiccontent_addCharacter\">
                    //     <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
                    //         <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\" stroke=\"#303030\" stroke-linecap=\"round\"/>
                    //         <line x1=\"5\" y1=\"0\" x2=\"5\" y2=\"10\" stroke=\"#303030\" stroke-linecap=\"round\"/>
                    //     </svg>
                    // </button>
                    // <button id=\"FFXIPackageHelper_dynamiccontent_removeCharacter\" class=\"FFXIPackageHelper_dynamiccontent_removeCharacter\">
                    //     <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
                    //         <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\" stroke=\"#303030\" stroke-linecap=\"round\"/>
                    //     </svg>
                    // </button>" .

                    "<br>" .
                    //"<span>Saved Sets  " . FFXIPackageHelper_HTMLOptions::setsDropDown("FFXIPackageHelper_equipsets_selectSet") . "</span><br>" .
                    "<span>Main " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectMJob") . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_equipsets_selectMLevel") . "</span><br>
                    <span>Sub " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectSJob") . FFXIPackageHelper_HTMLOptions::subLevelRange("FFXIPackageHelper_equipsets_selectSLevel") . $maxedSub ."</span><br>
                 </div>";
                // <span style=\"float:right;\">". $this->showShareButton("FFXIPackageHelper_dynamiccontent_shareEquipset") . "</span><br><br>
        return $html;
    }

    public function statsSection(){
        $html = "<div class=\"FFXIPackageHelper_Equipsets_showstats\">
                    <p><center><b>Statistics</b></center></p>
                    <table class=\"FFXIPackageHelper_Equipsets_showstats_basestats\">
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>HP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statHP\">0</span></td></tr>
                        <tr><td>MP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMP\">0</span></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>STR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statSTR\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statSTRMod\"></span></td></tr>
                        <tr><td>DEX&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEX\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statDEXMod\"></span></td></tr>
                        <tr><td>VIT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statVIT\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statVITMod\"></span></td></tr>
                        <tr><td>AGI&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statAGI\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statAGIMod\"></span></td></tr>
                        <tr><td>INT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statINT\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statINTMod\"></span></td></tr>
                        <tr><td>MND&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMND\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statMNDMod\"></span></td></tr>
                        <tr><td>CHR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statCHR\">0</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statCHRMod\"></span></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>DEF&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEF\">0</span></td></tr>
                        <tr><td>ATT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statATT\">0</span></td></tr>
                        <tr><td>ACC&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statACC\">0</span></td></tr>
                        <tr><td>EVA&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statEVA\">0</span></td></tr>
                    </table>
                </div>";
        return $html;
    }

    public function equipmentGrid($slot = null){

        $griditems = self::updateGridItems($slot)[0];

        $f = MediaWikiServices::getInstance()->getRepoGroup()->findFile('Blank.jpg');
        $imageURL = $f->getCanonicalUrl();
        $td = "<td style=\"background-image:url(" . $imageURL . ");background-repeat:no-repeat;background-size:64px 64px;\">";

        $html = "";

        for ( $s = 0; $s <= 15; $s++){
            if ( $s == 0 ) $html .= "<tr>";
            else if ( $s == 4 || $s == 8 || $s == 12 ) $html .= "</tr><tr>";

           $html .= $td . "<div class=\"equipsetsGridImage\" id=\"grid" . $s . "\" data-value=\"" . $griditems[$s][1][0]  . "\">". $griditems[$s][1][1] . "</div></td>";
           // $html .= $td . "<div class=\"equipsetsGridImage\" id=\"grid0\" data-value=\"0\">". ParserHelper::wikiParse("[[File:". $slot[$s] . "|64px|link=]]") ."</div></td>";
        }
        // $html .= "</tr></table>";
        $html .= "</tr>";

        return $html;
    }

    public function resistances(){
        $resCircles = array();
        $resCircles[] = "Trans_Fire.gif";
        $resCircles[] = "Trans_Wind.gif";
        $resCircles[] = "Trans_Lightning.gif";
        $resCircles[] = "Trans_Light.gif";
        $resCircles[] = "Trans_Ice.gif";
        $resCircles[] = "Trans_Earth.gif";
        $resCircles[] = "Trans_Water.gif";
        $resCircles[] = "Trans_Dark.gif";

        for ( $r = 0; $r <= 7; $r++){
            $resCircles[$r] = "[[File:".  $resCircles[$r] . "|20px|link=]]";
        }

        $resCircles = ParserHelper::wikiParse($resCircles);

        $html = "<div class=\"FFXIPackageHelper_Equipsets_showstats_res\"><table style=\"width:100%;line-height: 14px;\" >";
            // <tr>
            //     <td style=\"width:100px;\">". $this->resCircle("Fire") ."<p id=\"FFXIPackageHelper_Equipsets_statFire\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Wind") ."<p id=\"FFXIPackageHelper_Equipsets_statWind\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Ice") ."<p id=\"FFXIPackageHelper_Equipsets_statIce\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Light") ."<p id=\"FFXIPackageHelper_Equipsets_statLight\"></p></td>
            // </tr>
            // <tr>
            //     <td style=\"width:100px;\">". $this->resCircle("Water") ."<p id=\"FFXIPackageHelper_Equipsets_statWater\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Earth") ."<p id=\"FFXIPackageHelper_Equipsets_statEarth\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Lightning") ."<p id=\"FFXIPackageHelper_Equipsets_statLightning\"></p></td>
            //     <td style=\"width:100px;\">". $this->resCircle("Dark") ."<p id=\"FFXIPackageHelper_Equipsets_statDark\"></p></td>
            // </tr>

        $td = "<td class=\"FFXIPackageHelper_Equipsets_statRes\" >";
        for ( $r = 0; $r <= 7; $r++){
            if ( $r == 0 ) $html .= "<tr>";
            else if ( $r == 4 ) $html .= "</tr><tr>";
            $html .= $td . $resCircles[$r] . "<span id=\"FFXIPackageHelper_Equipsets_statRes" . $r . "\"  >0</span>";
        }

        $html .= "</tr></table></div>";
        return $html;
    }

    public function userSetsData(){
        // $html = "<div class=\"FFXIPackageHelper_Equipsets_setManagement\">
        //             <b>Sets Management</b><br>
        //             <span style=\"float:left;\">". $this->showShareButton("FFXIPackageHelper_dynamiccontent_shareEquipset") . "</span><br><br>
        //             <span style=\"float:left;\"><button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_saveSet\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\">Save Set</button></span><br><br>
        //             <span style=\"float:left;\">Available Sets</span><br>
        //             <table style=\"width:100%;height:150px;line-height: 14px;\">
        //             </table>
        //             <span style=\"float:left;\"><button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_saveSet\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\">Manage Sets</button></span><br><br>

        //         </div>";

        $html = "<div style=\"width:100%;text-align: center; padding: 12px;\">
                    <span>
                        ". $this->showShareButton("FFXIPackageHelper_dynamiccontent_shareEquipset") .
                        // "<button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_saveSet\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\" disabled>Save</button>" .
                        // "<button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_manageChars\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\" disabled>Manage Chars</button>" .
                        // "<button type=\"button\" id=\"FFXIPackageHelper_dynamiccontent_manageSets\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\" disabled>Manage Sets</button>" .
                    "</span>
                </div>";
        return $html;
     }

    public function additionalData(){
        $html =  "<div class=\"FFXIPackageHelper_Equipsets_additionalData\">" .
                    "<div class=\"FFXIPackageHelper_Equipsets_equipList\">
                        <h3>Equipment List</h3><br>
                        <table>
                            <tr>
                                <td>Main</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel0\"> - </td>
                            </tr>
                            <tr>
                                <td>Sub</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel1\"> - </td>
                            </tr>
                            <tr>
                                <td>Range</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel2\"> - </td>
                            </tr>
                            <tr>
                                <td>Ammo</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel3\"> - </td>
                            </tr>
                            <tr>
                                <td>Head</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel4\"> - </td>
                            </tr>
                            <tr>
                                <td>Neck</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel5\"> - </td>
                            </tr>
                            <tr>
                                <td>Ear1</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel6\"> - </td>
                            </tr>
                            <tr>
                                <td>Ear2</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel7\"> - </td>
                            </tr>
                            <tr>
                                <td>Body</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel8\"> - </td>
                            </tr>
                            <tr>
                                <td>Hands</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel9\"> - </td>
                            </tr>
                            <tr>
                                <td>Ring1</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel10\"> - </td>
                            </tr>
                            <tr>
                                <td>Ring2</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel11\"> - </td>
                            </tr>
                            <tr>
                                <td>Back</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel12\"> - </td>
                            </tr>
                            <tr>
                                <td>Waist</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel13\"> - </td>
                            </tr>
                            <tr>
                                <td>Legs</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel14\"> - </td>
                            </tr>
                            <tr>
                                <td>Feet</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel15\"> - </td>
                            </tr>
                        </table><br>" .
                        FFXIPackageHelper_HTMLOptions::saveButton("FFXIPackageHelper_newSetButton") .
                        "<br><div style=\"background: #202122; height: 1px; width: 70%;\"></div>" .
                        //"<button id=\"FFXIPackageHelper_editSetsButton\" class=\"FFXIPackageHelper_editSetsButton\">Edit</button>" .

                        "<button id=\"FFXIPackageHelper_dynamiccontent_saveSet\" class=\"FFXIPackageHelper_newSetButton FFXIPackageHelper_saveSetButton\">Save</button>" .
                        "<div id=\"FFXIPackageHelper_dynamiccontent_newSetSection\" style=\"display: none;\" >" .
                            //"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Set Name</p>" .
                            "<input type=\"text\" id=\"FFXIPackageHelper_dynamiccontent_setNameInput\" class=\"FFXIPackageHelper_dynamiccontent_setNameInput\" placeholder=\"Set Name\" maxlength=\"25\"></input><br>" .
                        "</div>" .


// "<div class=\"FFXIPackageHelper_equipsets_charSelectOptionsMenu\">" .
//                         // "<button id=\"FFXIPackageHelper_editSetButton\" class=\"FFXIPackageHelper_editCharButton\">Edit</button>" .
//                         "<button id=\"FFXIPackageHelper_dynamiccontent_saveSet\" class=\"FFXIPackageHelper_newSetButton FFXIPackageHelper_saveSetButton\">Save</button>" .
//                     "</div>" .
                        "</div>" .
                    FFXIPackageHelper_HTMLOptions::setsList() .
                "</div>";
        return $html;
    }

    public function setManagementDiv(){
        $html =  "<div>" .
                "</div>";
        return $html;
    }

    public function showLuaSets(){
            $html =  "<div class=\"FFXIPackageHelper_Equipsets_container\" >
                        <span id=\"FFXIPackageHelper_Equipsets_showLuaSets\"></span>
                        </div>";
            return $html;
    }

    public function showEquipsets(){
        $html = "<span><b>THIS IS STILL UNDER CONSTRUCTION. ALL SAVED CHARS & SETS MAY
        BE DELETED AS WE CONTINUE DEVELOPMENT OF THIS TOOL.</b><br><i><b>Disclosure:</b>  This is for experimentation only. If you have any questions/comments please reach out via Discord.</i></span>" .
                    "<div class=\"FFXIPackageHelper_Equipsets_container\" >" .
                    $this->userSetsData() .
                    "<br><table class=\"FFXIPackageHelper_Equipsets_showset\">
                        <tr>
                            <td colspan=\"2\">" . $this->querySection() . "</td>
                        </tr>
                        <tr>
                            <td rowspan=\"2\">" . $this->statsSection() . "</td>
                            <td><table id=\"FFXIPackageHelper_Equipsets_equipmentgrid\" class=\"FFXIPackageHelper_Equipsets_equipmentgrid\" >" . $this->equipmentGrid() . "</table></td>
                        </tr>
                        <tr><td>" . $this->resistances() ."</td></tr>
                    </table>" .
                    $this->additionalData() . 


                    $this->showLuaSets() .
                "</div>";

        return $html;
    }

    public function generateTooltip($details){
        $output = "";
        if ( $details["name"] == ucwords($details["longname"]) ) $output = $details["name"] . "\n\n";
        else $output = $details["name"] . "\n(" . ucwords($details["longname"]) . ")\n\n";

        $output .= $details["descr"] . "\n\nLv." . $details["lvl"] . " ";

        if ( count($details["jobs"]) == 22 )  $output .= " All Jobs";
        else if ( count($details["jobs"]) <= 6 ) $output .= implode( "/", $details["jobs"]);
        else  {
            $chunks = array_chunk($details["jobs"], 6);
            for( $c = 0; $c < count($chunks); $c++){
                if ( $c != 0 ) $output .= "\t  ";
                $output .= implode( "/", $chunks[$c]) . "\n";
            }
        }
        return $output;
    }

    public function updateGridItems($slot, $force = false){
        if ($slot == null){
            for ( $s = 0; $s <= 15; $s++){
                $slot[$s] = [
                    0,
                    0,
                    1,  // set flag = 1 on the first iteration to show initial grid with no items
                    ""];
            }
        }

        $slot[0][1] =  ( intval($slot[0][0] )  != 0 ) ? "itemid_" . intval($slot[0][0] ). ".png" : "Main.jpg";
        $slot[1][1] =  ( intval($slot[1][0] )  != 0 ) ? "itemid_" . intval($slot[1][0] ). ".png" : "Sub.jpg";
        $slot[2][1] =  ( intval($slot[2][0] )  != 0 ) ? "itemid_" . intval($slot[2][0] ). ".png" : "Range.jpg";
        $slot[3][1] =  ( intval($slot[3][0] )  != 0 ) ? "itemid_" . intval($slot[3][0] ). ".png" : "Ammo.jpg";
        $slot[4][1] =  ( intval($slot[4][0] )  != 0 ) ? "itemid_" . intval($slot[4][0] ). ".png" : "Head.jpg";
        $slot[5][1] =  ( intval($slot[5][0] )  != 0 ) ? "itemid_" . intval($slot[5][0] ). ".png" : "Neck.jpg";
        $slot[6][1] =  ( intval($slot[6][0] )  != 0 ) ? "itemid_" . intval($slot[6][0] ). ".png" : "Ear1.jpg";
        $slot[7][1] =  ( intval($slot[7][0] )  != 0 ) ? "itemid_" . intval($slot[7][0] ). ".png" : "Ear2.jpg";
        $slot[8][1] =  ( intval($slot[8][0] )  != 0 ) ? "itemid_" . intval($slot[8][0] ). ".png" : "Body.jpg";
        $slot[9][1] =  ( intval($slot[9][0] )  != 0 ) ? "itemid_" . intval($slot[9][0] ). ".png" : "Hands.jpg";
        $slot[10][1] = ( intval($slot[10][0])  != 0 ) ? "itemid_" . intval($slot[10][0]) . ".png" : "Ring1.jpg";
        $slot[11][1] = ( intval($slot[11][0])  != 0 ) ? "itemid_" . intval($slot[11][0]) . ".png" : "Ring2.jpg";
        $slot[12][1] = ( intval($slot[12][0])  != 0 ) ? "itemid_" . intval($slot[12][0]) . ".png" : "Back.jpg";
        $slot[13][1] = ( intval($slot[13][0])  != 0 ) ? "itemid_" . intval($slot[13][0]) . ".png" : "Waist.jpg";
        $slot[14][1] = ( intval($slot[14][0])  != 0 ) ? "itemid_" . intval($slot[14][0]) . ".png" : "Legs.jpg";
        $slot[15][1] = ( intval($slot[15][0])  != 0 ) ? "itemid_" . intval($slot[15][0]) . ".png" : "Feet.jpg";

        $wParser = ParserHelper::wikiParseOptions();
        $title = $wParser[0];
        $parser = $wParser[1];
        $parserOptions = $wParser[2];

        $iDetails = new FFXIPackageHelper_ItemDetails();
        $tooltip = "";

        $updatedGrid = array();
        $luaNames = array();
        for ( $s = 0; $s <= 15; $s++){

            $id = intval($slot[$s][0]);
            if ( $id != 0 )  $luaNames[] = ucwords($iDetails->items[ $id ]["name"]);
            else $luaNames[] = 0;


            if ( $slot[$s][2] == 1 || $force == true ){
                $slot[$s][1] = "[[File:". $slot[$s][1] . "|64px|link=]]";
                //<span class="hint--bottom" aria-label="Thank you!">hover me.</span>
                $parserOutput = $parser->parse( $slot[$s][1], $title, $parserOptions );
                $slot[$s][1] = $parserOutput->getText();


                if ( $slot[$s][0] != 0 ){
                    //$id = intval($slot[$s][0]);
                    $tooltip = $this->generateTooltip($iDetails->items[ $id ]);

                }
                else $tooltip = "";

                $updatedGrid[] = [$s, $slot[$s], $tooltip];
                //if ( $slot[$s][3] != null ) throw new Exception( $s . ":" . $id . ", of type: " . gettype($id) );
            }
        }
        //if ( $slot[5][0] == 15515) throw new Exception ( json_encode($updatedGrid));
        return [$updatedGrid, $luaNames];
    }

    private function showShareButton($id){
        return FFXIPackageHelper_HTMLTableHelper::shareButton($id);
    }
}

?>