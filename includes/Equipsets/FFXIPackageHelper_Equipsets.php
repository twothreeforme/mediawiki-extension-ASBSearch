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
    public function __construct() {
    }

     public function querySection(){
        $maxedSub = "<label class=\"FFXIPackageHelper_dynamiccontent_checkContainer\"><input id=\"FFXIPackageHelper_dynamiccontent_checkboxMaxSub\" type=\"checkbox\" checked=\"checked\"><i>(max)</i></input></label>";
        $html = "<div class=\"FFXIPackageHelper_Equipsets_selectOptions\">
                    <span>Race  " . FFXIPackageHelper_HTMLOptions::raceDropDown("FFXIPackageHelper_equipsets_selectRace") . "</span>
                    <p>Main " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectMJob") . FFXIPackageHelper_HTMLOptions::levelRange("FFXIPackageHelper_equipsets_selectMLevel") . "</p>
                    <p>Sub " . FFXIPackageHelper_HTMLOptions::jobDropDown("FFXIPackageHelper_equipsets_selectSJob") . FFXIPackageHelper_HTMLOptions::subLevelRange("FFXIPackageHelper_equipsets_selectSLevel") . $maxedSub ."</p>
                 </div>";

        return $html;
    }

    public function statsSection(){
        $html = "<div class=\"FFXIPackageHelper_Equipsets_showstats\">
                    <p><center><b>Statistics</b></center></p>
                    <table class=\"FFXIPackageHelper_Equipsets_showstats_basestats\">
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>HP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statHP\">0</span></td></tr>
                        <tr><td>MP&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMP\">0</span></td></tr>
                        <tr><td>STR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statSTR\">0</span></td></tr>
                        <tr><td>DEX&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEX\">0</span></td></tr>
                        <tr><td>VIT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statVIT\">0</span></td></tr>
                        <tr><td>AGI&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statAGI\">0</span></td></tr>
                        <tr><td>INT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statINT\">0</span></td></tr>
                        <tr><td>MND&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statMND\">0</span></td></tr>
                        <tr><td>CHR&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statCHR\">0</span></td></tr>
                        <tr><td colspan=\"2\" style=\"height:10px;\"></td></tr>
                        <tr><td>DEF&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statDEF\">0</span></td></tr>
                        <tr><td>ATT&emsp;</td><td><span id=\"FFXIPackageHelper_Equipsets_statATT\">0</span></td></tr>
                    </table>
                </div>";
        return $html;
    }

    public function equipmentGrid($slot = null){

        /*
                <tr>
                    <td><img class=\"equipsetsGridImage\" id=\"grid0\" data-value=\"0\" src=\"" . $this->imgPath . "Main.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid1\" data-value=\"0\" src=\"" . $this->imgPath . "Sub.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid2\" data-value=\"0\" src=\"" . $this->imgPath . "Range.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid3\" data-value=\"0\" src=\"" . $this->imgPath . "Ammo.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                </tr>
                <tr>
                    <td><img class=\"equipsetsGridImage\" id=\"grid4\" data-value=\"0\" src=\"" . $this->imgPath . "Head.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid5\" data-value=\"0\" src=\"" . $this->imgPath . "Neck.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid6\" data-value=\"0\" src=\"" . $this->imgPath . "Ear1.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid7\" data-value=\"0\" src=\"" . $this->imgPath . "Ear2.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                </tr>
                <tr>
                    <td><img class=\"equipsetsGridImage\" id=\"grid8\" data-value=\"0\" src=\"" . $this->imgPath . "Body.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid9\" data-value=\"0\" src=\"" . $this->imgPath . "Hands.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid10\" data-value=\"0\" src=\"" . $this->imgPath . "Ring1.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid11\" data-value=\"0\" src=\"" . $this->imgPath . "Ring2.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                </tr>
                <tr>
                    <td><img class=\"equipsetsGridImage\" id=\"grid12\" data-value=\"0\" src=\"" . $this->imgPath . "Back.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid13\" data-value=\"0\" src=\"" . $this->imgPath . "Waist.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid14\" data-value=\"0\" src=\"" . $this->imgPath . "Legs.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                    <td><img class=\"equipsetsGridImage\" id=\"grid15\" data-value=\"0\" src=\"" . $this->imgPath . "Feet.jpg\" width=\"$iSize\" height=\"$iSize\"></td>
                </tr>
        */

        // $iSize = 64;

        /*
        slot array
        [
            key = slot number, (ie: 0-15)
            value =  [
                        [0] = item id,
                        [1] = html, -- only needs to be here if flag = 1
                        [2] = flag  -- 1 = needs updating
                    ]
        ]

        */


        // **********************************************************
        // if (!is_array($slot)){
        //     for ( $s = 0; $s <= 15; $s++){
        //         $slot[$s] = [0, 0, 1]; // set flag = 1 on the first iteration to show initial grid with no items
        //     }
        // }

        // $slot[0][1] =  ( intval($slot[0][0] )  != 0 ) ? "itemid_" . intval($slot[0][0] ). ".png" : "Main.jpg";
        // $slot[1][1] =  ( intval($slot[1][0] )  != 0 ) ? "itemid_" . intval($slot[1][0] ). ".png" : "Sub.jpg";
        // $slot[2][1] =  ( intval($slot[2][0] )  != 0 ) ? "itemid_" . intval($slot[2][0] ). ".png" : "Range.jpg";
        // $slot[3][1] =  ( intval($slot[3][0] )  != 0 ) ? "itemid_" . intval($slot[3][0] ). ".png" : "Ammo.jpg";
        // $slot[4][1] =  ( intval($slot[4][0] )  != 0 ) ? "itemid_" . intval($slot[4][0] ). ".png" : "Head.jpg";
        // $slot[5][1] =  ( intval($slot[5][0] )  != 0 ) ? "itemid_" . intval($slot[5][0] ). ".png" : "Neck.jpg";
        // $slot[6][1] =  ( intval($slot[6][0] )  != 0 ) ? "itemid_" . intval($slot[6][0] ). ".png" : "Ear1.jpg";
        // $slot[7][1] =  ( intval($slot[7][0] )  != 0 ) ? "itemid_" . intval($slot[7][0] ). ".png" : "Ear2.jpg";
        // $slot[8][1] =  ( intval($slot[8][0] )  != 0 ) ? "itemid_" . intval($slot[8][0] ). ".png" : "Body.jpg";
        // $slot[9][1] =  ( intval($slot[9][0] )  != 0 ) ? "itemid_" . intval($slot[9][0] ). ".png" : "Hands.jpg";
        // $slot[10][1] = ( intval($slot[10][0])  != 0 ) ? "itemid_" . intval($slot[10][0]) . ".png" : "Ring1.jpg";
        // $slot[11][1] = ( intval($slot[11][0])  != 0 ) ? "itemid_" . intval($slot[11][0]) . ".png" : "Ring2.jpg";
        // $slot[12][1] = ( intval($slot[12][0])  != 0 ) ? "itemid_" . intval($slot[12][0]) . ".png" : "Back.jpg";
        // $slot[13][1] = ( intval($slot[13][0])  != 0 ) ? "itemid_" . intval($slot[13][0]) . ".png" : "Waist.jpg";
        // $slot[14][1] = ( intval($slot[14][0])  != 0 ) ? "itemid_" . intval($slot[14][0]) . ".png" : "Legs.jpg";
        // $slot[15][1] = ( intval($slot[15][0])  != 0 ) ? "itemid_" . intval($slot[15][0]) . ".png" : "Feet.jpg";

        // $wParser = ParserHelper::wikiParseOptions();
        // $title = $wParser[0];
        // $parser = $wParser[1];
        // $parserOptions = $wParser[2];

        // for ( $s = 0; $s <= 15; $s++){
        //     if ( $slot[15][2] == 1 ){
        //     $slot[$s][1] = "[[File:". $slot[$s][1] . "|64px|link=]]";

        //     $parserOutput = $parser->parse( $slot[$s][1], $title, $parserOptions );
        //     $slot[$s][1] = $parserOutput->getText();
        //     }
        // }

        $griditems = self::updateGridItems($slot);
        // throw new Exception( implode(',',$griditems));

        // $slot = $griditems[1];
        // throw new Exception( implode(',',$slot[1]));
        // **********************************************************


        //$slot = ParserHelper::wikiParse($slot);

    //     <tr>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid0\" data-value=\"0\">". ParserHelper::wikiParse("[[File:itemid_16594.png|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid1\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Sub.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid2\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Range.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid3\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ammo.jpg|64px|link=]]") ."</div></td>
    // </tr>
    // <tr>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid4\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Head.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid5\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Neck.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid6\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ear1.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid7\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ear2.jpg|64px|link=]]") ."</div></td>
    // </tr>
    // <tr>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid8\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Body.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid9\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Hands.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid10\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ring1.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid11\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ring2.jpg|64px|link=]]") ."</div></td>
    // </tr>
    // <tr>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid12\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Back.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid13\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Waist.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid14\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Legs.jpg|64px|link=]]") ."</div></td>
    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid15\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Feet.jpg|64px|link=]]") ."</div></td>

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



        //$td = "<td style=\"background-image:url(" . $this->imgPath . "Blank.jpg);background-repeat:no-repeat;background-size:64px 64px; width:64px; height:64px;\">";

                    // <tr>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid0\" data-value=\"0\">". ParserHelper::wikiParse("[[File:itemid_16594.png|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid1\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Sub.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid2\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Range.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid3\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ammo.jpg|64px|link=]]") ."</div></td>
                    // </tr>
                    // <tr>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid4\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Head.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid5\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Neck.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid6\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ear1.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid7\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ear2.jpg|64px|link=]]") ."</div></td>
                    // </tr>
                    // <tr>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid8\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Body.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid9\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Hands.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid10\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ring1.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid11\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Ring2.jpg|64px|link=]]") ."</div></td>
                    // </tr>
                    // <tr>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid12\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Back.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid13\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Waist.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid14\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Legs.jpg|64px|link=]]") ."</div></td>
                    //     ". $td . "<div class=\"equipsetsGridImage\" id=\"grid15\" data-value=\"0\">". ParserHelper::wikiParse("[[File:Feet.jpg|64px|link=]]") ."</div></td>
                //     </tr>
                // </table>";
        return $html;
    }

    public function resistances(){
        $resCircles = array();
        $resCircles[] = "Trans_Fire.gif";
        $resCircles[] = "Trans_Wind.gif";
        $resCircles[] = "Trans_Ice.gif";
        $resCircles[] = "Trans_Light.gif";
        $resCircles[] = "Trans_Water.gif";
        $resCircles[] = "Trans_Earth.gif";
        $resCircles[] = "Trans_Lightning.gif";
        $resCircles[] = "Trans_Dark.gif";

        for ( $r = 0; $r <= 7; $r++){
            $resCircles[$r] = "[[File:".  $resCircles[$r] . "|20px|link=]]";
        }

        $resCircles = ParserHelper::wikiParse($resCircles);

        $html = "<div class=\"FFXIPackageHelper_Equipsets_showstats_res\"><table style=\"width:100%;\" >";
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

    public function equipLabels(){
        $html =  "<div><table class=\"FFXIPackageHelper_Equipsets_table\">
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
                    </div>";
        return $html;
    }

    public function showEquipsets(){
        $html = "<div ><i><b>Disclosure:</b>  This is for experimentation only. When complete this will serve as a place to assist people with theorycrafting and equipment sets design. This
            is by no means complete and is currently being tested by senior editors. If you have any questions/comments please reach out via Discord.</i></div>
                    <div class=\"FFXIPackageHelper_Equipsets_container\" >
                    <table class=\"FFXIPackageHelper_Equipsets_showset\">
                        <tr>
                            <td colspan=\"2\">" . $this->querySection() . "</td>
                        </tr>
                        <tr>
                            <td rowspan=\"2\">" . $this->statsSection() . "</td>
                            <td><table id=\"FFXIPackageHelper_Equipsets_equipmentgrid\" class=\"FFXIPackageHelper_Equipsets_equipmentgrid\" >" . $this->equipmentGrid() . "</table></td>
                        </tr>
                        <tr><td>" . $this->resistances() ."</td></tr>
                    </table>" .
                 /*   $this->equipLabels() . */
                "</div>";

        return $html;
    }

    public function generateTooltip($details){
        //throw new Exception(json_encode($details));
        $output = $details["name"] . "\n\n" . $details["descr"];
        return $output;
    }

    public function updateGridItems($slot){
        //throw new Exception($slot);

        if ($slot == null){
            for ( $s = 0; $s <= 15; $s++){
                $slot[$s] = [0, 0, 1, null]; // set flag = 1 on the first iteration to show initial grid with no items
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
        $tooltip = null;

        $updatedGrid = array();
        for ( $s = 0; $s <= 15; $s++){
            //throw new Exception($slot[$s][2]);
            if ( $slot[$s][2] == 1 ){
                $slot[$s][1] = "[[File:". $slot[$s][1] . "|64px|link=]]";
                //<span class="hint--bottom" aria-label="Thank you!">hover me.</span>
                $parserOutput = $parser->parse( $slot[$s][1], $title, $parserOptions );
                $slot[$s][1] = $parserOutput->getText();

                if ( $slot[$s][0] != 0 ){
                    $id = intval($slot[$s][0]);
                    $tooltip = $this->generateTooltip($iDetails->items[ $id ]);
                }
                else $tooltip = null;

                $updatedGrid[] = [$s, $slot[$s], $tooltip];
                //if ( $slot[$s][3] != null ) throw new Exception( $s . ":" . $id . ", of type: " . gettype($id) );
            }
        }

        return $updatedGrid;
    }
}

?>