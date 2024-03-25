<?php

class ParserHelper {

    /**************************
     * Mob related parsing
     */
    public static function mobName($mobName, $minLvl, $maxLvl, $mobType, $zoneName, $changes){
		//print_r($zoneName);
		$fished = false;
		if ( str_contains($mobName, "_fished") ) {
			$mobName = str_replace("_fished", "", $mobName);
			$fished = true;
		}

		$mobName = self::replaceUnderscores($mobName);
		$mobName = ucwords($mobName);

		// print_r($mobName ."-". $mobType ."...");

		if ( ExclusionsHelper::zoneIsBCNM($zoneName) ) $mobName = " [[$mobName]]<sup>(BCNM)</sup> ";
		else if ( $minLvl == $maxLvl ) {
			if ( $maxLvl == 255) $mobName = " {{changes}}[[$mobName]]<sup>(HENM)</sup> ";
			else $mobName = " [[$mobName]]<sup>($maxLvl)</sup> ";
		}
		else if ( $changes == 1) $mobName = " {{changes}}[[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		else $mobName = " [[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		
		if ( $fished == true ) return " " . $mobName . " (fished) ";
		else if ( $mobType == 2 || $mobType == 16 || $mobType == 18 ) return "[NM] " . $mobName;
		
		return $mobName;
	}


    /**************************
     * Item related parsing
     */
	public static function itemName($item){
		//if item = Nothing
		if ( $item['name'] == 'nothing' ) return " <i>Nothing</i> ";

		//adjust item names
		$itemName = self::replaceUnderscores($item['name']);
		$itemName = ucwords($itemName);

		//if item is on OOE list
		if ( ExclusionsHelper::itemIsOOE($itemName) ) return " <strike>$itemName</strike><sup>(OOE)</sup> ";


		if ( $item['changes'] == 1 )  return " {{changes}}[[$itemName]] ";
		else if ( $item['changes'] == 2 )  return " ** [[$itemName]] ";
		else return " [[$itemName]] ";
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
     * Drop Rate Parsing
     */
    public static function getVarRate($rate){
        switch($rate){
            case '@ALWAYS': return [true, 1000];
            case '@VCOMMON': return [true, 240];
            case '@COMMON': return [true, 150];
            case '@UNCOMMON': return [true, 100];
            case '@RARE': return [true, 50];
            case '@VRARE': return [true, 10];
            case '@SRARE': return [true, 5];
            case '@URARE': return [true, 1];
            default: return [false, $rate];
        }
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

    
}


?>