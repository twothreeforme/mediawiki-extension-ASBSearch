<?php


class FFXIPackageHelper_HTMLOptions {
    public function __construct() {
      }

      public static function jobDropDown($classname, $sharedJob = null){
        $html = "<select id=\"". $classname ."\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        $html .= "<option value=\"0\">Any</option>";
        $html .= "<option value=\"1\">Warrior</option>";
        $html .= "<option value=\"2\">Monk</option>";
        $html .= "<option value=\"3\">White Mage</option>";
        $html .= "<option value=\"4\">Black Mage</option>";
        $html .= "<option value=\"5\">Red Mage</option>";
        $html .= "<option value=\"6\">Thief</option>";
        $html .= "<option value=\"7\">Paladin</option>";
        $html .= "<option value=\"8\">Dark Knight</option>";
        $html .= "<option value=\"9\">Beastmaster</option>";
        $html .= "<option value=\"10\">Bard</option>";
        $html .= "<option value=\"11\">Ranger</option>";
        $html .= "<option value=\"12\">Samurai</option>";
        $html .= "<option value=\"13\">Ninja</option>";
        $html .= "<option value=\"14\">Dragoon</option>";
        $html .= "<option value=\"15\">Summoner</option>";
        // $html .= "<option value=\"16\">Blue Mage</option>";
        // $html .= "<option value=\"17\">Scholar</option>";
        // $html .= "<option value=\"18\">Puppetmaster</option>";
        // $html .= "<option value=\"19\">Dancer</option>";
        // $html .= "<option value=\"20\">Scholar</option>";
        // $html .= "<option value=\"21\">Geomancer</option>";
        // $html .= "<option value=\"22\">Rune Fencer</option>";

        // Select the job option from the shared link
        if ( $sharedJob != 0 ){
            $html = str_replace("value=\"$sharedJob\"", "value=\"$sharedJob\" selected=\"selected\"", $html);
        }
        
        $html .= "</select>";
        return $html;
    }

    public static function raceDropDown($classname, $sharedRace = null){
        $html = "<select id=\"". $classname ."\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\" disabled>";
        $html .= "<option value=\"0\" selected=\"selected\">Hume</option>";
        $html .= "<option value=\"1\">Elvaan</option>";
        $html .= "<option value=\"2\">Tarutaru</option>";
        $html .= "<option value=\"3\">Mithra</option>";
        $html .= "<option value=\"4\">Galka</option>";
        $html .= "</select>";

        // Select the race option from the shared link
        if ( $sharedRace != 0 ){
            $html = str_replace("value=\"$sharedRace\"", "value=\"$sharedRace\" selected=\"selected\"", $html);
        }
        
        //throw new Exception ($html);
        return $html;
    }

    public static function setsDropDown($classname){
        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        $db = new DBConnection();
        $userSets = $db->getUserSetsFromUserID($uid);

        $html = "<select id=\"". $classname ."\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\" ";

        if ( count($userSets) > 0 ){
            $html .= ">";
            //$vars = new FFXIPackageHelper_Variables();

            $currentJobType = 0;
            foreach ($userSets as $jobtype => $val ) {
                if ( $currentJobType != $jobtype ){
                    $currentJobType = $jobtype;
                    $html .= " <optgroup label=\"" . $jobtype . "\">";
                }
                foreach ( $val as $set ){
                    $html .= "<option value=\"" . $set["usersetid"] . "\">" . $set["setname"] . "</option>";
                    // $html .= "<option value=\"1\">Elvaan</option>";
                    //$html .= "<button id=\"FFXIPackageHelper_setButton_" . $set["setname"] . "\" class=\"" . $classname . "\">" . $set["setname"] . "</button>";
                }
            }

        }
        else {
            $html .= "disabled>";
            $html .= "<option value=\"0\">None</option>";
        }

        $html .= "</select>";
        return $html;

    }

    public static function levelRange($classname, $sharedLvl = null){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";

        for ($i = 0; $i <= 75; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";

        // Select the lvl range option from the shared link
        if ( $sharedLvl != 0 ){
            $html = str_replace("value=\"$sharedLvl\"", "value=\"$sharedLvl\" selected=\"selected\"", $html);
        }
        

        return $html;
    }

    public static function subLevelRange($classname, $sharedLvl = null){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";

        for ($i = 0; $i <= 37; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";

        // Select the lvl range option from the shared link
        if ( $sharedLvl != 0 ){
            $html = str_replace("value=\"$sharedLvl\"", "value=\"$sharedLvl\" selected=\"selected\"", $html);
        }        

        return $html;
    }

    private static function zoneNamelist($fishing = null){
        $db = new DBConnection();
        if ( $fishing == true ) $zonelist = $db->getZoneListFishing();
        else $zonelist = $db->getZoneList();

        foreach ($zonelist as $row) {
			$temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) ) continue;
            if ( $fishing == false && ExclusionsHelper::zoneIsTown($temp) ) continue;
            if ( ctype_digit($temp) ) continue;
			$result[$temp]=$row->name;
			//print_r($result[$temp] .", " . $row->name);
		}
		$result[' ** Search All Zones ** '] = "searchallzones";
		ksort($result);
		return $result ;
    }

    public static function zonesDropDown(){
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectZoneName\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        $zoneNamesList = self::zoneNameList();

        foreach ($zoneNamesList as $key => $value) {
            $html .= "<option value=\"" . $value . "\">" . $key . "</option>";
        }

        $html .= "</select>";
        return $html;
    }

    public static function fishZonesDropDown(){
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectFishingZone\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        $zoneNamesList = self::zoneNameList(true);

        foreach ($zoneNamesList as $key => $value) {
            $html .= "<option value=\"" . $value . "\">" . $key . "</option>";
        }

        $html .= "</select>";
        return $html;
    }

    /**
     * 
     *      True = shoud select default character from array
     *      False = should select NONE character
     *      (string) = should select character with name (string)
     */
    public static function charactersButtonsList($userCharacters, $selectDefaultCharacter = null){

        // "<button id=\"FFXIPackageHelper_newCharButton\" class=\"FFXIPackageHelper_newCharButton\"></button>"
        // $html = "<button id=\"FFXIPackageHelper_newCharButton\" class=\"FFXIPackageHelper_newCharButton\"></button>";
        $html = "";

        //wfDebugLog( 'Equipsets', get_called_class() . ":charactersButtonsList:" . json_encode($selectDefaultCharacter) . ":gettype " .  gettype($selectDefaultCharacter) );
        //wfDebugLog( 'Equipsets', get_called_class() . ":charactersButtonsList:" . $html );

        if ( !is_null($userCharacters) && count($userCharacters) > 0 ){
            /* array of FFIXPH_Character objects */
            foreach ($userCharacters as $char) {
                $classlist = "FFXIPackageHelper_charButton";    
                if ( $char->def != 0 ) {
                    $classlist .= " FFXIPackageHelper_charButton_default";
                    
                    if ( $selectDefaultCharacter === true )  {
                        //$html = str_replace("FFXIPackageHelper_charButtonselectDefaultCharactered", "", $html);
                        $classlist .= " FFXIPackageHelper_charButtonselected";
                    }
                }

                if ( gettype($selectDefaultCharacter) == 'string' && $char->charname == $selectDefaultCharacter && !str_contains($classlist, "FFXIPackageHelper_charButtonselected") ) {
                    $classlist .= " FFXIPackageHelper_charButtonselected";
                }

                $html .= "<button id=\"FFXIPackageHelper_charButton_" . $char->charname . "\" class=\"" . $classlist . "\">" . $char->charname . "</button>";
            }
        }
        
        $classlist = "FFXIPackageHelper_charButton";
        if ( $selectDefaultCharacter === false ){
            $classlist .= " FFXIPackageHelper_charButtonselected";
            //wfDebugLog( 'Equipsets', get_called_class() . ":charactersButtonsList:" . json_encode($selectDefaultCharacter) );
        }
        $html = "<button id=\"FFXIPackageHelper_charButtonNone\" class=\"" . $classlist . "\">None</button>" . $html;
        //$html .= "<button id=\"FFXIPackageHelper_charButtonNone\" class=\"FFXIPackageHelper_charButton\">None</button>";

        return $html;
    }


    public static function setsButtonsList(){
        $html = "";

        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        if ( $uid != 0 && $uid != null ){
            $db = new DBConnection();
            $userSets = $db->getUserSetsFromUserID($uid);
            //throw new Exception ( json_encode($userSets));
            if ( count($userSets) > 0 ){
                foreach ($userSets as $set) {
                    // throw new Exception ( json_encode($set));
                    $classlist = "FFXIPackageHelper_setButton";
                    // if ( $set["def"] != 0  ) $classlist .= " FFXIPackageHelper_setButton_default";
                    $html .= "<button id=\"FFXIPackageHelper_setButton_" . $set["usersetid"] . "\" class=\"" . $classlist . "\">" . $set["setname"] . "</button>";
                }
            }
        }

        return $html;
    }

    public static function selectableButtonsBar($barClassname, $userCharacters){
        $divName = "";
        $newButton = "";
        if ( $barClassname == "FFXIPackageHelper_equipsets_setSelect"){
            $divName = "FFXIPackageHelper_equipsets_setSelect";
            $newButton = "FFXIPackageHelper_newSetButton";
        }
        else if ( $barClassname == "FFXIPackageHelper_equipsets_charSelect" ){
            $divName = "FFXIPackageHelper_equipsets_charSelect";
            $newButton = "FFXIPackageHelper_newCharButton";
        }

        $html = "<div id=\"$divName\">" .
            "<button id=\"$newButton\" class=\"$newButton\">
                <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
                    <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\"  stroke-linecap=\"round\"/>
                    <line x1=\"5\" y1=\"0\" x2=\"5\" y2=\"10\"  stroke-linecap=\"round\"/>
                </svg>
                <span id=\"$newButton-text\">";
        if ( $newButton == "FFXIPackageHelper_newSetButton") $html .= "Save this set" ;
        else $html .= "New";
        $html .= "</span></button>";
        
        $html .= "<div id=\"FFXIPackageHelper_equipsets_charactersButtonsList\">";
        if ( $barClassname == "FFXIPackageHelper_equipsets_charSelect" ) $html .= self::charactersButtonsList($userCharacters, true);
        //else if ( $barClassname == "FFXIPackageHelper_equipsets_setSelect") $html .= self::setsButtonsList();
					
		$html .= "</div></div>";
        return $html;
    }

    public static function saveButton($classname){
        $html = "<button id=\"$classname\" class=\"$classname\">
                <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
                    <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\"  stroke-linecap=\"round\"/>
                    <line x1=\"5\" y1=\"0\" x2=\"5\" y2=\"10\"  stroke-linecap=\"round\"/>
                </svg>
                <span id=\"$classname-text\">";
        if ( $classname == "FFXIPackageHelper_newSetButton") $html .= "Save this set" ;
        else $html .= "New";
        $html .= "</span></button>";
        return $html;
    }

    public static function setsList(){

        $html = "<div id=\"FFXIPackageHelper_Equipsets_setManagement\" class=\"FFXIPackageHelper_Equipsets_setManagement\">";
        //$html .= FFXIPackageHelper_HTMLOptions::selectableButtonsBar("FFXIPackageHelper_equipsets_setSelect");
        
        $html .="<div style=\"width: 100%; flex-wrap: nowrap; display: flex;flex-direction: row;justify-content: space-between;\">";
        $html .="<h3 style=\"display:inline-block;margin-top:0em;padding:0px;\">Available Sets</h3>";
        $html .="<div id=\"FFXIPackageHelper_menuIcon\" class=\"FFXIPackageHelper_menuIcon\">" .
                    "<div class=\"FFXIPackageHelper_menuIcon_bar1\"></div>" .
                    "<div class=\"FFXIPackageHelper_menuIcon_bar2\"></div>" .
                    "<div class=\"FFXIPackageHelper_menuIcon_bar3\"></div>" .
                "</div>";
        $html .="</div>";
        
        $html .= "<div id=\"FFXIPackageHelper_Equipsets_setManagement_setsList\" class=\"FFXIPackageHelper_Equipsets_setManagement_setsList\">";
        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        if ( $uid != 0 && $uid != null ){
            $db = new DBConnection();
            $userSets = $db->getUserSetsFromUserID($uid);
            //throw new Exception ( json_encode($userSets));
            if ( count($userSets) > 0 ){

                $html .= self::setsListTable($userSets);
            }

        }
        $html .= "</div>";
        //$html .= "<button id=\"FFXIPackageHelper_deleteSetButton\" class=\"FFXIPackageHelper_deleteSetButton\">Remove set</button>";

        $html .= "</div>";
        return $html;
    }

    public static function setsListTable($userSets){
        $html = "";
            $html = "<table id=\"FFXIPackageHelper_Equipsets_setManagement_setsListTable\" class=\"FFXIPackageHelper_Equipsets_setManagement_setsListTable\">";
            if ( count($userSets) > 0 ){
                foreach ($userSets as $jobtype => $val ) {
                    $html .="<tr>
                                <th colspan=\"2\">$jobtype</th>
                            </tr>";

                    foreach ( $val as $set ){
                        $html .="<tr>";
                        if (  gettype($set) == "string") throw new Exception ( json_encode($val));
                        $html .= "<td data-value=\"" . $set["usersetid"] . "\">" . $set["setname"] ."</td>";
                        $html .= "<td class =\"FFXIPackageHelper_Equipsets_setManagement_setsListTable_Remove\" data-value=\"" . $set["usersetid"] . "\" style=\"color:red;text-align:end;width: 1%;white-space: nowrap;\" >Remove</td>";
                        $html .="<tr>";
                    }

                }
            }
            $html .= "</table>";

        return $html;
    }

}

?>