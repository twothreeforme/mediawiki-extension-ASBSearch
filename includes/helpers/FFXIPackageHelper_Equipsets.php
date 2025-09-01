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

    public function __construct(FFXIPH_Character $query) {
        if ( !is_null($query) && $query->isDefault() == false){
            $this->sharedLink = $query->toArray();
        }
        else {
            $temp = new FFXIPH_Character(); 
            $this->sharedLink = $temp->toArray();
        }

        $this->sharedEquipmentModel = new FFXIPackageHelper_Equipment(  $this->sharedLink['equipment'] );
        
    }

    public function querySection(): string{
        $maxedSub = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxMaxSub\" type=\"checkbox\" checked=\"checked\"><i>(max)</i></input></label>";
        $html = "<div class=\"FFXIPackageHelper_Equipsets_selectOptions\">" .
                    "<br>" .
                    //"<span>Saved Sets  " . FFXIPackageHelper_HTMLOptions::setsDropDown("FFXIPackageHelper_equipsets_selectSet") . "</span><br>" .
                    "<span>Main " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectMJob", $this->sharedLink['mjob']) . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_equipsets_selectMLevel", $this->sharedLink['mlvl']) . "</span><br>
                    <span>Sub " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectSJob", $this->sharedLink['sjob']) . FFXIPackageHelper_HTMLOptions::subLevelRange("FFXIPackageHelper_equipsets_selectSLevel", $this->sharedLink['slvl']) . $maxedSub ."</span><br>
                    </div>";
        return $html;
    }

    public function statsSection( $stats = null): string{

        $html = "
             
                <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                <tr><td>HP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statHP\">" . ($stats ? $stats[0] : 0) . "</span></td></tr>
                <tr><td>MP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMP\">" . ($stats ? $stats[1] : 0) . "</span></td></tr>
                <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                <tr><td>STR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statSTR\">" . ($stats ? $stats[2] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statSTRMod\"" . ($stats ? self::styleStatMod($stats[3]) : 0) . "&emsp;</span></td></tr>
                <tr><td>DEX&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEX\">" . ($stats ? $stats[4] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statDEXMod\"" . ($stats ? self::styleStatMod($stats[5]) : 0) . "&emsp;</span></td></tr>
                <tr><td>VIT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statVIT\">" . ($stats ? $stats[6] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statVITMod\"" . ($stats ? self::styleStatMod($stats[7]) : 0) . "&emsp;</span></td></tr>
                <tr><td>AGI&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statAGI\">" . ($stats ? $stats[8] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statAGIMod\"" . ($stats ? self::styleStatMod($stats[9]) : 0) . "&emsp;</span></td></tr>
                <tr><td>INT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statINT\">" . ($stats ? $stats[10] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statINTMod\"" . ($stats ? self::styleStatMod($stats[11]) : 0) . "&emsp;</span></td></tr>
                <tr><td>MND&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMND\">" . ($stats ? $stats[12] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statMNDMod\"" . ($stats ? self::styleStatMod($stats[13]) : 0) . "&emsp;</span></td></tr>
                <tr><td>CHR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statCHR\">" . ($stats ? $stats[14] : 0) . "</span></td><td><span id=\"FFXIPackageHelper_Equipsets_statCHRMod\"" . ($stats ? self::styleStatMod($stats[15]) : 0) . "&emsp;</span></td></tr>
                <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                <tr><td>DEF&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEF\">" . ($stats ? $stats[16] : 0) . "</span></td></tr>
                <tr><td>ATT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statATT\">" . ($stats ? $stats[17] : 0) . "</span></td></tr>
                <tr><td>ACC&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statACC\">" . ($stats ? $stats[26] : 0) . "</span></td></tr>
                <tr><td>EVA&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statEVA\">" . ($stats ? $stats[27] : 0) . "</span></td></tr>
                <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>" .

                "<tr><td>Gear Haste&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statHasteGear\">" . ($stats ? self::styleHaste($stats[28]["gear"]) : 0) . "</span></td><td>%</td></tr>" .
                "<tr><td>Fast Cast&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statFastCast\">" . ($stats ? $stats[29] : 0) . "</span></td><td>%</td></tr>" .
                "<tr><td>PDT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statPDT\">" . ($stats ? $stats[30] : 0) . "</span></td><td>%</td></tr>" .
                "<tr><td>MDT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMDT\">" . ($stats ? $stats[31] : 0) . "</span></td><td>%</td></tr>" .
                "<tr><td>Conserve MP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statConserveMP\">" . ($stats ? $stats[32] : 0) . "</span></td></tr>" .
                "<tr><td>Enmity&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statEnmity\">" . ($stats ? $stats[33] : 0) . "</span></td></tr>" .

            "</table>";
            
        return $html;
    }

    private function styleHaste($stat){
        return $stat / 100;
    }

    private function styleStatMod($stat){
        $style = "";
        if ( $stat > 0 ){
            $style = " style=\"color:green;\">&nbsp;&nbsp;+";
        }
        else if ( $stat < 0 ) {
            $style = " style=\"color:red;\">&nbsp;&nbsp;";
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

            $td = "<td style=\"height:inherit; background-image:url(" . $imageURL . ");background-repeat:no-repeat;background-size:64px 64px;\"";
            if ( $updatedGridItems[$s][2] ){
                $td .= " class=\"hint--bottom\" aria-label=\"";
                $tooltip = str_replace("\"", "&quot;", $updatedGridItems[$s][2]);
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

        $html = "<table style=\"width:100%;line-height: 14px;\" >";
        $td = "<td class=\"FFXIPackageHelper_Equipsets_statRes\" >";
        //throw new Exception ( json_encode($updatedGridItems));
        for ( $r = 0; $r <= 7; $r++){
            if ( $r == 0 ) $html .= "<tr>";
            else if ( $r == 4 ) $html .= "</tr><tr>";
            $s = $r + 18;
            $html .= $td . $resCircles[$r] . "<span id=\"FFXIPackageHelper_Equipsets_statRes" . $r . "\"  >". ($stats ? $stats[$s] : 0) . "</span>";
        }

        $html .= "</tr></table>";
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

        $html =  "" .
                    "<div class=\"FFXIPackageHelper_Equipsets_equipList\">
                        <h2 style=\"display:block;margin-top:0em;padding:0px;\">Equipment List</h2>
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
                "";
        return $html;
    }

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
        //wfDebugLog( 'Equipsets', get_called_class() . ":showEquipsets:" . json_encode($stats) );
        //wfDebugLog( 'Equipsets', get_called_class() . ":showEquipsets:" . $this->sharedLink['canGenerateStats'] );

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
                            <td><table id=\"FFXIPackageHelper_Equipsets_equipmentgrid\" class=\"FFXIPackageHelper_Equipsets_equipmentgrid\" >" . $this->equipmentGrid( $updatedEquipmentData[0] ) . "</table></td>
                        </tr>
                        <tr><td><div id=\"FFXIPackageHelper_Equipsets_showstats_res\">" . $this->resistances( $stats ) ."</div></td></tr>
                    </table>" .   
                "</div>" .
                "<div class=\"FFXIPackageHelper_Equipsets_additionalData\">
                    <div class=\"FFXIPackageHelper_Equipsets_showstats\">
                        <h2 style=\"display:block;margin-top:0em;padding:0px;\">Statistics</h2>" .
                        "<table id=\"FFXIPackageHelper_Equipsets_showstatstable\" class=\"FFXIPackageHelper_Equipsets_showstatstable\">" .
                            $this->statsSection( $stats ) .
                        "</table></div><br><br>" .
                    $this->additionalData( $updatedEquipmentData[1] ) . 
                "</div>" .
                FFXIPackageHelper_HTMLOptions::setsList() . 
                $this->showLuaSets( $updatedEquipmentData[1] ) ;

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

        $wParser = ParserHelper::wikiParseOptions();
        $title = $wParser[0];
        $parser = $wParser[1];
        $parserOptions = $wParser[2];

        $iDetails = new FFXIPackageHelper_ItemDetails();


        $updatedGrid = array();
        $luaNames = array();
        for ( $s = 0; $s <= 15; $s++){
            $tooltip = "";
            $id = intval($slot[$s][0]);
            if ( $id > 50000 && array_key_exists($id , $iDetails->replacement) ) $id = $iDetails->replacement[ $id ];

            $slot[$s][1] = ( $id != 0 ) ? "itemid_" . $id . ".png" : self::getDefaultImageName($s);

//wfDebugLog( 'Equipsets', get_called_class() . ":updateGridItems:" . $id );

            if ( $id != 0 )  $luaNames[] = ucwords($iDetails->items[ $id ]["name"]);
            else $luaNames[] = 0;

            if ( $slot[$s][2] == 1 || $force == true ){
                $slot[$s][1] = "[[File:". $slot[$s][1] . "|64px|link=]]";
                $parserOutput = $parser->parse( $slot[$s][1], $title, $parserOptions );
                $slot[$s][1] = $parserOutput->getText();

                if ( $id != 0 ){
                    $tooltip = $this->generateTooltip($iDetails->items[ $id ]);
                }

                $updatedGrid[] = [$s, $slot[$s], $tooltip];
            }
        }
        return [$updatedGrid, $luaNames];
    }


    public function showMerits(FFXIPH_Character $c){
        $html = "<div class=\"FFXIPackageHelper_dynamiccontent_showMerits\" >
                    <table id=\"FFXIPackageHelper_dynamiccontent_showMerits_table\" class=\"FFXIPackageHelper_dynamiccontent_showMerits_table\">" . 
                        $this->showMeritsTable($c) .
                    "</table>" . 
                "</div>";
        return $html;
    }

    public function showMeritsTable(FFXIPH_Character $c){
		$html = "";
		$html = "<tr><td><h4>Stats</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">HP (+10 per)</span></td><td style=\"\">" . $this->meritIncrement(2, $c->getMerit(2) ) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">MP (+10 per)</span></td><td style=\"\">" . $this->meritIncrement(5, $c->getMerit(5) ) . "</td></tr>
									<tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr><tr></tr> 
									<tr><td><span style=\"vertical-align:middle;\">STR (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(8, $c->getMerit(8) ) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">DEX (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(9, $c->getMerit(9)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">VIT (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(10, $c->getMerit(10)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">AGI (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(11, $c->getMerit(11)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">INT (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(12, $c->getMerit(12)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">MND (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(13, $c->getMerit(13)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">CHR (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(14, $c->getMerit(14)) . "</td></tr>" .

									"<tr></tr><tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr>" .
									"<tr><td><h4>Combat Skills</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Hand to Hand (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(80, $c->getMerit(80)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Dagger (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(81, $c->getMerit(81)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Sword (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(82, $c->getMerit(82)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Sword (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(83, $c->getMerit(83)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Axe (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(84, $c->getMerit(84)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Axe (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(85, $c->getMerit(85)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Scythe (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(86, $c->getMerit(86)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Polearm (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(87, $c->getMerit(87)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Katana (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(88, $c->getMerit(88)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Katana (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(89, $c->getMerit(89)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Club (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(90, $c->getMerit(90)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Staff (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(91, $c->getMerit(91)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Archery (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(104, $c->getMerit(104)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Marksmanship (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(105, $c->getMerit(105)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Throwing (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(106, $c->getMerit(106)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Guard (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(107, $c->getMerit(107)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Evasion (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(108, $c->getMerit(108)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Shield (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(109, $c->getMerit(109)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Parry (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(110, $c->getMerit(110)) . "</td></tr>" .

									"<tr></tr><tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr>" .
									"<tr><td><h4>Magic Skills</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Divine Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(111, $c->getMerit(111)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Healing Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(112, $c->getMerit(112)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enhancing Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(113, $c->getMerit(113)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enfeebling Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(114, $c->getMerit(114)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Elemental Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(115, $c->getMerit(115)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Dark Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(116, $c->getMerit(116)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Summoning Magic (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(117, $c->getMerit(117)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Ninjutsu (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(118, $c->getMerit(118)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Singing (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(119, $c->getMerit(119)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">String Instrument (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(120, $c->getMerit(120)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Wind Instrument (+2 per)</span></td><td style=\"\">" . $this->meritIncrement(121, $c->getMerit(121)) . "</td></tr>" .
								
									"<tr></tr><tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr>" .
									"<tr><td><h4>Other Skills</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enmity Increase (+1 per)</span></td><td style=\"\">" . $this->meritIncrement(27, $c->getMerit(27)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enmity Decrease (-1 per)</span></td><td style=\"\">" . $this->meritIncrement(999, $c->getMerit(999)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Crit Hit Rate (+1% per)</span></td><td style=\"\">" . $this->meritIncrement(165, $c->getMerit(165)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enemy Crit Hit Rate (-1% per)</span></td><td style=\"\">" . $this->meritIncrement(166, $c->getMerit(166)) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Spell Interruption Rate (-2% per)</span></td><td style=\"\">" . $this->meritIncrement(168, $c->getMerit(168)) . "</td></tr>
                                    ";
		return $html;
	}

	private function meritIncrement($merit, $value = 0){
		if ( $merit <= 14 ) $type = "stats";
		else $type = "skill";

        $html = "<div id=\"FFXIPackageHelper_dynamiccontent_counterbox\" class=\"FFXIPackageHelper_dynamiccontent_counterbox\">" . 
            FFXIPackageHelper_HTMLTableHelper::incrementMinus() .
            "<input id=\"FFXIPackageHelper_equipsets_merits_$type$merit\" class=\"FFXIPackageHelper_dynamiccontent_incrementInput\" type=\"text\" value=\"$value\" readonly >" . 
            FFXIPackageHelper_HTMLTableHelper::incrementPlus() .
            "</div>";

			return $html;
    }

    // private function showShareButton($id){
    //     return FFXIPackageHelper_HTMLTableHelper::shareButton($id);
    // }

    private function getDefaultImageName($s){
        switch($s){
            case 0: return "Main.jpg";
            case 1: return "Sub.jpg";
            case 2: return "Range.jpg";
            case 3: return "Ammo.jpg";
            case 4: return "Head.jpg";
            case 5: return "Neck.jpg";
            case 6: return "Ear1.jpg";
            case 7: return "Ear2.jpg";
            case 8: return "Body.jpg";
            case 9: return "Hands.jpg";
            case 10: return "Ring1.jpg";
            case 11: return "Ring2.jpg";
            case 12: return "Back.jpg";
            case 13: return "Waist.jpg";
            case 14: return "Legs.jpg";
            case 15: return "Feet.jpg";
        }
    }

    public function showCharacters($userChars, $shouldLoadDefaultCharacter, FFXIPH_Character $c){
        $html = "<span><i><b>Disclosure:</b>  Users must be logged in to save a character. Saving a character stores the RACE and MERITS set below. The character will be de-selected if any changes are made. Refresh button resets stats to default.</i></span>" .

					"<div id=\"FFXIPackageHelper_equipsets_charTab\" >" .
						FFXIPackageHelper_HTMLOptions::selectableButtonsBar("FFXIPackageHelper_equipsets_charSelect", $userChars, $shouldLoadDefaultCharacter) .
						
						"<div id=\"FFXIPackageHelper_equipsets_charSelectMerits\">" .

							"<div class=\"FFXIPackageHelper_equipsets_charSelectOptionsMenu\">" .
								"<button id=\"FFXIPackageHelper_editCharButton\" class=\"FFXIPackageHelper_editCharButton\">Edit</button>" .
								"<button id=\"FFXIPackageHelper_dynamiccontent_saveChar\" class=\"FFXIPackageHelper_newCharButton FFXIPackageHelper_saveCharButton\">Save</button>" .
							"</div>" .
							"<div id=\"FFXIPackageHelper_dynamiccontent_newCharSection\" style=\"display: none;\" >" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Name</p>" .
								"<input type=\"text\" id=\"FFXIPackageHelper_dynamiccontent_charNameInput\" class=\"FFXIPackageHelper_dynamiccontent_charNameInput\" placeholder=\"Character Name\" maxlength=\"25\"></input><br>" .
							"</div>" .
							"<div class=\"FFXIPackageHelper_equipsets_selectRace\">" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Default</p>" .
								"<label class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultLabel\">" .
									"<input type=\"checkbox\" id=\"FFXIPackageHelper_dynamiccontent_defaultChar\" class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultInput\" disabled";
								if ( $c->def == 1 ) $html .= " checked";
								$html .= "></input>" .
									"<span class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultSpan FFXIPackageHelper_dynamiccontent_addCharDefaultSpanround\"></span>" .
								"</label>" .
								"<br><p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Race</p>" . FFXIPackageHelper_HTMLOptions::raceDropDown("FFXIPackageHelper_equipsets_selectRace", $c->race) . "<br>" .
							"</div>" .
							"<div>" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Merits</p>" .
								//"<button id=\"FFXIPackageHelper_dynamiccontent_changeMerits\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\">Edit</button><br>" .
							"</div>" .
							"<div class=\"FFXIPackageHelper_dynamiccontent_showMerits\" >
								<table id=\"FFXIPackageHelper_dynamiccontent_showMerits_table\" class=\"FFXIPackageHelper_dynamiccontent_showMerits_table\">" . 
									$this->showMeritsTable($c) .
								"</table>" . 
							"</div>" .
							"<button id=\"FFXIPackageHelper_deleteCharButton\" class=\"FFXIPackageHelper_deleteCharButton\">Remove this character</button>" .

						"</div>" .
					"</div>";
        return $html;
    }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_combatsim_searchForm\">" .
                "<span><i><b>This page is only visible to senior-editor users while the page is under construction. </b>
                <br>DEF and EVA do NOT include mob family or mob pool characteristics yet (ie: Antlion family mobs have +20% DEF, but thats not shown yet in the chart below).</i></span>" .
                "<table><tbody>
                    <tr><td>
                        <table><tbody>
                        <tr>" . 
                            //<td>Mob Name<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" value=\"$this->mobName\" size=\"25\"></td>
                            "<td>Mob Name<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" size=\"25\">" . 
                                "<br>Level: " . FFXIPackageHelper_HTMLTableHelper::selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMob", 95) . 
                            "</td>" .
                        "</tr>
                        <tr>
                            <td><b>AND / OR</b></td>
                        </tr>
                        <tr>
                            <td>Zone<br>" . FFXIPackageHelper_HTMLOptions::zonesDropDown() . "<br><br><button id=\"FFXIPackageHelper_dynamiccontent_searchForMobAndZone\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Find Mob</button></td>
                        </tr>
                        </tbody></table>
                        </td>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_combatsim_queryresult\"></div>
                </div>";
        return $html;
    }
}

?>