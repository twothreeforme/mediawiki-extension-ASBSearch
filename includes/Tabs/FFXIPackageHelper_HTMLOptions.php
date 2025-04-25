<?php


class FFXIPackageHelper_HTMLOptions {
    public function __construct() {
      }

      public static function jobDropDown($classname){
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
        $html .= "<option value=\"16\">Blue Mage</option>";
        $html .= "<option value=\"17\">Scholar</option>";
        $html .= "<option value=\"18\">Puppetmaster</option>";
        $html .= "<option value=\"19\">Dancer</option>";
        $html .= "<option value=\"20\">Scholar</option>";
        $html .= "<option value=\"21\">Geomancer</option>";
        $html .= "<option value=\"22\">Rune Fencer</option>";
        $html .= "</select>";
        return $html;
    }

    public static function raceDropDown($classname){
        $html = "<select id=\"". $classname ."\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\" disabled>";
        $html .= "<option value=\"0\">Hume</option>";
        $html .= "<option value=\"1\">Elvaan</option>";
        $html .= "<option value=\"2\">Tarutaru</option>";
        $html .= "<option value=\"3\">Mithra</option>";
        $html .= "<option value=\"4\">Galka</option>";
        $html .= "</select>";
        return $html;
    }

    public static function levelRange($classname){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";

        for ($i = 0; $i <= 75; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public static function subLevelRange($classname){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";

        for ($i = 0; $i <= 37; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
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

    // public static function userCharsDropDown($classname){
    //     $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
    //     $html .= "<option value=\"0\">None</option>";

    //     $user = RequestContext::getMain()->getUser();
    //     $uid = $user->getId();
    //     if ( $uid != 0 && $uid != null ){
    //         $db = new DBConnection();
    //         $userCharacters = $db->getUserCharactersFromUserID($uid);

    //         if ( count($userCharacters) > 0 ){
    //             foreach ($userCharacters as $char) {
    //                 $html .= "<option >". $char["charname"] ."</option>";
    //             }
    //         }

    //     }

    //     $html .= "</select>";
    //     return $html;
    // }

    public static function charactersButtonsList(){
        // "<button id=\"FFXIPackageHelper_newCharButton\" class=\"FFXIPackageHelper_newCharButton\"></button>"
        // $html = "<button id=\"FFXIPackageHelper_newCharButton\" class=\"FFXIPackageHelper_newCharButton\"></button>";
        $html = "";

        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        if ( $uid != 0 && $uid != null ){
            $db = new DBConnection();
            $userCharacters = $db->getUserCharactersFromUserID($uid);

            if ( count($userCharacters) > 0 ){
                foreach ($userCharacters as $char) {
                    //$html .= "<option >". $char["charname"] ."</option>";
                    $classlist = "FFXIPackageHelper_charButton";
                    if ( $char["def"] != 0  ) $classlist .= " FFXIPackageHelper_charButton_default";
                    $html .= "<button id=\"FFXIPackageHelper_charButton_" . $char["charname"] . "\" class=\"" . $classlist . "\">" . $char["charname"] . "</button>";
                }
            }
        }

        return $html;
    }

    public static function setsButtonsList(){
        $html = "";

        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        if ( $uid != 0 && $uid != null ){
            $db = new DBConnection();
            $userSets = $db->getUserSetsFromUserID($uid);
            
            if ( count($userSets) > 0 ){
                foreach ($userSets as $set) {
                    $classlist = "FFXIPackageHelper_setsButton";
                    // if ( $set["def"] != 0  ) $classlist .= " FFXIPackageHelper_setButton_default";
                    $html .= "<button id=\"FFXIPackageHelper_setButton_" . $set["setname"] . "\" class=\"" . $classlist . "\">" . $set["setname"] . "</button>";
                }
            }
        }

        return $html;
    }

    public static function selectableButtonsBar($barClassname){
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
    
							//"<button id=\"FFXIPackageHelper_newCharButton\" class=\"FFXIPackageHelper_newCharButton\">New</button>" .
							"<button id=\"$newButton\" class=\"$newButton\">
								<svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">
									<line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\"  stroke-linecap=\"round\"/>
									<line x1=\"5\" y1=\"0\" x2=\"5\" y2=\"10\"  stroke-linecap=\"round\"/>
								</svg>
								<span id=\"$newButton-text\">"; 
                                if ( $newButton == "FFXIPackageHelper_newSetButton") $html .= "Save this set" ;
                                else $html .= "New";
                                $html .= "</span></button>";
        
        if ( $barClassname == "FFXIPackageHelper_equipsets_setSelect") self::setsButtonsList();
        else if ( $barClassname == "FFXIPackageHelper_equipsets_charSelect" ) self::charactersButtonsList();
					
		$html .= "</div>";
        return $html;
    }
}

?>