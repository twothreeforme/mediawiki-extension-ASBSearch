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
        $html = "<select id=\"". $classname ."\" defaultValue=\"0\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
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
            //$key = str_replace("dOria", "d'Oria", $key);
            $html .= "<option value=\"" . $value . "\">" . $key . "</option>";
        }

        $html .= "</select>";
        return $html;
    }

    public static function userCharsDropDown($classname){
        $html = "<select id=\"". $classname ."\" class=\"FFXIPackageHelper_dynamiccontent_customDropDown\">";
        $html .= "<option value=\"0\">None</option>";

        $user = RequestContext::getMain()->getUser();
        $uid = $user->getId();
        if ( $uid != 0 && $uid != null ){
            $db = new DBConnection();
            $userCharacters = $db->getUserCharactersFromUserID($uid);

            if ( count($userCharacters) > 0 ){
                foreach ($userCharacters as $char) {
                    $html .= "<option >". $char["charname"] ."</option>";
                }
            }

        }

        $html .= "</select>";
        return $html;
    }

}

?>