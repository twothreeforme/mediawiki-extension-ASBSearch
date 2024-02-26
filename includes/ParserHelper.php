<?php

class ParserHelper {

    /**************************
     * Mob related parsing
     */
    public static function mobName($mobName, $minLvl, $maxLvl){
		$fished = false;
		if ( str_contains($mobName, "_fished") ) {
			$mobName = str_replace("_fished", "", $mobName);
			$fished = true;
		}

		$mobName = self::replaceUnderscores($mobName);
		$mobName = ucwords($mobName);

		$mobName = " [[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		if ( $fished == true ) return " " . $mobName . " (fished) ";
		else return $mobName;
	}


    /**************************
     * Item related parsing
     */
	public static function itemName($itemName){
		$itemName = self::replaceUnderscores($itemName);
		$itemName = ucwords($itemName);
		return " [[$itemName]] ";
	}


    /**************************
     * Zone related parsing
     */
    public static function zoneName($zone){
		$zone = ParserHelper::replaceUnderscores($zone);
		$zone = str_replace("[S]", "(S)", $zone);
		$zone = str_replace("-", " - ", $zone);
		return " [[$zone]] ";
	}

    public static function zoneERA_forList($zone){
		$zone = ParserHelper::replaceUnderscores($zone);
        
		$zone = str_replace("[S]", "(S)", $zone);

        if ( ExclusionsHelper::zoneIsOOE($zone) ) return NULL;

		$zone = str_replace("-", " - ", $zone);

		return $zone;
	}

    public static function zoneERA_forQuery($zone){
		$zone = ParserHelper::replaceSpaces($zone);
		//$zone = str_replace("(S)", "[S]", $zone);
		$zone = str_replace(" - ", "-", $zone);
		return $zone;
	}


    /**************************
     * General Parsing
     */
    public static function replaceApostrophe($inputStr){
		return str_replace("'", "", $inputStr);
	}

	public static function replaceSpaces($inputStr){
		return str_replace(" ", "_", $inputStr);
	}

	public static function replaceUnderscores($inputStr){
		return str_replace("_", " ", $inputStr);
	}

    //public static function 
}


?>