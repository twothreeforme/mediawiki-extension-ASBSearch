<?php

use MediaWiki\MediaWikiServices;

class ParserHelper {

    public function __construct($param) {
        $this->itemsArray = $param;
    }
    private $itemsArray;

    public function getItemName($itemid) {
		if ( $itemid == 0 )  return NULL;
		$name = ParserHelper::itemName($this->itemsArray[$itemid]);
        return "[[" . $name . "]]";
	}

    /**************************
     * Mob related parsing
     */
    public static function mobName($mobName, $minLvl, $maxLvl, $mobType, $zoneName, $mobChanges, $bcnmChanges){
		//print_r($zoneName);
		$fished = false;
		if ( str_contains($mobName, "_fished") ) {
			$mobName = str_replace("_fished", "", $mobName);
			$fished = true;
		}

		$mobName = self::replaceUnderscores($mobName);
		$mobName = ucwords($mobName);
        $mobName = str_replace(" Of ", " of ", $mobName);
        
        if ( $mobName == "Treasure Chest" ) $mobName = "Treasure Chest(Monster)";

		// print_r($mobName ."-". $mobType ."...");
        $mobName = self::brackets($mobName);
        
		if ( ExclusionsHelper::zoneIsBCNM($zoneName) ) $mobName .= "<sup>(BCNM)</sup> ";
		else if ( $minLvl == $maxLvl ) {
			if ( $maxLvl == 255) $mobName = " {{changes}}" . $mobName . "<sup>(HENM)</sup> ";
			//else $mobName = " [[$mobName]] <sup>($maxLvl)</sup> ";
		}
		else if ( $mobChanges == 1) $mobName = " {{changes}}" . $mobName; //. "<sup>($minLvl-$maxLvl)</sup> ";
		//else $mobName = " [[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		
		if ( $fished == true ) return " " . $mobName . " (fished) ";
		else if ( 0x02 & $mobType ) return "[NM] " . $mobName;
		
        // MOBTYPE_NORMAL      = 0x00,
        // MOBTYPE_0X01        = 0x01, // available for use
        // MOBTYPE_NOTORIOUS   = 0x02,
        // MOBTYPE_FISHED      = 0x04,
        // MOBTYPE_CALLED      = 0x08,
        // MOBTYPE_BATTLEFIELD = 0x10,
        // MOBTYPE_EVENT       = 0x20

        //if (intval($bcnmChanges) == 1) $mobName = " {{changes}}" . $mobName;

		return $mobName;
	}

    public static function addDetects($mobName, $detects, $aggro, $truedetection, $mobType){
        $size = "14px";
        $detectsString = "<br>";
        if ( $aggro == 0 ) {
            if ( 0x02 & $mobType ) $detectsString .= "[[File:Detect_PassiveHQ.png|" . $size . "|" . $size. "|Passive HQ]] ";
            else $detectsString .= "[[File:Detect_PassiveNQ.png|" . $size . "|" . $size. "|Passive NQ]] ";
        }
        else if ( $aggro == 1 ) {
            if ( 0x02 & $mobType ) $detectsString .= "[[File:Detect_AggroHQ.png|" . $size . "|" . $size. "|Aggressive HQ]] ";
            else $detectsString .= "[[File:Detect_AggroNQ.png|" . $size . "|" . $size. "|Aggressive NQ]] ";
        }

        if ( 0x001 & $detects) {
            if ( $truedetection == 1 ) $detectsString .= "[[File:Detect_TrueSight.png|" . $size . "|" . $size. "|True Sight]] ";
            else $detectsString .= "[[File:Detect_Sight.png|" . $size . "|" . $size. "|Sight]] ";
        }
        if ( 0x002 & $detects) {
            if ( $truedetection == 1 ) $detectsString .= "[[File:Detect_Sound.png|" . $size . "|" . $size. "|True Hearing]] ";
            else $detectsString .= "[[File:Detect_Sound.png|" . $size . "|" . $size. "|Sound]] ";
        }
        if ( 0x004 & $detects) $detectsString .= "[[File:Detect_Blood.png|" . $size . "|" . $size. "|Low HP]] ";
        if ( 0x020 & $detects) $detectsString .= "[[File:Detect_Magic.png|" . $size . "|" . $size. "|Magic]] ";
        //if ( 0x040 & $detects) $detectsString .= "WS,";
        if ( 0x080 & $detects) $detectsString .= "[[File:Detect_JA.png|" . $size . "|" . $size. "|Job Ability]] ";
        if ( 0x100 & $detects) $detectsString .= "[[File:Detect_Scent.png|" . $size . "|" . $size. "|Scent]] ";

        //rtrim($detectsString, ',');
        if ($detectsString != "<br>" )  {
            //$detectsString = substr(trim($detectsString), 0, -1) . "";
            return $mobName .= $detectsString;
        }
        else return $mobName;

    }


    /**************************
     * Item related parsing
     */
	public static function dropRatesItemName($item){
		//if item = Nothing
		if ( $item['name'] == 'nothing' ) return " <i>Nothing</i> ";

		//adjust item names
		$itemName = self::replaceUnderscores($item['name']);
		$itemName = ucwords($itemName);
        $itemName = str_replace(" Of ", " of ", $itemName);
        $itemName = self::cleanStringDetails($itemName);
        $itemName = self::fixTrailingRomanNumerals($itemName);

		//if item is on OOE list
		if ( ExclusionsHelper::itemIsOOE($itemName) ) return " <strike>$itemName</strike><sup>(OOE)</sup> ";


		if ( $item['changes'] == 1 )  return " {{changes}}[[$itemName]] ";
		else if ( $item['changes'] == 2 )  return " ** [[$itemName]] ";
		else return self::brackets($itemName);

	}

    public static function itemName($name){
        //adjust item names
		$name = self::replaceUnderscores($name);
		$name = ucwords($name);
        $name = str_replace(" Of ", " of ", $name);
        $name = self::cleanStringDetails($name);
        $name = self::fixTrailingRomanNumerals($name);

        return $name;
    }

    public static function fixTrailingRomanNumerals($input){
		$frags = explode(" ", $input);

		for ( $i = 0; $i < count($frags); $i++){
			$fragmentUC = strtoupper($frags[$i]);
			//print_r($fragmentUC);
			//$pattern = '/(.+)\s+[IVXLCDM]+\s*$/';
			if (preg_match( "/(?!.*([IXCM])(\1{3}))(?!.*([VLD])(\3))^[IVXLCDM]+$/" , $fragmentUC)) {
				//print_r($fragmentUC . "<br>");
				$frags[$i] = $fragmentUC;
			} else {
				// No Roman numerals found
			}
		}
		return implode(" ", $frags);
	}

    public static function cleanStringDetails($input){
		$stats = array( "INT", "MND", "AGI", "STR", "CHR", "VIT", "DEX", " of ");
		foreach ($stats as $stat) {
			if ( str_ends_with($input,  strtolower( $stat ) ) ) {
				$input = substr_replace($input, $stat, strlen($input) - 3, 3);
			}
			$pos = strpos($input, " Of ");
			if ( $pos !== false ){
				$input = substr_replace($input, " of ", $pos, 4);
			}
		}
		return $input;
	}

    /**************************
     * Zone related parsing
     */
    public static function zoneName($zone){

		$zone = ParserHelper::replaceUnderscores($zone);
		$zone = str_replace("[S]", "(S)", $zone);
		$zone = str_replace("-", " - ", $zone);
        $zone = str_replace(" Of ", " of ", $zone);
        $zone = str_replace("dOria", "d'Oria", $zone);
       
		return self::brackets($zone);
	}

    public static function zone_forList($zone){
		$zone = ParserHelper::replaceUnderscores($zone);
        $zone = str_replace("[S]", "(S)", $zone);

		$zone = str_replace("-", " - ", $zone);
        $zone = str_replace(" Of ", " of ", $zone);
		return $zone;
	}

    public static function zoneERA_forList($zone){
		$zone = ParserHelper::replaceUnderscores($zone);
        $zone = str_replace("[S]", "(S)", $zone);

        if ( ExclusionsHelper::zoneIsOOE($zone) ) return NULL;

		$zone = str_replace("-", " - ", $zone);
        $zone = str_replace(" Of ", " of ", $zone);
        
		return $zone;
	}

    public static function zoneERA_forQuery($zone){
		$zone = ParserHelper::replaceSpaces($zone);
		//$zone = str_replace("(S)", "[S]", $zone);
		$zone = str_replace(" - ", "-", $zone);
        $zone = str_replace("_-_", "-", $zone);

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
        $inputStr = urldecode($inputStr);
        return str_replace("'", '',  $inputStr);
	}

	public static function replaceSpaces($inputStr){
		return str_replace(" ", "_", $inputStr);
	}

	public static function replaceUnderscores($inputStr){
		return str_replace("_", " ", $inputStr);
	}

    public static function brackets($string){
        return " [[$string]] "; 
    }

	// public static function replaceOf($inputStr){
	// 	return str_replace(" Of ", " of ", $inputStr);
	// }


	public static function getWeatherHex($arr, $vanaDay){
        $hexweatherdata =  $arr[(($vanaDay * 2)  + 1 )] . $arr[($vanaDay * 2) ];
        //print_r("<br/>" . "newHex: " . $hexweatherdata . "vanaDay: " . $vanaDay ."<br/>");
        return $hexweatherdata;
    }

    public static function getLastWeatherHex($arr, $w_vanaDate){
        $hexweatherdata = 0;
        do {
            $w_vanaDate = $w_vanaDate - 1;
            //print_r("date: " . $w_vanaDate . "<br/>");
            $hexweatherdata = ParserHelper::getWeatherHex($arr, $w_vanaDate);
        }while ( $hexweatherdata == 0000 );
        return $hexweatherdata;
    }

    // public static function getNextWeatherHex($arr){
    //     $hexweatherdata = 0;
    //     do {
    //         $w_vanaDate = $w_vanaDate - 1;
    //         print_r("date: " . $w_vanaDate . "<br/>");
    //         $hexweatherdata = ParserHelper::getWeatherHex($arr, $w_vanaDate);
    //     }while ( $hexweatherdata == 0000 );
    //     return $hexweatherdata;
    // }

    public static function convertHexToSplitStrings($hex){
        $decimal = hexdec($hex);
            $binary = decbin($decimal);
            $paddedBinary = str_pad(
                $binary,
                15,
                '0',
                STR_PAD_LEFT
            );

            $split = str_split($paddedBinary, 5);

            /*
            print_r("hex: " . $hex . " dec: " . $decimal . "<br/>" );
            print_r("bin: " . $binary . "<br/>");
            print_r("paddedBin: " . $paddedBinary . "<br/>" );
            print_r("split0: " . $split[0] . " split1: " . $split[1] . " split2: " . $split[2] . "<br/>");
            */

            return $split;
    }

	public static function createCompleteWeatherArrayForZone( $weather, $numberOfDays ) {
        if (!$weather ) return NULL;
        if ( !$numberOfDays ) $numberOfDays = 16;

        // $dbr = $this->openConnection();
        // $query = "zone_weather.zone = $zone";
		// $weather = $dbr->newSelectQueryBuilder()
		// 	->select( [ '*' ] )
		// 	->from( 'zone_weather' )
		// 	->where( $query )
		// 	->fetchResultSet();



        $bin = unpack("H*",$weather);
        $arr = str_split($bin[1], 2);
        //print_r(array_keys($arr));

        $vanatime = new VanaTime();
        $m_vanaDate = $vanatime->getWeatherDate();
        //print_r($m_vanaDate);

        /*                                                                        *
        *              0        00000       00000        00000                   *
        *              ^        ^^^^^       ^^^^^        ^^^^^                   *
        *          padding      normal      common       rare

                            DEC             HEX         BIN
        Day: 1251
                normal:    14 (thunder)      E          01110
                common:     2 (clouds)       2          00010
                rare:       2 (clouds)       2          00010

        Day: 1255
                normal:    14 (thunder)      E          01110
                common:     6 (rain)         6          01100
                rare:       6 (rain)         6          01100
        */

        //DEBUGGING
        //$m_vanaDate = 1255;

        //display how many days worth of data?
        $dayArray = array(); // weather data per day evaluated
        $weatherArray = array(); // overarching array for weather table, to show all days
        //$dayUpdate = 0;
        $w_vanaDate = $m_vanaDate;

        // Have to find the first instance of weather in the hex...
        // If the hex reads 0000 on the current day, then keep going back 1 day until
        // reach a day with weather
        //$w_vanaDate = $w_vanaDate + ($dayUpdate);
        //print_r("date: " . $w_vanaDate . "<br/>");
        $hexweatherdata = ParserHelper::getWeatherHex($arr, $m_vanaDate);

        if ( $hexweatherdata == 0 ){
            $hexweatherdata = ParserHelper::getLastWeatherHex($arr, $m_vanaDate);
        }

        do {
        /*         if ( $hexweatherdata == 0000) {
                    do {
                        $w_vanaDate = $w_vanaDate + 1;
                        //print_r("date: " . $w_vanaDate . "<br/>");
                        $hexweatherdata = $this->getWeatherHex($arr, $w_vanaDate);
                    }while ( $hexweatherdata == 0000 );
                } */

            $split = ParserHelper::convertHexToSplitStrings($hexweatherdata);

            //need error check on if split == nil here !!!
            for ( $c = 0; $c < 3; $c++ ){

                $ncr = "normal";
                $weatherType = "None";
                if ( $c == 1 ) $ncr = "common";
                if ( $c == 2 ) $ncr = "rare";

                switch (bindec($split[$c])) {
                    case 1;
                        $weatherType = "Sunshine";
                        break;
                    case 2;
                        $weatherType = "[[File:clouds.png]] Clouds";
                        break;
                    case 3;
                        $weatherType = "<b>Fog</b>";
                        break;
                    case 4;
                        $weatherType = "{{Fire|Weather}} <b>Hot Spell</b>";
                        break;
                    case 5;
                        $weatherType = "{{Fire|Double Weather}} <b>Heat Wave</b>";
                        break;
                    case 6;
                        $weatherType = "{{Water|Weather}} <b>Rain</b>";
                        break;
                    case 7;
                        $weatherType = "{{Water|Double Weather}} <b>Squalls</b>";
                        break;
                    case 8;
                        $weatherType = "{{Earth|Weather}} <b>Dust Storm</b>";
                        break;
                    case 9;
                        $weatherType = "{{Earth|Double Weather}} <b>Sand Storm</b>";
                        break;
                    case 10;
                        $weatherType = "{{Wind|Weather}} <b>Wind</b>";
                        break;
                    case 11;
                        $weatherType = "{{Wind|Double Weather}} <b>Gales</b>";
                        break;
                    case 12;
                        $weatherType = "{{Ice|Weather}} <b>Snow</b>";
                        break;
                    case 13;
                        $weatherType = "{{Ice|Double Weather}} <b>Blizzard</b>";
                        break;
                    case 14;
                        $weatherType = "{{Lightning|Weather}} <b>Thunder</b>";
                        break;
                    case 15;
                        $weatherType = "{{Lightning|Double Weather}} <b>Thunderstorms</b>";
                        break;
                    case 16;
                        $weatherType = "{{Light|Weather}} <b>Auroras</b>";
                        break;
                    case 17;
                        $weatherType = "{{Light|Double Weather}} <b>Stellar Glare</b>";
                        break;
                    case 18;
                        $weatherType = "{{Dark|Weather}} <b>Gloom</b>";
                        break;
                    case 19;
                        $weatherType = "{{Dark|Double Weather}} <b>Darkness</b>";
                        break;
                    default:
                        break;
                }

                //array_push( $weatherArray, [$ncr => $weatherType]);
                $dayArray[$ncr] = $weatherType;
            }


        if ( ($w_vanaDate - $m_vanaDate) < 0 ) $m_vanaDate = $m_vanaDate - 2160;

        if ( $dayArray["normal"] == "None" && $dayArray["common"] == "None" && $dayArray["rare"] == "None") {
            // $dayArray["normal"] = "";
            // $dayArray["common"] = "No Change";
            // $dayArray["rare"] = "";
            $weatherArray[$w_vanaDate - $m_vanaDate] = $weatherArray[$w_vanaDate - $m_vanaDate - 1];
        }
        else $weatherArray[$w_vanaDate - $m_vanaDate] = $dayArray;

        // $dayUpdate = $dayUpdate + 1;
        $hexweatherdata = 0;
        $w_vanaDate = $w_vanaDate + 1;

        if ( $w_vanaDate >= 2160 ) $w_vanaDate =  $w_vanaDate - 2160;
        //print_r($w_vanaDate . "<br>");
        $hexweatherdata = ParserHelper::getWeatherHex($arr, $w_vanaDate);
        //print_r(count($weatherArray));

        }while( count($weatherArray) < $numberOfDays );
        //$weatherArray = array("normal"=>bindec($split[0]), "common"=>bindec($split[1]), "rare"=>bindec($split[2]));
        //print_r($weatherArray);
        return $weatherArray;
	}

    public static function contains($str, array $arr) {
        foreach($arr as $a) {
            if (stripos($str,$a) !== false) return true;
        }
        return false;
    }

    public static function wikiParseOptions(){
        $context = RequestContext::getMain();
        $title = $context->getTitle();
        $parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$user = RequestContext::getMain()->getUser();
        $parserOptions = new ParserOptions($user);
        return [ $title, $parser, $parserOptions ];
    }

    public static function wikiParse($html){
        $wParser = self::wikiParseOptions();
        $title = $wParser[0];
        $parser = $wParser[1];
        $parserOptions = $wParser[2];

        if (is_array($html)){
            $response = array();
            foreach($html as $line){
                $parserOutput = $parser->parse( $line, $title, $parserOptions );
                $response[] = $parserOutput->getText();
            }
            return $response;
        }
        else{
            if ($html != null){
                $parserOutput = $parser->parse( $html, $title, $parserOptions );
                return $parserOutput->getText();
            }
        }
        return null;

        // $parserOutput = $parser->parse( $html, $title, $parserOptions );
        // return $parserOutput->getText();
	}

    public static function checkJob($job, $item){
        return ( $item & (1 << ($job - 1)) );
    } 

    public static function jobsFromInt($jobsint){
        $html = "";
        if ( $jobsint == 4194303 ) return " All Jobs ";
        //if ( ParserHelper::checkJob(0, $jobsint) ) $html .= "None";
        $var = new FFXIPackageHelper_Variables();

        for ( $j = 1; $j < count($var->jobArray); $j++){
            //OOE jobs - can change when content gets released
            if ( $j >= 16 ) continue;
            if ( ParserHelper::checkJob($j, $jobsint) ) $html .= " [[". $var->jobArray[$j] ."]]";
        }

        if ( $html == "" ) $html = "None";
        return $html;
    }

    private static function getMSB($n){
        if ($n < 1) return 0;
        if ($n == 3) return 16; // Common axes/daggers listed as 3
         
        $r = 0;
        while ($n >>= 1) $r++;
        return $r;
    }

    public static function getSlotLabelFromMSB($msb){
        switch($msb){
            case 0: return "Main";
            case 1: return "Sub";
            case 2: return "Ranged";
            case 3: return "Ammo";
            case 4: return "Head";
            case 5: return "Body";
            case 6: return "Hands";
            case 7: return "Legs";
            case 8: return "Feet";
            case 9: return "Neck";
            case 10: return "Waist";
            case 11: return "Ear";
            case 12: return "Ear";
            case 13: return "Ring";
            case 14: return "Ring";
            case 15: return "Back";
            case 16: return "Main/Sub";
            default: return "None";
        }
    }

    public static function getSlotLabel($n){
        $msb = self::getMSB($n);
        return self:: getSlotLabelFromMSB($msb);
    }

}

?>