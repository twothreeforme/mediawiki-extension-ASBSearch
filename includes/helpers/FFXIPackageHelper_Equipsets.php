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

    private $sharedLink;
    private $sharedEquipmentModel;

    public function __construct(FFXIPH_Character $query = null) {
        if ( !is_null($query) && $query->isDefault() == false){
            $this->sharedLink = $query->toArray();
        }
        else {
            $temp = new FFXIPH_Character(); 
            $this->sharedLink = $temp->toArray();
        }

        $this->sharedEquipmentModel = new FFXIPackageHelper_Equipment(  $this->sharedLink['equipment'] );
        
    }

    public function querySection(){
        $maxedSub = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxMaxSub\" type=\"checkbox\" checked=\"checked\"><i>(max)</i></input></label>";
        $html = "<div class=\"FFXIPackageHelper_Equipsets_selectOptions\">" .
                    "<br>" .
                    //"<span>Saved Sets  " . FFXIPackageHelper_HTMLOptions::setsDropDown("FFXIPackageHelper_equipsets_selectSet") . "</span><br>" .
                    "<span>Main " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectMJob", $this->sharedLink['mjob']) . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_equipsets_selectMLevel", $this->sharedLink['mlvl']) . "</span><br>
                    <span>Sub " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectSJob", $this->sharedLink['sjob']) . FFXIPackageHelper_HTMLOptions::subLevelRange("FFXIPackageHelper_equipsets_selectSLevel", $this->sharedLink['slvl']) . $maxedSub ."</span><br>
                    </div>";
        return $html;
    }

    public function statsSection( $stats = null){
        
        $html = "<div class=\"FFXIPackageHelper_Equipsets_showstats\">
                    <p><center><b>Statistics</b></center></p>
                    <table class=\"FFXIPackageHelper_Equipsets_showstats_basestats\">
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>HP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statHP\">" . ($stats ? $stats[0] : 0) . "</span></td></tr>
                        <tr><td>MP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMP\">" . ($stats ? $stats[1] : 0) . "</span></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>STR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statSTR\">" . ($stats ? $stats[2] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statSTRMod\"" . ($stats ? self::styleStatMod($stats[3]) : 0) . "</span></td></tr>
                        <tr><td>DEX&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEX\">" . ($stats ? $stats[4] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statDEXMod\"" . ($stats ? self::styleStatMod($stats[5]) : 0) . "</span></td></tr>
                        <tr><td>VIT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statVIT\">" . ($stats ? $stats[6] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statVITMod\"" . ($stats ? self::styleStatMod($stats[7]) : 0) . "</span></td></tr>
                        <tr><td>AGI&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statAGI\">" . ($stats ? $stats[8] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statAGIMod\"" . ($stats ? self::styleStatMod($stats[9]) : 0) . "</span></td></tr>
                        <tr><td>INT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statINT\">" . ($stats ? $stats[10] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statINTMod\"" . ($stats ? self::styleStatMod($stats[11]) : 0) . "</span></td></tr>
                        <tr><td>MND&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMND\">" . ($stats ? $stats[12] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statMNDMod\"" . ($stats ? self::styleStatMod($stats[13]) : 0) . "</span></td></tr>
                        <tr><td>CHR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statCHR\">" . ($stats ? $stats[14] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statCHRMod\"" . ($stats ? self::styleStatMod($stats[15]) : 0) . "</span></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>DEF&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEF\">" . ($stats ? $stats[16] : 0) . "</span></td></tr>
                        <tr><td>ATT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statATT\">" . ($stats ? $stats[17] : 0) . "</span></td></tr>
                        <tr><td>ACC&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statACC\">" . ($stats ? $stats[26] : 0) . "</span></td></tr>
                        <tr><td>EVA&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statEVA\">" . ($stats ? $stats[27] : 0) . "</span></td></tr>
                    </table>
                </div>";
        return $html;
    }

    private function styleStatMod($stat){
        $style = "";
        if ( $stat > 0 ){
            $style = " style=\"color:green;\">&nbsp;&nbsp;+";
        }
        else if ( $stat < 0 ) {
            $style = " style=\"color:red;\">&nbsp;&nbsp;-";
        }
        else $style = ">";

        if ( $stat != 0 ) return $style . $stat;
        else return $style;
    }

    public function equipmentGrid($updatedGridItems = null){
        //throw new Exception ( json_encode($updatedGridItems));
        $f = MediaWikiServices::getInstance()->getRepoGroup()->findFile('Blank.jpg');
        $imageURL = $f->getCanonicalUrl();

        $html = "";

        for ( $s = 0; $s <= 15; $s++){

            $td = "<td style=\"background-image:url(" . $imageURL . ");background-repeat:no-repeat;background-size:64px 64px;\""; 
            if ( $updatedGridItems[$s][2] ){
                $td .= " class=\"hint--bottom\" aria-label=\"";
                $tooltip = str_replace("\"", "\\\"", $updatedGridItems[$s][2]);

                $td .= $tooltip;
                $td .= "\"";
            }
            $td .= ">";

            if ( $s == 0 ) $html .= "<tr>";
            else if ( $s == 4 || $s == 8 || $s == 12 ) $html .= "</tr><tr>";

           $html .= $td . "<div class=\"equipsetsGridImage\" id=\"grid" . $s . "\" data-value=\"" . $updatedGridItems[$s][1][0]  . "\">". $updatedGridItems[$s][1][1] . "</div></td>";
        }
        // $html .= "</tr></table>";
        $html .= "</tr>";

        return $html;
    }

    public function resistances( $stats = null){
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
        $td = "<td class=\"FFXIPackageHelper_Equipsets_statRes\" >";
        //throw new Exception ( json_encode($updatedGridItems));
        for ( $r = 0; $r <= 7; $r++){
            if ( $r == 0 ) $html .= "<tr>";
            else if ( $r == 4 ) $html .= "</tr><tr>";
            $s = $r + 18;
            $html .= $td . $resCircles[$r] . "<span id=\"FFXIPackageHelper_Equipsets_statRes" . $r . "\"  >". ($stats ? $stats[$s] : 0) . "</span>";
        }

        $html .= "</tr></table></div>";
        return $html;
    }

    public function userSetsData(){
        $html = "<div style=\"width:100%;text-align: center; padding: 12px;\">
                    <span>". 
                    FFXIPackageHelper_HTMLTableHelper::shareButton("FFXIPackageHelper_dynamiccontent_shareEquipset") .
                    FFXIPackageHelper_HTMLTableHelper::shareDiscordButton("FFXIPackageHelper_dynamiccontent_shareDiscordEquipset") .
                    "</span>
                </div>";
        return $html;
    }

    /**
     * @param $luaNamesArray array of strings | names of items
     */
    public function additionalData($luaNamesArray){

        $html =  "<div class=\"FFXIPackageHelper_Equipsets_additionalData\">" .
                    "<div class=\"FFXIPackageHelper_Equipsets_equipList\">
                        <h3 style=\"display:block;margin-top:0em;padding:0px;\">Equipment List</h3><br>
                        <table>
                            <tr>
                                <td>Main</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel0\">" . (( !is_null($luaNamesArray) && $luaNamesArray[0] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[0] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Sub</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel1\">" . (( !is_null($luaNamesArray) && $luaNamesArray[1] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[1] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Range</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel2\">" . (( !is_null($luaNamesArray) && $luaNamesArray[2] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[2] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Ammo</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel3\">" . (( !is_null($luaNamesArray) && $luaNamesArray[3] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[3] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Head</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel4\">" . (( !is_null($luaNamesArray) && $luaNamesArray[4] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[4] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Neck</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel5\">" . (( !is_null($luaNamesArray) && $luaNamesArray[5] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[5] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Ear1</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel6\">" . (( !is_null($luaNamesArray) && $luaNamesArray[6] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[6] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Ear2</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel7\">" . (( !is_null($luaNamesArray) && $luaNamesArray[7] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[7] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Body</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel8\">" . (( !is_null($luaNamesArray) && $luaNamesArray[8] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[8] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Hands</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel9\">" . (( !is_null($luaNamesArray) && $luaNamesArray[9] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[9] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Ring1</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel10\">" . (( !is_null($luaNamesArray) && $luaNamesArray[10] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[10] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Ring2</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel11\">" . (( !is_null($luaNamesArray) && $luaNamesArray[11] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[11] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Back</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel12\">" . (( !is_null($luaNamesArray) && $luaNamesArray[12] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[12] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Waist</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel13\">" . (( !is_null($luaNamesArray) && $luaNamesArray[13] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[13] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Legs</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel14\">" . (( !is_null($luaNamesArray) && $luaNamesArray[14] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[14] . "]]") ) : "- ") . "</td>
                            </tr>
                            <tr>
                                <td>Feet</td><td id=\"FFXIPackageHelper_Equipsets_gridLabel15\">" . (( !is_null($luaNamesArray) && $luaNamesArray[15] != 0 ) ? ( ParserHelper::wikiParse("[[" . $luaNamesArray[15] . "]]") ) : "- ") . "</td>
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
                    "</div>" .
                    FFXIPackageHelper_HTMLOptions::setsList() .
                "</div>";
        return $html;
    }

    // public function setManagementDiv(){
    //     $html =  "<div>" .
    //             "</div>";
    //     return $html;
    // }

    public function showLuaSets($sets){
        $html =  "<div class=\"FFXIPackageHelper_Equipsets_container\" >
                    <span id=\"FFXIPackageHelper_Equipsets_showLuaSets\">";
        $setsHTML = new FFXIPackageHelper_LuaSetsHelper();
        $html .= $setsHTML->__getSetsHTML($sets);    
                    
        $html .=  "</span></div>";
        return $html;
    }

    public function showEquipsets(){
        $stats = null;
        if ( $this->sharedLink['canGenerateStats'] ) {
            //throw new Exception (  json_encode($this->sharedLink) ) ;
            // $equipmentModel = new FFXIPackageHelper_Equipment(  $this->sharedLink['equipment'] );
            //$equipmentArray = $equipmentModel->getEquipmentArray();

            $newStats = new FFXIPackageHelper_Stats( $this->sharedLink['race'], 
                                                    $this->sharedLink['mlvl'],
                                                    $this->sharedLink['slvl'], 
                                                    $this->sharedLink['mjob'], 
                                                    $this->sharedLink['sjob'], 
                                                    $this->sharedLink['merits'], 
                                                    $this->sharedEquipmentModel->getEquipmentArray() );
            $stats =  $newStats->getStats();
        }

        $updatedEquipmentData = $this->updateGridItems($this->sharedEquipmentModel->getIncomingEquipmentList());
        // $updatedGridItems = $updatedEquipmentData[0];
        // $updatedLuaNames = $updatedEquipmentData[1];

        $html = "<span><i><b>Disclosure:</b>  Please reach out with any questions/comments via Discord.</i>" .
                    "<div class=\"FFXIPackageHelper_Equipsets_container\" >" .
                    $this->userSetsData() .
                    "<br><table class=\"FFXIPackageHelper_Equipsets_showset\">
                        <tr>
                            <td colspan=\"2\">" . $this->querySection() . "</td>
                        </tr>
                        <tr>
                            <td rowspan=\"2\">" . $this->statsSection( $stats ) . "</td>
                            <td><table id=\"FFXIPackageHelper_Equipsets_equipmentgrid\" class=\"FFXIPackageHelper_Equipsets_equipmentgrid\" >" . $this->equipmentGrid( $updatedEquipmentData[0] ) . "</table></td>
                        </tr>
                        <tr><td>" . $this->resistances( $stats ) ."</td></tr>
                    </table>" .
                    $this->additionalData( $updatedEquipmentData[1] ) . 
                    $this->showLuaSets( $updatedEquipmentData[1] ) .
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