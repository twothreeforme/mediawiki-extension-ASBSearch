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

		// print_r($mobName ."-". $mobType ."...");

		if ( ExclusionsHelper::zoneIsBCNM($zoneName) ) $mobName = " [[$mobName]]<sup>(BCNM)</sup> ";
		else if ( $minLvl == $maxLvl ) {
			if ( $maxLvl == 255) $mobName = " {{changes}}[[$mobName]]<sup>(HENM)</sup> ";
			else $mobName = " [[$mobName]]<sup>($maxLvl)</sup> ";
		}
		else if ( $mobChanges == 1) $mobName = " {{changes}}[[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		else $mobName = " [[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		
		if ( $fished == true ) return " " . $mobName . " (fished) ";
		else if ( $mobType == 2 || $mobType == 16 || $mobType == 18 ) return "[NM] " . $mobName;
		
        if ($bcnmChanges == 1) $mobName = " {{changes}}" . $mobName;

		return $mobName;
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
		else return " [[$itemName]] ";
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

		return " [[$zone]] ";
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

    public static function wikiParse($html){
		$context = RequestContext::getMain();
        $title = $context->getTitle();
        $parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$user = RequestContext::getMain()->getUser();
        $parserOptions = new ParserOptions($user);
        $parserOutput = $parser->parse( $html, $title, $parserOptions );

        return $parserOutput->getText();
	}

    public static function checkJob($job, $item){
        return ( $item & (1 << ($job - 1)) );
    } 

    public static function jobsFromInt($jobsint){
        $html = "";
        if ( $jobsint == 4194303 ) return " All Jobs ";
        //if ( ParserHelper::checkJob(0, $jobsint) ) $html .= "None";
        for ( $j = 1; $j < count(self::$jobArray); $j++){
            if ( ParserHelper::checkJob($j, $jobsint) ) $html .= " [[". self::$jobArray[$j] ."]]";
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
            case 11: return "Ear1";
            case 12: return "Ear2";
            case 13: return "Ring1";
            case 14: return "Ring2";
            case 15: return "Back";
            case 16: return "Main/Sub";
            default: return "None";
        }
    }

    public static function getSlotLabel($n){
        $msb = self::getMSB($n);
        return self:: getSlotLabelFromMSB($msb);
    }

    public static function shouldShowMod($mod){
        if ( $mod['name'] == 499 || //ITEM_SUBEFFECT
        $mod['name'] == 431 || //ITEM_ADDEFFECT_TYPE
        $mod['name'] == 501 || //ITEM_ADDEFFECT_CHANCE
        $mod['name'] == 951 || //ITEM_ADDEFFECT_STATUS
        $mod['name'] == 952 || //ITEM_ADDEFFECT_POWER
        $mod['name'] == 953 || //ITEM_ADDEFFECT_DURATION
        $mod['name'] == 1180 //ITEM_ADDEFFECT_OPTION
        ) return false;
        return true;
    }

    public static $jobArray = array(
        0 => "NONE",
        1 => "WAR",
        2 => "MNK",
        3 => "WHM",
        4 => "BLM",
        5 => "RDM",
        6 => "THF",
        7 => "PLD",
        8 => "DRK",
        9 => "BST",
        10 => "BRD",
        11 => "RNG",
        12 => "SAM",
        13 => "NIN",
        14 => "DRG",
        15 => "SMN",
        16 => "BLU",
        17 => "COR",
        18 => "PUP",
        19 => "DNC",
        20 => "SCH",
        21 => "GEO",
        22 => "RUN"
    );



    public static $modArray = array(
        0 => "NONE",
        1 => "DEF",
        2 => "HP",
        3 => "HPP",
        4 => "CONVMPTOHP",
        5 => "MP",
        6 => "MPP",
        7 => "CONVHPTOMP",
        8 => "STR",
        9 => "DEX",
        10 => "VIT",
        11 => "AGI",
        12 => "INT",
        13 => "MND",
        14 => "CHR",
        15 => "FIRE_MEVA",
        16 => "ICE_MEVA",
        17 => "WIND_MEVA",
        18 => "EARTH_MEVA",
        19 => "THUNDER_MEVA",
        20 => "WATER_MEVA",
        21 => "LIGHT_MEVA",
        22 => "DARK_MEVA",
        192 => "FIRE_RES_RANK",
        193 => "ICE_RES_RANK",
        194 => "WIND_RES_RANK",
        195 => "EARTH_RES_RANK",
        196 => "THUNDER_RES_RANK",
        197 => "WATER_RES_RANK",
        198 => "LIGHT_RES_RANK",
        199 => "DARK_RES_RANK",
        23 => "ATT",
        24 => "RATT",
        25 => "ACC",
        26 => "RACC",
        27 => "ENMITY",
        427 => "ENMITY_LOSS_REDUCTION",
        28 => "MATT",
        29 => "MDEF",
        30 => "MACC",
        31 => "MEVA",
        32 => "FIREATT",
        33 => "ICEATT",
        34 => "WINDATT",
        35 => "EARTHATT",
        36 => "THUNDERATT",
        37 => "WATERATT",
        38 => "LIGHTATT",
        39 => "DARKATT",
        40 => "FIREACC",
        41 => "ICEACC",
        42 => "WINDACC",
        43 => "EARTHACC",
        44 => "THUNDERACC",
        45 => "WATERACC",
        46 => "LIGHTACC",
        47 => "DARKACC",
        48 => "WSACC",
        49 => "SLASH_SDT",
        50 => "PIERCE_SDT",
        51 => "IMPACT_SDT",
        52 => "HTH_SDT",
        54 => "FIRE_SDT",
        55 => "ICE_SDT",
        56 => "WIND_SDT",
        57 => "EARTH_SDT",
        58 => "THUNDER_SDT",
        59 => "WATER_SDT",
        60 => "LIGHT_SDT",
        61 => "DARK_SDT",
        62 => "ATTP",
        63 => "DEFP",
        64 => "COMBAT_SKILLUP_RATE",
        65 => "MAGIC_SKILLUP_RATE",
        66 => "RATTP",
        68 => "EVA",
        69 => "RDEF",
        70 => "REVA",
        71 => "MPHEAL",
        72 => "HPHEAL",
        73 => "STORETP",
        80 => "HTH",
        81 => "DAGGER",
        82 => "SWORD",
        83 => "GSWORD",
        84 => "AXE",
        85 => "GAXE",
        86 => "SCYTHE",
        87 => "POLEARM",
        88 => "KATANA",
        89 => "GKATANA",
        90 => "CLUB",
        91 => "STAFF",
        92 => "RAMPART_DURATION",
        93 => "FLEE_DURATION",
        94 => "MEDITATE_DURATION",
        95 => "WARDING_CIRCLE_DURATION",
        96 => "SOULEATER_EFFECT",
        53 => "SOULEATER_EFFECT_II",
        906 => "DESPERATE_BLOWS",
        907 => "STALWART_SOUL",
        97 => "BOOST_EFFECT",
        98 => "CAMOUFLAGE_DURATION",
        101 => "AUTO_MELEE_SKILL",
        102 => "AUTO_RANGED_SKILL",
        103 => "AUTO_MAGIC_SKILL",
        104 => "ARCHERY",
        105 => "MARKSMAN",
        106 => "THROW",
        107 => "GUARD",
        108 => "EVASION",
        109 => "SHIELD",
        110 => "PARRY",
        111 => "DIVINE",
        112 => "HEALING",
        113 => "ENHANCE",
        114 => "ENFEEBLE",
        115 => "ELEM",
        116 => "DARK",
        117 => "SUMMONING",
        118 => "NINJUTSU",
        119 => "SINGING",
        120 => "STRING",
        121 => "WIND",
        122 => "BLUE",
        123 => "GEOMANCY_SKILL",
        124 => "HANDBELL_SKILL",
        1026 => "CHAKRA_MULT",
        1027 => "CHAKRA_REMOVAL",
        125 => "SUPPRESS_OVERLOAD",
        126 => "BP_DAMAGE",
        127 => "FISH",
        128 => "WOOD",
        129 => "SMITH",
        130 => "GOLDSMITH",
        131 => "CLOTH",
        132 => "LEATHER",
        133 => "BONE",
        134 => "ALCHEMY",
        135 => "COOK",
        136 => "SYNERGY",
        137 => "RIDING",
        144 => "ANTIHQ_WOOD",
        145 => "ANTIHQ_SMITH",
        146 => "ANTIHQ_GOLDSMITH",
        147 => "ANTIHQ_CLOTH",
        148 => "ANTIHQ_LEATHER",
        149 => "ANTIHQ_BONE",
        150 => "ANTIHQ_ALCHEMY",
        151 => "ANTIHQ_COOK",
        160 => "DMG",
        161 => "DMGPHYS",
        190 => "DMGPHYS_II",
        162 => "DMGBREATH",
        163 => "DMGMAGIC",
        831 => "DMGMAGIC_II",
        164 => "DMGRANGE",
        387 => "UDMGPHYS",
        388 => "UDMGBREATH",
        389 => "UDMGMAGIC",
        390 => "UDMGRANGE",
        165 => "CRITHITRATE",
        421 => "CRIT_DMG_INCREASE",
        964 => "RANGED_CRIT_DMG_INCREASE",
        166 => "ENEMYCRITRATE",
        908 => "CRIT_DEF_BONUS",
        562 => "MAGIC_CRITHITRATE",
        563 => "MAGIC_CRIT_DMG_INCREASE",
        167 => "HASTE_MAGIC",
        168 => "SPELLINTERRUPT",
        169 => "MOVE",
        972 => "MOUNT_MOVE",
        170 => "FASTCAST",
        407 => "UFASTCAST",
        519 => "CURE_CAST_TIME",
        901 => "ELEMENTAL_CELERITY",
        171 => "DELAY",
        172 => "RANGED_DELAY",
        173 => "MARTIAL_ARTS",
        174 => "SKILLCHAINBONUS",
        175 => "SKILLCHAINDMG",
        978 => "MAX_SWINGS",
        979 => "ADDITIONAL_SWING_CHANCE",
        176 => "FOOD_HPP",
        177 => "FOOD_HP_CAP",
        178 => "FOOD_MPP",
        179 => "FOOD_MP_CAP",
        180 => "FOOD_ATTP",
        181 => "FOOD_ATT_CAP",
        182 => "FOOD_DEFP",
        183 => "FOOD_DEF_CAP",
        184 => "FOOD_ACCP",
        185 => "FOOD_ACC_CAP",
        186 => "FOOD_RATTP",
        187 => "FOOD_RATT_CAP",
        188 => "FOOD_RACCP",
        189 => "FOOD_RACC_CAP",
        99 => "FOOD_MACCP",
        100 => "FOOD_MACC_CAP",
        937 => "FOOD_DURATION",
        224 => "VERMIN_KILLER",
        225 => "BIRD_KILLER",
        226 => "AMORPH_KILLER",
        227 => "LIZARD_KILLER",
        228 => "AQUAN_KILLER",
        229 => "PLANTOID_KILLER",
        230 => "BEAST_KILLER",
        231 => "UNDEAD_KILLER",
        232 => "ARCANA_KILLER",
        233 => "DRAGON_KILLER",
        234 => "DEMON_KILLER",
        235 => "EMPTY_KILLER",
        236 => "HUMANOID_KILLER",
        237 => "LUMINIAN_KILLER",
        238 => "LUMINION_KILLER",
        1178 => "WYRMAL_ABJ_KILLER_EFFECT",
        240 => "SLEEPRES",
        241 => "POISONRES",
        242 => "PARALYZERES",
        243 => "BLINDRES",
        244 => "SILENCERES",
        245 => "VIRUSRES",
        246 => "PETRIFYRES",
        247 => "BINDRES",
        248 => "CURSERES",
        249 => "GRAVITYRES",
        250 => "SLOWRES",
        251 => "STUNRES",
        252 => "CHARMRES",
        253 => "AMNESIARES",
        254 => "LULLABYRES",
        255 => "DEATHRES",
        958 => "STATUSRES",
        200 => "SLEEP_MEVA",
        201 => "POISON_MEVA",
        202 => "PARALYZE_MEVA",
        203 => "BLIND_MEVA",
        204 => "SILENCE_MEVA",
        205 => "VIRUS_MEVA",
        206 => "PETRIFY_MEVA",
        207 => "BIND_MEVA",
        208 => "CURSE_MEVA",
        209 => "GRAVITY_MEVA",
        210 => "SLOW_MEVA",
        211 => "STUN_MEVA",
        212 => "CHARM_MEVA",
        213 => "AMNESIA_MEVA",
        214 => "LULLABY_MEVA",
        215 => "DEATH_MEVA",
        216 => "STATUS_MEVA",
        261 => "SLEEP_IMMUNOBREAK",
        262 => "POISON_IMMUNOBREAK",
        263 => "PARALYZE_IMMUNOBREAK",
        264 => "BLIND_IMMUNOBREAK",
        265 => "SILENCE_IMMUNOBREAK",
        266 => "PETRIFY_IMMUNOBREAK",
        267 => "BIND_IMMUNOBREAK",
        268 => "GRAVITY_IMMUNOBREAK",
        269 => "SLOW_IMMUNOBREAK",
        270 => "ADDLE_IMMUNOBREAK",
        256 => "AFTERMATH",
        257 => "PARALYZE",
        258 => "MIJIN_RERAISE",
        259 => "DUAL_WIELD",
        288 => "DOUBLE_ATTACK",
        1038 => "DOUBLE_ATTACK_DMG",
        289 => "SUBTLE_BLOW",
        973 => "SUBTLE_BLOW_II",
        290 => "ENF_MAG_POTENCY",
        291 => "COUNTER",
        292 => "KICK_ATTACK_RATE",
        293 => "AFFLATUS_SOLACE",
        294 => "AFFLATUS_MISERY",
        295 => "CLEAR_MIND",
        296 => "CONSERVE_MP",
        297 => "ENHANCES_SABOTEUR",
        298 => "STEAL",
        896 => "DESPOIL",
        883 => "PERFECT_DODGE",
        299 => "BLINK",
        300 => "STONESKIN",
        301 => "PHALANX",
        302 => "TRIPLE_ATTACK",
        1039 => "TRIPLE_ATTACK_DMG",
        303 => "TREASURE_HUNTER",
        1048 => "TREASURE_HUNTER_PROC",
        1049 => "TREASURE_HUNTER_CAP",
        304 => "TAME",
        305 => "RECYCLE",
        306 => "ZANSHIN",
        307 => "UTSUSEMI",
        900 => "UTSUSEMI_BONUS",
        308 => "NINJA_TOOL",
        309 => "BLUE_POINTS",
        945 => "BLUE_LEARN_CHANCE",
        316 => "DMG_REFLECT",
        317 => "ROLL_ROGUES",
        318 => "ROLL_GALLANTS",
        319 => "ROLL_CHAOS",
        320 => "ROLL_BEAST",
        321 => "ROLL_CHORAL",
        322 => "ROLL_HUNTERS",
        323 => "ROLL_SAMURAI",
        324 => "ROLL_NINJA",
        325 => "ROLL_DRACHEN",
        326 => "ROLL_EVOKERS",
        327 => "ROLL_MAGUS",
        328 => "ROLL_CORSAIRS",
        329 => "ROLL_PUPPET",
        330 => "ROLL_DANCERS",
        331 => "ROLL_SCHOLARS",
        869 => "ROLL_BOLTERS",
        870 => "ROLL_CASTERS",
        871 => "ROLL_COURSERS",
        872 => "ROLL_BLITZERS",
        873 => "ROLL_TACTICIANS",
        874 => "ROLL_ALLIES",
        875 => "ROLL_MISERS",
        876 => "ROLL_COMPANIONS",
        877 => "ROLL_AVENGERS",
        878 => "ROLL_NATURALISTS",
        879 => "ROLL_RUNEISTS",
        332 => "BUST",
        333 => "FINISHING_MOVES",
        490 => "SAMBA_DURATION",
        491 => "WALTZ_POTENCY",
        492 => "JIG_DURATION",
        493 => "VFLOURISH_MACC",
        494 => "STEP_FINISH",
        403 => "STEP_ACCURACY",
        497 => "WALTZ_DELAY",
        498 => "SAMBA_PDURATION",
        340 => "WIDESCAN",
        420 => "BARRAGE_ACC",
        341 => "ENSPELL",
        342 => "SPIKES",
        343 => "ENSPELL_DMG",
        856 => "ENSPELL_CHANCE",
        344 => "SPIKES_DMG",
        345 => "TP_BONUS",
        1079 => "SPIKES_DMG_BONUS",
        948 => "BERSERK_POTENCY",
        954 => "BERSERK_DURATION",
        955 => "AGGRESSOR_DURATION",
        956 => "DEFENDER_DURATION",
        1045 => "ENHANCES_RESTRAINT",
        1046 => "ENHANCES_BLOOD_RAGE",
        1061 => "ENHANCES_CHIVALRY",
        1062 => "ENHANCES_DIVINE_EMBLEM",
        1063 => "ENHANCES_FEALTY",
        1064 => "ENHANCES_IRON_WILL",
        1065 => "ENHANCES_GUARDIAN",
        1066 => "PALISADE_BLOCK_BONUS",
        1067 => "REPRISAL_BLOCK_BONUS",
        1068 => "REPRISAL_SPIKES_BONUS",
        1069 => "ARCANE_CIRCLE_POTENCY",
        1070 => "ENHANCES_BLOOD_WEAPON",
        1071 => "DARK_MAGIC_CAST",
        1072 => "DARK_MAGIC_DURATION",
        1073 => "ENHANCES_DARK_SEAL",
        1043 => "WYVERN_LVL_BONUS",
        1040 => "AVATAR_LVL_BONUS",
        1041 => "CARBUNCLE_LVL_BONUS",
        1042 => "CAIT_SITH_LVL_BONUS",
        74 => "ENHANCES_MANA_CEDE",
        1078 => "SUMMONING_MAGIC_CAST",
        346 => "PERPETUATION_REDUCTION",
        1179 => "SPIRIT_SPELLCAST_DELAY",
        1044 => "AUTOMATON_LVL_BONUS",
        1025 => "FULL_CIRCLE",
        1028 => "BOLSTER_EFFECT",
        1029 => "LIFE_CYCLE_EFFECT",
        1030 => "AURA_SIZE",
        1004 => "ENHANCES_BATTUTA",
        1005 => "ENHANCES_ELEMENTAL_SFORZO",
        1006 => "ENHANCES_SLEIGHT_OF_SWORD",
        1007 => "ENHANCES_INSPIRATION",
        1008 => "SWORDPLAY",
        1009 => "LIEMENT",
        1010 => "VALIANCE_VALLATION_DURATION",
        1011 => "PFLUG",
        1012 => "VIVACIOUS_PULSE_POTENCY",
        1013 => "AUGMENTS_VIVACIOUS_PULSE",
        1014 => "RAYKE_DURATION",
        1015 => "ODYLLIC_SUBTERFUGE_DURATION",
        1016 => "SWIPE",
        1017 => "LIEMENT_DURATION",
        1018 => "GAMBIT_DURATION",
        1019 => "EMBOLDEN_DURATION",
        1020 => "LIEMENT_EXTENDS_TO_AREA",
        1021 => "INSPIRATION_FAST_CAST",
        1022 => "PARRY_SPIKES",
        1023 => "PARRY_SPIKES_DMG",
        1024 => "SPECIAL_ATTACK_EVASION",
        347 => "FIRE_AFFINITY_DMG",
        348 => "ICE_AFFINITY_DMG",
        349 => "WIND_AFFINITY_DMG",
        350 => "EARTH_AFFINITY_DMG",
        351 => "THUNDER_AFFINITY_DMG",
        352 => "WATER_AFFINITY_DMG",
        353 => "LIGHT_AFFINITY_DMG",
        354 => "DARK_AFFINITY_DMG",
        544 => "FIRE_AFFINITY_ACC",
        545 => "ICE_AFFINITY_ACC",
        546 => "WIND_AFFINITY_ACC",
        547 => "EARTH_AFFINITY_ACC",
        548 => "THUNDER_AFFINITY_ACC",
        549 => "WATER_AFFINITY_ACC",
        550 => "LIGHT_AFFINITY_ACC",
        551 => "DARK_AFFINITY_ACC",
        553 => "FIRE_AFFINITY_PERP",
        554 => "ICE_AFFINITY_PERP",
        555 => "WIND_AFFINITY_PERP",
        556 => "EARTH_AFFINITY_PERP",
        557 => "THUNDER_AFFINITY_PERP",
        558 => "WATER_AFFINITY_PERP",
        559 => "LIGHT_AFFINITY_PERP",
        560 => "DARK_AFFINITY_PERP",
        355 => "ADDS_WEAPONSKILL",
        356 => "ADDS_WEAPONSKILL_DYN",
        357 => "BP_DELAY",
        358 => "STEALTH",
        359 => "RAPID_SHOT",
        360 => "CHARM_TIME",
        361 => "JUMP_TP_BONUS",
        285 => "JUMP_SPIRIT_TP_BONUS",
        362 => "JUMP_ATT_BONUS",
        286 => "JUMP_SOUL_SPIRIT_ATT_BONUS",
        936 => "JUMP_ACC_BONUS",
        888 => "JUMP_DOUBLE_ATTACK",
        363 => "HIGH_JUMP_ENMITY_REDUCTION",
        282 => "ENHANCES_STRAFE",
        281 => "ENHANCES_SPIRIT_LINK",
        364 => "REWARD_HP_BONUS",
        365 => "SNAP_SHOT",
        287 => "DMG_RATING",
        366 => "MAIN_DMG_RATING",
        367 => "SUB_DMG_RATING",
        376 => "RANGED_DMG_RATING",
        377 => "MAIN_DMG_RANK",
        378 => "SUB_DMG_RANK",
        379 => "RANGED_DMG_RANK",
        368 => "REGAIN",
        369 => "REFRESH",
        370 => "REGEN",
        371 => "AVATAR_PERPETUATION",
        372 => "WEATHER_REDUCTION",
        373 => "DAY_REDUCTION",
        374 => "CURE_POTENCY",
        260 => "CURE_POTENCY_II",
        375 => "CURE_POTENCY_RCVD",
        1051 => "CURE_POTENCY_BONUS",
        380 => "DELAYP",
        381 => "RANGED_DELAYP",
        382 => "EXP_BONUS",
        383 => "HASTE_ABILITY",
        384 => "HASTE_GEAR",
        385 => "SHIELD_BASH",
        386 => "KICK_DMG",
        391 => "CHARM_CHANCE",
        392 => "WEAPON_BASH",
        393 => "BLACK_MAGIC_COST",
        394 => "WHITE_MAGIC_COST",
        395 => "BLACK_MAGIC_CAST",
        396 => "WHITE_MAGIC_CAST",
        397 => "BLACK_MAGIC_RECAST",
        398 => "WHITE_MAGIC_RECAST",
        399 => "ALACRITY_CELERITY_EFFECT",
        334 => "LIGHT_ARTS_EFFECT",
        335 => "DARK_ARTS_EFFECT",
        336 => "LIGHT_ARTS_SKILL",
        337 => "DARK_ARTS_SKILL",
        338 => "LIGHT_ARTS_REGEN",
        339 => "REGEN_DURATION",
        478 => "HELIX_EFFECT",
        477 => "HELIX_DURATION",
        400 => "STORMSURGE_EFFECT",
        401 => "SUBLIMATION_BONUS",
        489 => "GRIMOIRE_SPELLCASTING",
        402 => "WYVERN_BREATH",
        284 => "UNCAPPED_WYVERN_BREATH",
        404 => "REGEN_DOWN",
        405 => "REFRESH_DOWN",
        406 => "REGAIN_DOWN",
        311 => "MAGIC_DAMAGE",
        408 => "DA_DOUBLE_DMG_RATE",
        409 => "TA_TRIPLE_DMG_RATE",
        410 => "ZANSHIN_DOUBLE_DAMAGE",
        479 => "RAPID_SHOT_DOUBLE_DAMAGE",
        480 => "ABSORB_DMG_CHANCE",
        481 => "EXTRA_DUAL_WIELD_ATTACK",
        482 => "EXTRA_KICK_ATTACK",
        415 => "SAMBA_DOUBLE_DAMAGE",
        416 => "NULL_PHYSICAL_DAMAGE",
        417 => "QUICK_DRAW_TRIPLE_DAMAGE",
        418 => "BAR_ELEMENT_NULL_CHANCE",
        419 => "GRIMOIRE_INSTANT_CAST",
        543 => "COUNTERSTANCE_EFFECT",
        552 => "DODGE_EFFECT",
        561 => "FOCUS_EFFECT",
        835 => "MUG_EFFECT",
        884 => "ACC_COLLAB_EFFECT",
        885 => "HIDE_DURATION",
        897 => "GILFINDER",
        836 => "REVERSE_FLOURISH_EFFECT",
        837 => "SENTINEL_EFFECT",
        838 => "REGEN_MULTIPLIER",
        1003 => "AUGMENT_SONG_STAT",
        422 => "DOUBLE_SHOT_RATE",
        423 => "VELOCITY_SNAPSHOT_BONUS",
        424 => "VELOCITY_RATT_BONUS",
        425 => "SHADOW_BIND_EXT",
        426 => "ABSORB_PHYSDMG_TO_MP",
        485 => "SHIELD_MASTERY_TP",
        428 => "PERFECT_COUNTER_ATT",
        429 => "FOOTWORK_ATT_BONUS",
        433 => "MINNE_EFFECT",
        434 => "MINUET_EFFECT",
        435 => "PAEON_EFFECT",
        436 => "REQUIEM_EFFECT",
        437 => "THRENODY_EFFECT",
        438 => "MADRIGAL_EFFECT",
        439 => "MAMBO_EFFECT",
        440 => "LULLABY_EFFECT",
        441 => "ETUDE_EFFECT",
        442 => "BALLAD_EFFECT",
        443 => "MARCH_EFFECT",
        444 => "FINALE_EFFECT",
        445 => "CAROL_EFFECT",
        446 => "MAZURKA_EFFECT",
        447 => "ELEGY_EFFECT",
        448 => "PRELUDE_EFFECT",
        449 => "HYMNUS_EFFECT",
        450 => "VIRELAI_EFFECT",
        451 => "SCHERZO_EFFECT",
        452 => "ALL_SONGS_EFFECT",
        453 => "MAXIMUM_SONGS_BONUS",
        454 => "SONG_DURATION_BONUS",
        455 => "SONG_SPELLCASTING_TIME",
        630 => "AVATARS_FAVOR_ENHANCE",
        411 => "QUICK_DRAW_DMG",
        191 => "QUICK_DRAW_MACC",
        430 => "QUAD_ATTACK",
        432 => "ENSPELL_DMG_BONUS",
        459 => "FIRE_ABSORB",
        460 => "ICE_ABSORB",
        461 => "WIND_ABSORB",
        462 => "EARTH_ABSORB",
        463 => "LTNG_ABSORB",
        464 => "WATER_ABSORB",
        465 => "LIGHT_ABSORB",
        466 => "DARK_ABSORB",
        467 => "FIRE_NULL",
        468 => "ICE_NULL",
        469 => "WIND_NULL",
        470 => "EARTH_NULL",
        471 => "LTNG_NULL",
        472 => "WATER_NULL",
        473 => "LIGHT_NULL",
        474 => "DARK_NULL",
        475 => "MAGIC_ABSORB",
        476 => "MAGIC_NULL",
        239 => "NULL_RANGED_DAMAGE",
        512 => "PHYS_ABSORB",
        516 => "ABSORB_DMG_TO_MP",
        483 => "WARCRY_DURATION",
        484 => "AUSPICE_EFFECT",
        486 => "TACTICAL_PARRY",
        487 => "MAG_BURST_BONUS",
        488 => "INHIBIT_TP",
        496 => "GOV_CLEARS",
        456 => "RERAISE_I",
        457 => "RERAISE_II",
        458 => "RERAISE_III",
        431 => "ITEM_ADDEFFECT_TYPE",
        499 => "ITEM_SUBEFFECT",
        500 => "ITEM_ADDEFFECT_DMG",
        501 => "ITEM_ADDEFFECT_CHANCE",
        950 => "ITEM_ADDEFFECT_ELEMENT",
        951 => "ITEM_ADDEFFECT_STATUS",
        952 => "ITEM_ADDEFFECT_POWER",
        953 => "ITEM_ADDEFFECT_DURATION",
        1180 => "ITEM_ADDEFFECT_OPTION",
        503 => "FERAL_HOWL_DURATION",
        504 => "MANEUVER_BONUS",
        505 => "OVERLOAD_THRESH",
        847 => "BURDEN_DECAY",
        853 => "REPAIR_EFFECT",
        854 => "REPAIR_POTENCY",
        855 => "PREVENT_OVERLOAD",
        506 => "EXTRA_DMG_CHANCE",
        507 => "OCC_DO_EXTRA_DMG",
        863 => "REM_OCC_DO_DOUBLE_DMG",
        864 => "REM_OCC_DO_TRIPLE_DMG",
        867 => "REM_OCC_DO_DOUBLE_DMG_RANGED",
        868 => "REM_OCC_DO_TRIPLE_DMG_RANGED",
        865 => "MYTHIC_OCC_ATT_TWICE",
        866 => "MYTHIC_OCC_ATT_THRICE",
        412 => "EAT_RAW_FISH",
        413 => "EAT_RAW_MEAT",
        67 => "ENHANCES_CURSNA_RCVD",
        310 => "ENHANCES_CURSNA",
        495 => "ENHANCES_HOLYWATER",
        977 => "ENHANCES_PROT_SHELL_RCVD",
        1050 => "ENHANCES_PROT_RCVD",
        414 => "RETALIATION",
        508 => "THIRD_EYE_COUNTER_RATE",
        839 => "THIRD_EYE_ANTICIPATE_RATE",
        509 => "CLAMMING_IMPROVED_RESULTS",
        510 => "CLAMMING_REDUCED_INCIDENTS",
        511 => "CHOCOBO_RIDING_TIME",
        513 => "HARVESTING_RESULT",
        514 => "LOGGING_RESULT",
        515 => "MINING_RESULT",
        517 => "EGGHELM",
        518 => "SHIELDBLOCKRATE",
        312 => "SCAVENGE_EFFECT",
        313 => "DIA_DOT",
        314 => "SHARPSHOT",
        315 => "ENH_DRAIN_ASPIR",
        830 => "SNEAK_ATK_DEX",
        520 => "TRICK_ATK_AGI",
        522 => "NIN_NUKE_BONUS",
        911 => "DAKEN",
        523 => "AMMO_SWING",
        826 => "AMMO_SWING_TYPE",
        528 => "ROLL_RANGE",
        881 => "PHANTOM_ROLL",
        882 => "PHANTOM_DURATION",
        529 => "ENHANCES_REFRESH",
        530 => "NO_SPELL_MP_DEPLETION",
        531 => "FORCE_FIRE_DWBONUS",
        532 => "FORCE_ICE_DWBONUS",
        533 => "FORCE_WIND_DWBONUS",
        534 => "FORCE_EARTH_DWBONUS",
        535 => "FORCE_LIGHTNING_DWBONUS",
        536 => "FORCE_WATER_DWBONUS",
        537 => "FORCE_LIGHT_DWBONUS",
        538 => "FORCE_DARK_DWBONUS",
        539 => "STONESKIN_BONUS_HP",
        540 => "ENHANCES_ELEMENTAL_SIPHON",
        541 => "BP_DELAY_II",
        542 => "JOB_BONUS_CHANCE",
        565 => "DAY_NUKE_BONUS",
        566 => "IRIDESCENCE",
        567 => "BARSPELL_AMOUNT",
        827 => "BARSPELL_MDEF_BONUS",
        568 => "RAPTURE_AMOUNT",
        569 => "EBULLIENCE_AMOUNT",
        829 => "WYVERN_EFFECTIVE_BREATH",
        283 => "ENHANCE_DEEP_BREATHING",
        832 => "AQUAVEIL_COUNT",
        833 => "SONG_RECAST_DELAY",
        890 => "ENH_MAGIC_DURATION",
        891 => "ENHANCES_COURSERS_ROLL",
        892 => "ENHANCES_CASTERS_ROLL",
        893 => "ENHANCES_BLITZERS_ROLL",
        894 => "ENHANCES_ALLIES_ROLL",
        895 => "ENHANCES_TACTICIANS_ROLL",
        902 => "OCCULT_ACUMEN",
        909 => "QUICK_MAGIC",
        842 => "AUTO_DECISION_DELAY",
        843 => "AUTO_SHIELD_BASH_DELAY",
        844 => "AUTO_MAGIC_DELAY",
        845 => "AUTO_HEALING_DELAY",
        846 => "AUTO_HEALING_THRESHOLD",
        848 => "AUTO_SHIELD_BASH_SLOW",
        849 => "AUTO_TP_EFFICIENCY",
        850 => "AUTO_SCAN_RESISTS",
        938 => "AUTO_STEAM_JACKET",
        939 => "AUTO_STEAM_JACKET_REDUCTION",
        940 => "AUTO_SCHURZEN",
        941 => "AUTO_EQUALIZER",
        942 => "AUTO_PERFORMANCE_BOOST",
        943 => "AUTO_ANALYZER",
        1001 => "AUTO_RANGED_DELAY",
        1002 => "AUTO_RANGED_DAMAGEP",
        521 => "AUGMENTS_ABSORB",
        524 => "AOE_NA",
        525 => "AUGMENTS_CONVERT",
        526 => "AUGMENTS_SA",
        527 => "AUGMENTS_TA",
        502 => "AUGMENTS_FEINT",
        886 => "AUGMENTS_ASSASSINS_CHARGE",
        887 => "AUGMENTS_AMBUSH",
        889 => "AUGMENTS_AURA_STEAL",
        912 => "AUGMENTS_CONSPIRATOR",
        564 => "JUG_LEVEL_RANGE",
        828 => "FORCE_JUMP_CRIT",
        834 => "QUICK_DRAW_DMG_PERCENT",
        851 => "SYNTH_SUCCESS",
        852 => "SYNTH_SKILL_GAIN",
        861 => "SYNTH_FAIL_RATE",
        862 => "SYNTH_HQ_RATE",
        916 => "DESYNTH_SUCCESS",
        917 => "SYNTH_FAIL_RATE_FIRE",
        918 => "SYNTH_FAIL_RATE_ICE",
        919 => "SYNTH_FAIL_RATE_WIND",
        920 => "SYNTH_FAIL_RATE_EARTH",
        921 => "SYNTH_FAIL_RATE_LIGHTNING",
        922 => "SYNTH_FAIL_RATE_WATER",
        923 => "SYNTH_FAIL_RATE_LIGHT",
        924 => "SYNTH_FAIL_RATE_DARK",
        925 => "SYNTH_FAIL_RATE_WOOD",
        926 => "SYNTH_FAIL_RATE_SMITH",
        927 => "SYNTH_FAIL_RATE_GOLDSMITH",
        928 => "SYNTH_FAIL_RATE_CLOTH",
        929 => "SYNTH_FAIL_RATE_LEATHER",
        930 => "SYNTH_FAIL_RATE_BONE",
        931 => "SYNTH_FAIL_RATE_ALCHEMY",
        932 => "SYNTH_FAIL_RATE_COOK",
        570 => "WEAPONSKILL_DAMAGE_BASE",
        840 => "ALL_WSDMG_ALL_HITS",
        841 => "ALL_WSDMG_FIRST_HIT",
        949 => "WS_NO_DEPLETE",
        963 => "INQUARTATA",
        980 => "WS_STR_BONUS",
        957 => "WS_DEX_BONUS",
        981 => "WS_VIT_BONUS",
        982 => "WS_AGI_BONUS",
        983 => "WS_INT_BONUS",
        984 => "WS_MND_BONUS",
        985 => "WS_CHR_BONUS",
        990 => "PET_ATK_DEF",
        991 => "PET_ACC_EVA",
        992 => "PET_MAB_MDB",
        993 => "PET_MACC_MEVA",
        994 => "PET_ATTR_BONUS",
        995 => "PET_TP_BONUS",
        857 => "HOLY_CIRCLE_DURATION",
        858 => "ARCANE_CIRCLE_DURATION",
        859 => "ANCIENT_CIRCLE_DURATION",
        860 => "CURE2MP_PERCENT",
        910 => "DIVINE_BENISON",
        880 => "SAVETP",
        898 => "SMITE",
        899 => "TACTICAL_GUARD",
        976 => "GUARD_PERCENT",
        1047 => "COUNTER_DAMAGE",
        903 => "FENCER_TP_BONUS",
        904 => "FENCER_CRITHITRATE",
        905 => "SHIELD_DEF_BONUS",
        946 => "SNEAK_DURATION",
        947 => "INVISIBLE_DURATION",
        959 => "CARDINAL_CHANT",
        960 => "INDI_DURATION",
        961 => "GEOMANCY_BONUS",
        962 => "WIDENED_COMPASS",
        968 => "MENDING_HALATION",
        969 => "RADIAL_ARCANA",
        970 => "CURATIVE_RECANTATION",
        971 => "PRIMEVAL_ZEAL",
        965 => "COVER_TO_MP",
        966 => "COVER_MAGIC_AND_RANGED",
        967 => "COVER_DURATION",
        974 => "WYVERN_SUBJOB_TRAITS",
        975 => "GARDENING_WILT_BONUS",
        986 => "WYVERN_BREATH_MACC",
        989 => "REGEN_BONUS",
        997 => "SUPERIOR_LEVEL",
        996 => "ONE_HOUR_RECAST",
        998 => "DREAD_SPIKES_EFFECT",
        152 => "PENGUIN_RING_EFFECT",
        153 => "ALBATROSS_RING_EFFECT",
        154 => "PELICAN_RING_EFFECT",
        155 => "FISHING_SKILL_GAIN",
        913 => "BLOOD_BOON",
        914 => "EXPERIENCE_RETAINED",
        915 => "CAPACITY_BONUS",
        933 => "CONQUEST_BONUS",
        934 => "CONQUEST_REGION_BONUS",
        935 => "CAMPAIGN_BONUS",
        944 => "CONSERVE_TP",
        987 => "AUTO_ELEM_CAPACITY",
        988 => "MAX_FINISHING_MOVE_BONUS",
        999 => "TRIPLE_SHOT_RATE",
        1000 => "NINJUTSU_DURATION",
        1031 => "AUGMENT_CONSERVE_MP",
        1032 => "AUGMENT_COMPOSURE",
        1033 => "AUGMENT_DAMAGE_HP",
        1034 => "AUGMENT_DAMAGE_PET_HP",
        1035 => "AUGMENT_BLOOD_BOON",
        1036 => "AUGMENT_BLU_MAGIC",
        1037 => "GEOMANCY_MP_NO_DEPLETE",
        1052 => "SIC_READY_RECAST",
        1053 => "TRUE_SHOT_EFFECT",
        1054 => "DEAD_AIM_EFFECT",
        1055 => "THIRD_EYE_BONUS",
        1056 => "WYVERN_ATTRIBUTE_DA",
        1057 => "DRAGOON_BREATH_RECAST",
        1058 => "BLUE_JOB_TRAIT_BONUS",
        1059 => "BLUE_MAGIC_EFFECT",
        1060 => "QUICK_DRAW_RECAST",
        1074 => "DIG_BYPASS_FATIGUE",
        1075 => "BREATH_DMG_DEALT",
        1138 => "SLEEPRESBUILD",
        1139 => "POISONRESBUILD",
        1140 => "PARALYZERESBUILD",
        1141 => "BLINDRESBUILD",
        1142 => "SILENCERESBUILD",
        1143 => "VIRUSRESBUILD",
        1144 => "PETRIFYRESBUILD",
        1145 => "BINDRESBUILD",
        1146 => "CURSERESBUILD",
        1147 => "GRAVITYRESBUILD",
        1148 => "SLOWRESBUILD",
        1149 => "STUNRESBUILD",
        1150 => "CHARMRESBUILD",
        1151 => "AMNESIARESBUILD",
        1152 => "LULLABYRESBUILD",
        1153 => "DEATHRESBUILD",
        1154 => "PET_DMG_TAKEN_PHYSICAL",
        1155 => "PET_DMG_TAKEN_MAGICAL",
        1156 => "PET_DMG_TAKEN_BREATH",
        1158 => "FIRE_EEM",
        1159 => "ICE_EEM",
        1160 => "WIND_EEM",
        1161 => "EARTH_EEM",
        1162 => "THUNDER_EEM",
        1163 => "WATER_EEM",
        1164 => "LIGHT_EEM",
        1165 => "DARK_EEM",
        1166 => "TAME_SUCCESS_RATE",
        1167 => "RAMPART_MAGIC_SHIELD",
        1168 => "CRITHITRATE_SLOT",
        1169 => "ATT_SLOT",
        1170 => "UDMG",
        2000 => "TANDEM_STRIKE",
        2001 => "TANDEM_BLOW",
        2002 => "INVISIBLE_EQUIP_BOOST",
        2003 => "SNEAK_EQUIP_BOOST"
    );
}

?>