<?php

//use Wikimedia\Rdbms\DatabaseFactory;
//use MediaWiki\MediaWikiServices;

//class DBConnection {
class DatabaseQueryWrapper {

    private $database; 

    public function __construct() {
        $this->database = new DatabaseConnection();
    }

    private function exclude_MOBGROUPS_OOE(Array $query){
        //$query[] = "mob_groups.name NOT LIKE '%_G'";
        array_push($query, "mob_groups.name NOT LIKE '%!_G' ESCAPE '!'");
        foreach( ExclusionsHelper::$mobs as $mob) {
            //$query[] = "mob_groups.name NOT LIKE '" . $mob . "'";
            array_push($query, "mob_groups.name NOT LIKE '" . $mob . "'"); 
        }
        return $query;
    }

    private function exclude_MOBGROUPS_Fished(Array $query){
        $query[] = "mob_groups.name NOT LIKE '%fished%'";
        return $query;
    }

    private function exclude_ZONES_OOE(Array $query){
        foreach( ExclusionsHelper::$zones as $zone) {
            $zone = ParserHelper::replaceSpaces($zone);
            array_push($query, "zone_settings.name NOT LIKE '" . $zone . "'"); 
        }
        return $query;
    }


    private function openASBSearchConnection() { return $this->database->openConnection("ASB_Data"); }
    private function openEquipsetsConnection() { return $this->database->openConnection("Equipsets"); }

    public function getHitCounter($tab) {
		$dbr = $this->openASBSearchConnection();
		$tabHitCounter = $dbr->newSelectQueryBuilder()
			->select( [ 'hitcount' ] )
			->from( 'search_counters' )
			->where( [ "search_counters.page LIKE '%$tab%'" ] )
            ->fetchResultSet();

        foreach($tabHitCounter as $row){
            return $row->hitcount;
        }
        return null;
    }

    public function incrementHitCounter($tab) {
        $dbw = $this->openASBSearchConnection();

        return $dbw->update(
            'search_counters',
            [
                'hitcount = hitcount + 1'
            ],
            [
                'page' => $tab,
            ],
            __METHOD__
        );
    }

    function getWeatherHex($arr, $vanaDay){
        $hexweatherdata =  $arr[(($vanaDay * 2)  + 1 )] . $arr[($vanaDay * 2) ];
        return $hexweatherdata;
    }

    function getLastWeatherHex($arr, $w_vanaDate){
        $hexweatherdata = 0;
        do {
            $w_vanaDate = $w_vanaDate - 1;
            $hexweatherdata = $this->getWeatherHex($arr,$w_vanaDate);
        }while ( $hexweatherdata == 0000 );
        return $hexweatherdata;
    }

    function getNextWeatherHex($arr, $w_vanaDate){
        $hexweatherdata = 0;
        do {
            $w_vanaDate = $w_vanaDate + 1;
            $hexweatherdata = $this->getWeatherHex($arr, $w_vanaDate);
        }while ( $hexweatherdata == 0000 );
        return $hexweatherdata;
    }

    function convertHexToSplitStrings($hex){
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



    function getZoneList() {
		$dbr = $this->openASBSearchConnection();
		$zoneList = $dbr->newSelectQueryBuilder()
			->select( [ 'zoneid, name' ] )
			->from( 'zone_settings' )
			->fetchResultSet();

/*         $list = array();
        foreach($zoneList as $row){
            $list[$row->zoneid] = $row->name;
            //print_r($row->name);
        }
        return $list; */
        return $zoneList;
    }

    function getZoneListFishing() {
		$dbr = $this->openASBSearchConnection();
		$zoneList = $dbr->newSelectQueryBuilder()
			->select( [ 'fishing_zone.zoneid, fishing_zone.name' ] )
			->from( 'fishing_zone' )
            ->join( 'fishing_area', null, 'fishing_area.zoneid=fishing_zone.zoneid')
			->fetchResultSet();

        return $zoneList;
    }


    function getZoneWeather($zone, $numberOfDays ) {
		$dbr = $this->openASBSearchConnection();
        $query = "zone_weather.zone = $zone";
		$weather = $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'zone_weather' )
			->where( $query )
			->fetchResultSet(); 
        
        foreach ( $weather as $row ) {
            $bin = unpack("H*",$row->weather);        
            $arr = str_split($bin[1], 2);
                        
            $m_vanaDate = intval(((floor(microtime(true) ) - 1009810800) / 3456)) % 2160 ;

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
            $hexweatherdata = $this->getWeatherHex($arr, $m_vanaDate);
            
            if ( $hexweatherdata == 0 ){
                $hexweatherdata = $this->getLastWeatherHex($arr, $m_vanaDate);
            }


            do {
            /*         if ( $hexweatherdata == 0000) {
                        do {
                            $w_vanaDate = $w_vanaDate + 1;
                            //print_r("date: " . $w_vanaDate . "<br/>");
                            $hexweatherdata = $this->getWeatherHex($arr, $w_vanaDate);
                        }while ( $hexweatherdata == 0000 );
                    } */

                $split = $this->convertHexToSplitStrings($hexweatherdata);

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
                            $weatherType = "Fog";
                            break;
                        case 4;
                            $weatherType = "{{Fire|Weather}} Hot Spell";
                            break;
                        case 5;
                            $weatherType = "{{Fire|Double Weather}} Heat Wave";
                            break;
                        case 6;
                            $weatherType = "{{Water|Weather}} Rain";
                            break;
                        case 7;
                            $weatherType = "{{Water|Double Weather}} Squalls";
                            break;
                        case 8;
                            $weatherType = "{{Earth|Weather}} Dust Storm";
                            break;
                        case 9;
                            $weatherType = "{{Earth|Double Weather}} Sand Storm";
                            break;
                        case 10;
                            $weatherType = "{{Wind|Weather}} Wind";
                            break;
                        case 11;
                            $weatherType = "{{Wind|Double Weather}} Gales";
                            break;
                        case 12;
                            $weatherType = "{{Ice|Weather}} Snow";
                            break;
                        case 13;
                            $weatherType = "{{Ice|Double Weather}} Blizzard";
                            break;
                        case 14;
                            $weatherType = "{{Lightning|Weather}} Thunder";
                            break;
                        case 15;
                            $weatherType = "{{Lightning|Double Weather}} Thunderstorms";
                            break;
                        case 16;
                            $weatherType = "{{Light|Weather}} Auroras";
                            break;
                        case 17;
                            $weatherType = "{{Light|Double Weather}} Stellar Glare";
                            break;
                        case 18;
                            $weatherType = "{{Dark|Weather}} Gloom";
                            break;
                        case 19;
                            $weatherType = "{{Dark|Double Weather}} Darkness";
                            break;
                        default:
                            break;
                    }		

                    //array_push( $weatherArray, [$ncr => $weatherType]);
                    $dayArray[$ncr] = $weatherType;
                }

            if ( $dayArray["normal"] == "None" && $dayArray["common"] == "None" && $dayArray["rare"] == "None") {
                $dayArray["normal"] = "";
                $dayArray["common"] = "No Change";
                $dayArray["rare"] = "";
            }

            $weatherArray[$w_vanaDate - $m_vanaDate] = $dayArray;
            // $dayUpdate = $dayUpdate + 1;
            $hexweatherdata = 0;
            $w_vanaDate = $w_vanaDate + 1;
            $hexweatherdata = $this->getWeatherHex($arr, $w_vanaDate);
            //print_r(count($weatherArray));
            }while( count($weatherArray) < 16 );

            //$weatherArray = array("normal"=>bindec($split[0]), "common"=>bindec($split[1]), "rare"=>bindec($split[2]));
            //print_r($weatherArray);
            return $weatherArray;
        }

        return null;
	}

    
    public function getZoneForecastFromDB($zone){
        $dbr = $this->openASBSearchConnection();
        $query = "zone_weather.zone = $zone";
		return $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'zone_weather' )
			->where( $query )
			->fetchResultSet(); 
    }

    public function getForecastFromDB() {
        $dbr =  $this->openASBSearchConnection();
        // $query = "zone_weather.zone = $zone";
		return $dbr->newSelectQueryBuilder()
			->select( [ 'name', 'zoneid', 'weather' ] )
			->from( 'zone_weather' )
            ->join( 'zone_settings', null, 'zone_weather.zone=zone_settings.zoneid' )
			// ->where( $query )
			->fetchResultSet();
    }

    public function getWeather($forDiggersPage, $forRetail = false){
        $forDiggersPage ? $forDiggersPage : false;

        $dbr = new DatabaseQueryWrapper();
        $allZonesWeather = $dbr->getForecastFromDB();

        $result = [ ];
        foreach( $allZonesWeather as $row ){
            //Filter zones for those in Era
            if ( $forRetail == true ) $temp = ParserHelper::zone_forList($row->name);
            else $temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) || ExclusionsHelper::zoneIsTown($temp)) { continue; }

            $dayCount = 30;
            //check if on the diggers special page
            //should only include the weather for the zones listed in ExclusionsHelper::$diggingRelevantZones
            if ( $forDiggersPage == true) {
                if ( !array_key_exists($row->zoneid, ExclusionsHelper::$diggingRelevantZones) ) continue;
                $dayCount = 4;
            }

            // discount any zones where weather is not relevant and should be excluded
            if ( array_key_exists($row->zoneid, ExclusionsHelper::$weatherZones) ) continue;

            //Start stripping out weather details
            //Should occur for each relevant zone
            $zoneWeatherArray = ParserHelper::createCompleteWeatherArrayForZone($row->weather, $dayCount);
            // print_r("<br>". $temp . "<br>");
            // print_r($zoneWeatherArray);

            $weatherdetails = array(
                'name' => $temp,
                'pagelinkname' => $row->name,
				'weather' => $zoneWeatherArray,
                'id' => $row->zoneid
            );

			$result[] = $weatherdetails;
            //print_r("<br />" . $row->zoneid . " " . $row->name);
        }

        if ( $forDiggersPage == false ) {
            $allzones= array(
                'name' => ' ** Search All Zones ** ',
                'pagelinkname' => 'searchallzones',
                'weather' => NULL,
                'id' => NULL
            );
            $result[] = $allzones;
        }

        return $result;
    }


    public function getDropRates($queryData){

        $queryLimit = $queryData[0];
        $mobNameSearch = $queryData[1];
        $itemNameSearch = $queryData[2];
        $zoneNameSearch = $queryData[3];
        //$showBCNMdrops = intval($queryData[4]);
        $excludeNMs = intval($queryData[5]);
        $levelRangeMIN = intval($queryData[6]);
        $levelRangeMAX = intval($queryData[7]);
        $includeSteal = intval($queryData[9]);
        $includeFished = intval($queryData[10]);

		$mobNameSearch = ParserHelper::replaceSpaces($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$mobNameSearch = ParserHelper::replaceApostrophe($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"mob_droplist.dropid != 0 ",
            //"mob_droplist.dropType != 4",  // removing DESPOIL - as its OOE
			"( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')",
			//"mob_groups.content_tag IS NULL ",
		];

        $query = $this->exclude_MOBGROUPS_OOE($query);

        if ( $mobNameSearch !=  '' ) {
            array_push($query, "mob_groups.name LIKE '%$mobNameSearch%'");
            //wfDebugLog( 'ASBSearch', get_called_class() . ":" . json_encode($query) );
            
		}

        if ( $includeFished == 0 ){ $query = $this->exclude_MOBGROUPS_fished($query); }


        if ( $itemNameSearch !=  '' ) {
			array_push($query, "item_basic.name LIKE '%$itemNameSearch%' OR item_basic.sortname LIKE '%$itemNameSearch%'");
		}   
			//up_property = 'enotifwatchlistpages'
		if ( $zoneNameSearch !=  'searchallzones' ) {
			$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
			//$str = "zone_settings.name => $zoneNameSearch';
			array_push($query, "zone_settings.name = '$zoneNameSearch'");
		}
		if ( $excludeNMs == 1) {
			array_push($query, "mob_pools.mobType != 2");
			array_push($query, "mob_pools.mobType != 16");
			array_push($query, "mob_pools.mobType != 18");
		}
		if ( $levelRangeMIN > 0){
			array_push($query, "mob_groups.minLevel >= '$levelRangeMIN'");
		}
		if ( $levelRangeMAX > 0){
			array_push($query, "mob_groups.maxLevel <= '$levelRangeMAX'");
		}
        if ( $includeSteal == 1 ){
			array_push($query, "mob_droplist.dropType <= 2"); // steal = 2
		}
        else array_push($query, "mob_droplist.dropType <= 1"); // all other drops = 0

		$dbr = $this->openASBSearchConnection();
		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'mob_droplist.itemRate',
						'mob_droplist.dropType',
						'mob_droplist.groupId',
						'mob_droplist.groupRate',
						'zone_settings.name AS zoneName',
						'mob_groups.name AS mobName',
						'mob_groups.minLevel AS mobMinLevel',
						'mob_groups.maxLevel AS mobMaxLevel',
                        'mob_groups.dropid',
						'item_basic.name AS itemName', 
						//'item_basic.sortname AS itemSortName',
						'mob_groups.changes_tag AS mobChanges',
						'item_basic.changes_tag AS itemChanges',
						'mob_droplist.changes_tag AS dropChanges',
						'mob_pools.mobType',
                        'mob_pools.aggro',
                        'mob_pools.true_detection',
                        'mob_family_system.superFamily',
                        'mob_family_system.ecosystem',
                        'mob_family_system.detects',
						] )
			->from( 'mob_droplist' )
			->join( 'mob_groups', null, 'mob_groups.dropid=mob_droplist.dropid' )
			->join( 'item_basic', null, 'item_basic.itemid=mob_droplist.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
            ->join( 'mob_family_system', null, 'mob_family_system.familyID=mob_pools.familyid')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			->limit( $queryLimit)
			->fetchResultSet(); 
	}


    /**
     * Used mainly for the 'Page Directs' calls. Not used with ASBSearch. Default exclusions not applied in this search.
     * @param string $mobname *required
     * @return fetchResultSet array of rows for SQL results
     */
    public function getMobDropRates(string $mobname){
        $mobNameSearch = ParserHelper::replaceSpaces($mobname);
       
        $query = [ 
                    "mob_droplist.dropid != 0 ",
                    //"mob_droplist.dropType != 4",  // removing DESPOIL - as its OOE
                    "( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')",
                    "mob_groups.name LIKE '%$mobNameSearch%'",
                ];

        $query = $this->exclude_MOBGROUPS_OOE($query);

        $dbr = $this->openASBSearchConnection();
		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'mob_droplist.itemRate',
						'mob_droplist.dropType',
						'mob_droplist.groupId',
						'mob_droplist.groupRate',
						'zone_settings.name AS zoneName',
						'mob_groups.name AS mobName',
						'mob_groups.minLevel AS mobMinLevel',
						'mob_groups.maxLevel AS mobMaxLevel',
						'item_basic.name AS itemName', 
						//'item_basic.sortname AS itemSortName',
						'mob_groups.changes_tag AS mobChanges',
						'item_basic.changes_tag AS itemChanges',
						'mob_droplist.changes_tag AS dropChanges',
						'mob_pools.mobType',
                        'mob_pools.aggro',
                        'mob_pools.true_detection',
                        // 'mob_family_system.superFamily',
                        // 'mob_family_system.ecosystem',
                        'mob_family_system.detects',
						] )
			->from( 'mob_droplist' )
			->join( 'mob_groups', null, 'mob_groups.dropid=mob_droplist.dropId' )
			->join( 'item_basic', null, 'item_basic.itemid=mob_droplist.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
            ->join( 'mob_family_system', null, 'mob_family_system.familyID=mob_pools.familyid')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			//->limit( $queryLimit )
			->fetchResultSet(); 
    }

    public function getBCNMCrateRates($queryData){
        $bcnmNameSearch = $queryData[1];
        $itemNameSearch = $queryData[2];
        $zoneNameSearch = $queryData[3];

		$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
		//if ( $zoneNameSearch != 'searchallzones' )

		if ( !ExclusionsHelper::zoneIsBCNM($zoneNameSearch) && $zoneNameSearch != 'searchallzones' ) return;

		//if ( gettype($itemNameSearch) ==  )
		//print_r(gettype($itemNameSearch));

		$bcnmNameSearch = ParserHelper::replaceSpaces($bcnmNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$bcnmNameSearch = ParserHelper::replaceApostrophe($bcnmNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"bcnm_info.name LIKE '%$bcnmNameSearch%'",
			"item_basic.name LIKE '%$itemNameSearch%'" ];

			//up_property = 'enotifwatchlistpages'
		if ( $zoneNameSearch !=  'searchallzones' ) {
			//$str = "zone_settings.name => $zoneNameSearch';
			array_push($query, "zone_settings.name = '$zoneNameSearch'");
		}


		$dbr = $this->openASBSearchConnection();
		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'hxi_bcnm_crate_list.itemRate',
						//'hxi_bcnm_crate_list.dropType',
						'hxi_bcnm_crate_list.groupId',
						'hxi_bcnm_crate_list.groupRate',
						'zone_settings.name AS zoneName',
						'bcnm_info.name AS mobName',
						//'mob_groups.minLevel AS mobMinLevel',
						//'mob_groups.maxLevel AS mobMaxLevel',
						'item_basic.name AS itemName', 
                        'item_basic.changes_tag AS itemChanges',
						'hxi_bcnm_crate_list.changes_tag AS bcnmChanges',
						'hxi_bcnm_crate_list.gilAmount AS gilAmt',
						'hxi_bcnm_crate_list.itemId'  ] )
			->from( 'hxi_bcnm_crate_list' )
			->join( 'bcnm_info', null, 'bcnm_info.bcnmId=hxi_bcnm_crate_list.bcnmId' )
			->join( 'item_basic', null, 'item_basic.itemid=hxi_bcnm_crate_list.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=bcnm_info.zoneId')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			->limit(500)
			->fetchResultSet(); 
	}

    public function getMobAndZone($mobname = null, $zonename = null, $moblevel = null){
        if ( $mobname == null && $zonename == null ) return;

        $query = [  "( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')" ];
        //array_push($query, $this->exclude_MOBGROUP_GARRISON);

        $query = $this->exclude_MOBGROUPS_OOE($query);
        $query = $this->exclude_ZONES_OOE($query);

        if ( !is_null($mobname) )  {
            $mobNameSearch = ParserHelper::replaceSpaces($mobname);
            array_push($query, "mob_groups.name LIKE '%$mobNameSearch%'");
        }
        if ( !is_null($zonename) && $zonename != 'searchallzones')  {  
            $zoneNameSearch = ParserHelper::replaceSpaces($zonename);
            array_push($query, "zone_settings.name = '$zoneNameSearch'");
		}
        if ( !is_null($moblevel) && intval($moblevel) > 0 ){
            array_push($query, "(mob_groups.minLevel <= '$moblevel') AND (mob_groups.maxLevel >= '$moblevel')");
        }
        
        //wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . $zonename .":". $mobname . ":" . $moblevel . ":" . gettype($moblevel)  );

        $dbr = $this->openASBSearchConnection();
        $results = $dbr->newSelectQueryBuilder()
			->select( [ 
						'mob_groups.groupId',
                        'mob_groups.name',
                        'mob_groups.zoneid',
                        'zone_settings.name AS zonename',
                        'mob_pools.mobType',
                        'mob_pools.aggro',
                        'mob_pools.true_detection',
                        'mob_groups.minLevel AS mobMinLevel',
						'mob_groups.maxLevel AS mobMaxLevel',
                        'mob_groups.changes_tag AS mobChanges',
                        'mob_family_system.detects',
						] )
			->from( 'mob_groups' )
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
            ->join( 'mob_family_system', null, 'mob_family_system.familyID=mob_pools.familyid')
            ->orderBy( 'zoneid', 'ASC' )
			->where( $query	)
			->fetchResultSet(); 

        // $returnArray = [];
        // foreach( $results as $result ){
        //     $returnArray[] = [ $result->zoneid, $result->zonename, $result->name, $result->groupId];
        // }
        // return $returnArray;
        return $results;
    }

    public function getMobStats($mobname, $zonename, $moblevel){
        if ( $mobname == null || $zonename == null ) return;

        if ( !is_null($moblevel) ) $moblevel = intval($moblevel);
        else $moblevel = 0;

        $query = [  "( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')" ];

        $query = $this->exclude_MOBGROUPS_OOE($query);

        $mobNameSearch = ParserHelper::replaceSpaces($mobname);
        array_push($query, "mob_groups.name = '$mobNameSearch'");

        $zoneNameSearch = ParserHelper::replaceSpaces($zonename);
        array_push($query, "zone_settings.name = '$zoneNameSearch'");
        
        if ( $moblevel > 0 ){
            array_push($query, "(mob_groups.minLevel <= '$moblevel') AND (mob_groups.maxLevel >= '$moblevel')");
        }
	
        $dbr = $this->openASBSearchConnection();
        $results = $dbr->newSelectQueryBuilder()
			->select( [ 
						'mob_groups.groupid',
                        'mob_groups.name',
                        'mob_groups.HP AS HPmodifier',
                        'mob_groups.MP AS MPmodifier',
                        'mob_groups.minLevel',
                        'mob_groups.maxLevel',
                        'zone_settings.name AS zonename',
                        'mob_family_system.STR',
                        'mob_family_system.DEX',
                        'mob_family_system.VIT',
                        'mob_family_system.AGI',
                        'mob_family_system.INT',
                        'mob_family_system.MND',
                        'mob_family_system.CHR',
                        'mob_family_system.ATT',
                        'mob_family_system.DEF',
                        'mob_family_system.ACC',
                        'mob_family_system.EVA',
                        'mob_resistances.slash_sdt', 
                        'mob_resistances.pierce_sdt', 
                        'mob_resistances.h2h_sdt', 
                        // 'mob_resistances.impact_sdt',
                        'mob_resistances.magical_sdt',
                        'mob_resistances.fire_sdt', 
                        'mob_resistances.ice_sdt', 
                        'mob_resistances.wind_sdt', 
                        'mob_resistances.earth_sdt', 
                        'mob_resistances.lightning_sdt', 
                        'mob_resistances.water_sdt', 
                        'mob_resistances.light_sdt', 
                        'mob_resistances.dark_sdt',
                        'mob_resistances.fire_res_rank',
                        'mob_resistances.ice_res_rank',
                        'mob_resistances.wind_res_rank',
                        'mob_resistances.earth_res_rank', 
                        'mob_resistances.lightning_res_rank', 
                        'mob_resistances.water_res_rank', 
                        'mob_resistances.light_res_rank', 
                        'mob_resistances.dark_res_rank', 
                        'mob_family_system.familyID',
                        '(mob_family_system.HP / 100) AS HPscale',
                        '(mob_family_system.MP / 100) AS MPscale',
                        'mob_pools.mJob',
                        'mob_pools.sJob',
                        'mob_pools.cmbSkill',
                        'mob_pools.cmbDelay',
                        'mob_pools.cmbDmgMult',
                        'mob_pools.mobType',
                        'mob_pools.poolid'
						] )
			->from( 'mob_groups' )
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
            ->join( 'mob_family_system', null, 'mob_family_system.familyID=mob_pools.familyid')
            ->join( 'mob_resistances', null, 'mob_pools.resist_id=mob_resistances.resist_id' )
            //->orderBy( 'zoneid', 'ASC' )
			->where( $query	)
			->fetchResultSet(); 

        foreach( $results as $result ){
        //     //wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . json_encode( $result ) );
            return $result;
        }

        return ;
    }

    public function getMobPoolMods($poolid){
        $dbr = $this->openASBSearchConnection();
        return $dbr->newSelectQueryBuilder()
			->select( [ 
                        'mob_pool_mods.modid',
                        'mob_pool_mods.value'
						] )
			->from( 'mob_pool_mods' )
			->where( "mob_pool_mods.poolid = '$poolid'"	)
			->fetchResultSet(); 
    }

    public function getMobFamilyMods($familyid){
        $dbr = $this->openASBSearchConnection();
        return $dbr->newSelectQueryBuilder()
			->select( [ 
                        'mob_family_mods.modid',
                        'mob_family_mods.value'
						] )
			->from( 'mob_family_mods' )
			->where( "mob_family_mods.familyid = '$familyid'"	)
			->fetchResultSet(); 
    }

    public function getRecipes($queryData){

        // $queryData = [  $params['craft'],
        //                 $params['recipename'],
        //                 $params['ingredient'],
        //                 $params['crystal'],
        //                 $params['skillrank'],
        //                 $params['mincraftlvl'],
        //                 $params['maxcraftlvl']
        //              ];

        $craftType = $queryData[0];
        $recipename = $queryData[1];
        $ingredient = $queryData[2];
        $crystal = intval($queryData[3]);
        //$skillrank = intval($queryData[4]);
        $mincraftlvl = $queryData[5];
        $maxcraftlvl = $queryData[6];
        $includeDesynths = $queryData[7];

		$recipename = ParserHelper::replaceSpaces($recipename);
		$ingredient = ParserHelper::replaceSpaces($ingredient);
        $recipename = ParserHelper::replaceApostrophe($recipename);
		$ingredient = ParserHelper::replaceApostrophe($ingredient);

        $dbr = $this->openASBSearchConnection();

        $items = $dbr->newSelectQueryBuilder()
            ->select( [ 'item_basic.name, item_basic.itemid' ] )
            ->from( 'item_basic' )
            ->fetchResultSet();
        $itemArray = [];
        foreach( $items as $item ){
            $itemArray[$item->itemid] = $item->name;
        }

        $query = [ "( synth_recipes.ContentTag = 'COP' OR synth_recipes.ContentTag IS NULL )" ];

        if ( isset($recipename) && $recipename != "" ){
            $recipeIDs = $this->getItemIDsFromDB($dbr, $recipename );

            $q = $dbr->makeList( [ 'synth_recipes.Result' => $recipeIDs ,
                                    'synth_recipes.ResultHQ1' => $recipeIDs ,
                                    'synth_recipes.ResultHQ2' => $recipeIDs ,
                                    'synth_recipes.ResultHQ3' => $recipeIDs ],
                                    $dbr::LIST_OR);
            array_push ( $query, $q);
        }

        if ( isset($ingredient) && $ingredient != "" ) {
            $ingr = [];
            $ingr = $this->getItemIDsFromDB($dbr, $ingredient );

            $q = $dbr->makeList( [ 'synth_recipes.Ingredient1' => $ingr ,
                                            'synth_recipes.Ingredient2' => $ingr ,
                                            'synth_recipes.Ingredient3' => $ingr ,
                                            'synth_recipes.Ingredient4' => $ingr ,
                                            'synth_recipes.Ingredient5' => $ingr ,
                                            'synth_recipes.Ingredient6' => $ingr ,
                                            'synth_recipes.Ingredient7' => $ingr ,
                                            'synth_recipes.Ingredient8' => $ingr
                                        ],
                                        $dbr::LIST_OR);
            array_push ( $query, $q);
        }
        //throw new Exception ( json_encode($query) );

        if ( isset($crystal) && $crystal != 0 ){ array_push ( $query, "synth_recipes.Crystal = '$crystal'"); }

        switch($craftType){
            case 'Wood':
                array_push ( $query, "synth_recipes.Wood != 0" ) ;
                if ( $mincraftlvl != '0' ) array_push ( $query, "synth_recipes.Wood >= '$mincraftlvl'")  ;
                if ( $maxcraftlvl != '0' ) array_push ( $query, "synth_recipes.Wood <= '$maxcraftlvl'") ;
                break;
            case 'Smith':
                array_push ( $query, "synth_recipes.Smith != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Smith >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Smith <= '$maxcraftlvl'" ) ;
                break;
            case 'Gold':
                array_push ( $query, "synth_recipes.Gold != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Gold >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Gold <= '$maxcraftlvl'" ) ;
                break;
            case 'Cloth':
                array_push ( $query, "synth_recipes.Cloth != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Cloth >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Cloth <= '$maxcraftlvl'" ) ;
                break;
            case 'Leather':
                array_push ( $query, "synth_recipes.Leather != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Leather >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Leather <= '$maxcraftlvl'" ) ;
                break;
            case 'Bone':
                array_push ( $query, "synth_recipes.Bone != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Bone >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Bone <= '$maxcraftlvl'" ) ;
                break;
            case 'Alchemy':
                array_push ( $query, "synth_recipes.Alchemy != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Alchemy >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Alchemy <= '$maxcraftlvl'" ) ;
                break;
            case 'Cook':
                array_push ( $query, "synth_recipes.Cook != 0" ) ;
                if ( $mincraftlvl != "0" ) array_push ( $query, "synth_recipes.Cook >= '$mincraftlvl'" ) ;
                if ( $maxcraftlvl != "0" ) array_push ( $query, "synth_recipes.Cook <= '$maxcraftlvl'" ) ;
                break;
            default;
        }

        if ( $includeDesynths == 1 ) array_push ( $query, "synth_recipes.Desynth <= 1" );
        else array_push ( $query, "synth_recipes.Desynth = 0" );

        // if ( isset($skillrank) && $skillrank != 0 ){
        //     $high = $skillrank + 9;
        //     array_push ( $query, $dbr->expr( 'cat_pages', '>', 0 ));
        // }

        // DEBUGGING
        //return $query;
        /////////////

        $recipesQueryResult = $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'synth_recipes' )
            ///->join( 'item_basic', null, 'item_basic.name=synth_recipes.ResultName')
			->where( $query )
			->fetchResultSet();

        return [ $recipesQueryResult, $itemArray ];
    }

    private function getItemIDsFromDB($db, $name){
        $items = $db->newSelectQueryBuilder()
            ->select( [ 'item_basic.name, item_basic.itemid' ] )
            ->from( 'item_basic' )
            ->where( "item_basic.name LIKE '%$name%'"	)
            ->fetchResultSet();

            $returnarray = [];
            if ( count($items) == 0 || count($items) == NULL) return NULL;
            foreach ( $items as $row ) {
                array_push( $returnarray , strval($row->itemid));
            }
            return $returnarray;
    }

    // private function getItemModsFromDB($db, $name){
    //     $items = $db->newSelectQueryBuilder()
    //         ->select( [ 'item_basic.name, item_basic.itemid' ] )
    //         ->from( 'item_basic' )
    //         ->where( "item_basic.name LIKE '%$name%'"	)
    //         ->fetchResultSet();

    //         $returnarray = [];
    //         if ( count($items) == 0 || count($items) == NULL) return NULL;
    //         foreach ( $items as $row ) {
    //             array_push( $returnarray , strval($row->itemid));
    //         }
    //         return $returnarray;
    // }

    public function getEquipmentFromDB($queryData){
        //$this->incrementHitCounter("equipment");

        $dbr = $this->openASBSearchConnection();

        $equipmentname = $queryData[0];
        // $job = $queryData[1];
        $itemlevel = intval($queryData[2]);
        $slot = intval($queryData[3]);

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

        $query = [ "item_equipment.name LIKE '%$equipmentname%' OR item_basic.name LIKE '%$equipmentname%' OR item_basic.sortname LIKE '%$equipmentname%'"];
        // if ( $queryData[0] !=  '' ) {
		// 	array_push($query, "item_equipment.name LIKE '%$queryData[0]%'");
		// }
        if ( $itemlevel !=  '0' ) {
			array_push($query, "item_equipment.level <= $itemlevel");
		}
        else array_push($query, "item_equipment.level <= 75");

        if ( $slot != 0 ){
            if ( $slot == 1){ $q = "( item_equipment.slot = 1 OR item_equipment.slot = 3 )"; }
            else $q = "item_equipment.slot = '$slot'" ;
            array_push($query, $q);
        }

        return $dbr->newSelectQueryBuilder()
        ->select( [ 'item_basic.name AS showname',
                    'item_equipment.level',
                    'item_equipment.jobs',
                    'item_equipment.slot',
                    'item_mods.modId AS modid',
                    'item_mods.value AS modValue',
                    'item_equipment.itemId'
                    ] )
        ->from( 'item_equipment' )
        ->leftjoin( 'item_mods', null, 'item_mods.itemId=item_equipment.itemId' )
        ->leftjoin( 'item_basic', null, 'item_basic.itemid=item_equipment.itemId' )
        ->orderBy( 'level', 'DESC' )
        ->where( $query	)
        ->fetchResultSet();
    }

    public function getTraits( $mlvl, $slvl, $mjob, $sjob){
        $dbr = $this->openASBSearchConnection();
        $query = [
            "( traits.job = '$mjob' AND traits.level <= '$mlvl') OR (traits.job = '$sjob' AND traits.level <= '$slvl')",
            "( traits.content_tag = 'COP' OR traits.content_tag IS NULL )",
        ];

        return $dbr->newSelectQueryBuilder()
        ->select( [ 'traits.modifier',
                    'traits.value',
                    'traits.traitid'
                    ] )
        ->from( 'traits' )
        ->orderBy( 'modifier' )
        ->where( $query	)
        ->fetchResultSet();
    }

    public function getItem( $itemid ){
        $dbr = $this->openASBSearchConnection();
        $query = [ "item_basic.itemId = '$itemid'" ];

        return $dbr->newSelectQueryBuilder()
        ->select( [ 'item_basic.name AS showname',
                    'item_equipment.itemId',
                    'item_equipment.slot',
                    'item_equipment.rslot',
                    'item_mods.modId AS modid',
                    'item_mods.value AS modValue',
                    'item_weapon.skill AS skilltype',
                    'item_equipment.level',
                    'item_equipment.jobs',
                    ] )
        ->from( 'item_basic' )
        ->leftjoin( 'item_mods', null, 'item_mods.itemId=item_basic.itemid' )
        ->leftjoin( 'item_equipment', null, 'item_equipment.itemId=item_basic.itemid' )
        ->leftjoin( 'item_weapon', null, 'item_basic.itemid=item_weapon.itemId' )
        ->where( $query	)
        ->orderBy( 'showname', 'ASC' ) 
        ->fetchResultSet();
    }

    public function getSkillRanks( $mjob, $sjob ){
        $dbr = $this->openASBSearchConnection();

        $mjobLabel =  strtolower(FFXIPackageHelper_Variables::$jobArrayByID[$mjob]);
        $sjobLabel =  strtolower(FFXIPackageHelper_Variables::$jobArrayByID[$sjob]);

        $query = [ "$mjobLabel > 0 OR $sjobLabel > 0" ];

        $mjobLabel .= " as mjob";
        $sjobLabel .= " as sjob";
       // throw new Exception(json_encode($query));

        return $dbr->newSelectQueryBuilder()
        ->select( [ $mjobLabel, $sjobLabel, 'skillid' ] )
        ->from( 'skill_ranks' )
        ->where( $query	)
        ->fetchResultSet();
    }

    public function getSkillRank( $skill, $mjob ){
        $dbr = $this->openASBSearchConnection();

        $mjobLabel = strtolower(FFXIPackageHelper_Variables::$jobArrayByID[$mjob]);

        $query = [ "skill_ranks.skillid = '$skill'" ];        

        $results = $dbr->newSelectQueryBuilder()
        ->select( [ $mjobLabel ] )
        ->from( 'skill_ranks' )
        ->where( $query	)
        ->fetchResultSet();

        foreach ($results as $row ){
            return $row->$mjobLabel;
        }
        return;
    }    

    public function getSkillCap( $mLvl, $rank ){
        $dbr = $this->openASBSearchConnection();

        $rank = "r" . $rank;

        $query = [ "skill_caps.level = '$mLvl'" ];

        $results = $dbr->newSelectQueryBuilder()
        ->select( [ $rank ] )
        ->from( 'skill_caps' )
        ->where( $query	)
        ->fetchResultSet();

        foreach ($results as $row ){
            return $row->$rank;
        }
        return;
    }

    public function getEquipment( $name, $mlvl, $gridSlot = null ){

        $dbr = $this->openASBSearchConnection();

        $mlvl = intval($mlvl);

        $query = [  "item_equipment.name LIKE '%$name%' OR item_basic.name LIKE '%$name%' OR item_basic.sortname LIKE '%$name%'",
                    "item_equipment.level <= $mlvl"
        ];

        if ( !is_null($gridSlot)  ) {
            $gridSlot = intval($gridSlot) ;

            $q = null;
                switch($gridSlot){
                    case 0: // main
                        $q = $dbr->makeList( [ 'item_equipment.slot' => [ 0x1, 0x3] ], $dbr::LIST_OR);
                        break;
                    case 1: // sub
                        $q = $dbr->makeList( [ 'item_equipment.slot' => [ 0x2, 0x3] ], $dbr::LIST_OR);
                        break;
                    case 2: // range
                        $q = "item_equipment.slot = 0x4";
                        break;
                    case 3: // ammo
                        $q = "item_equipment.slot = 0x8";
                        break;
                    case 4: // head
                        $q = "item_equipment.slot = 0x10";
                        break;
                    case 5: // neck
                        $q = "item_equipment.slot = 0x200";
                        break;
                    case 6: // ear1
                    case 7: // ear2
                        $q = "item_equipment.slot = 0x1800";
                        break;
                    case 8: // body
                        $q = "item_equipment.slot = 0x20";
                        break;
                    case 9: // hands
                        $q = "item_equipment.slot = 0x40";
                        break;
                    case 10: // ring1
                    case 11: // ring2
                        $q = "item_equipment.slot = 0x6000";
                        break;
                    case 12: // back
                        $q = "item_equipment.slot = 0x8000";
                        break;
                    case 13: // waist
                        $q = "item_equipment.slot = 0x400";
                        break;
                    case 14: // legs
                        $q = "item_equipment.slot = 0x80";
                        break;
                    case 15: // feet
                        $q = "item_equipment.slot = 0x100";
                        break;
                }

                if ( !is_null($q) ) array_push ( $query, $q);
        }

        return $dbr->newSelectQueryBuilder()
        ->select( [ 'item_equipment.itemId',
                    'item_equipment.level',
                    'item_equipment.jobs',
                    'item_equipment.slot',
                    'item_equipment.rslot',
                    'item_mods.modId AS modid',
                    'item_mods.value AS modValue',
                    'item_weapon.skill AS skill',
                    'item_basic.name AS showname',
                    ] )
        ->from( 'item_basic' )
        //->from( 'item_equipment' )
        ->leftjoin( 'item_mods', null, 'item_mods.itemId=item_basic.itemid' )
        ->leftjoin( 'item_weapon', null, 'item_weapon.itemId=item_basic.itemid' )
        //->leftjoin( 'item_basic', null, 'item_basic.itemid=item_equipment.itemId' )
        ->leftjoin( 'item_equipment', null, 'item_basic.itemid=item_equipment.itemId' )
        ->where( $query )
        ->orderBy( 'showname', 'ASC' ) 
        ->fetchResultSet();

    }


    public function getFishing( $queryData ){
        $bait = $queryData[0];
        $fish = $queryData[1];
        $zone = $queryData[2];

        
        $dbr = $this->openASBSearchConnection();
        //$vars = new FFXIPackageHelper_Variables();

        $query = [];

        if ( !is_null($bait)&& $bait != "") {
            $bait =  strtolower($bait);
            $bait = ParserHelper::replaceSpaces($bait);
            $bait = ParserHelper::replaceApostrophe($bait);

            array_push($query, "fishing_bait.name LIKE '%$bait%'");
        }
        if ( !is_null($fish) && $fish != "") {
            $fish =  strtolower($fish);
            $fish = ParserHelper::replaceSpaces($fish);
            $fish = ParserHelper::replaceApostrophe($fish);

            array_push($query, "fishing_fish.name LIKE '%$fish%'");
        }
        if ( !is_null($zone) && $zone != "searchallzones") {
            //$zone =  strtolower($zone);
            $zone = ParserHelper::replaceSpaces($zone);
            $zone = ParserHelper::replaceApostrophe($zone); 

            array_push($query, "fishing_zone.name LIKE '%$zone%'");
        }
        //throw new Exception( json_encode($query));
        return $dbr->newSelectQueryBuilder()
        ->select( [ 'fishing_fish.name AS fishname',
                    'fishing_fish.fishid',
                    'fishing_bait.name AS baitname',
                    'fishing_zone.name AS zonename'
                ] )
        ->from( 'fishing_fish' )
        ->join( 'fishing_group', null, 'fishing_group.fishid=fishing_fish.fishid')
        ->join( 'fishing_bait_affinity', null, 'fishing_bait_affinity.fishid=fishing_fish.fishid')
        ->join( 'fishing_bait', null, 'fishing_bait.baitid=fishing_bait_affinity.baitid')
        ->join( 'fishing_catch', null, 'fishing_catch.groupid=fishing_group.groupid')
        ->join( 'fishing_zone', null, 'fishing_zone.zoneid=fishing_catch.zoneid')
        ->orderBy( 'fishing_fish.name', 'ASC' )        
        ->where( $query	)
        ->fetchResultSet();
    }

    public function setEquipsetForUser( $user, $equipset){
		// $userId = $user->getId();

		// if ( $userId != 0 ) {
        //     $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		// 	$dbw = $lb->getConnectionRef( DB_PRIMARY );
		// 	$dbw->delete(
		// 		'ldap_domains',
		// 		[
		// 			'user_id' => $userId
		// 		],
		// 		__METHOD__
		// 	);
		// 	return $dbw->insert(
		// 		'ldap_domains',
		// 		[
		// 			'domain' => $domain,
		// 			'user_id' => $userId
		// 		],
		// 		__METHOD__
		// 	);
		// }
		// return false;
	}

    public function getUserCharactersFromUserID($uid){
        $char = new FFXIPH_Character();
        return $this->getUserCharacters($char);
    }

    /**
     * Returns an array of FFXIPH_Character objects
     * @return FFXIPH_Character[]
     */
    public function getUserCharacters($char = null, $testForExistingChar = false){
        $dbr = $this->openEquipsetsConnection();

        if ( $char == null ) $uid = RequestContext::getMain()->getUser()->getId();
        else $uid = $char->userid;

        $chars = $dbr->newSelectQueryBuilder()
        ->select( [ 'charname', 'charid', 'race', 'merits', 'def' ] )
        ->from( 'user_chars' )
        ->where( [ "user_chars.userid = $uid" ] )
        ->fetchResultSet();

        $userCharacters = [];
        foreach($chars as $row){
            if ( $testForExistingChar == true &&
                //array_key_exists("charname", $char) &&
                !is_null($char->charname) &&
                $row->charname == $char->charname ) {
                //throw new Exception ( json_encode($row) );
                return $row->charname;
            }
            else {
                $newChar = new FFXIPH_Character();
                    $newChar->charname = $row->charname;
                    $newChar->charid = $row->charid;
                    $newChar->race = $row->race;
                    $newChar->setMerits($row->merits);
                    $newChar->def = $row->def;
                $userCharacters[] = $newChar;
            }
        }
        return $userCharacters;
    }

    public function removeUserCharDefault($char){
        $dbw = $this->openEquipsetsConnection();

        return $dbw->update(
            'user_chars',
            [
                'def' => 0
            ],
            [
                'charid' => $char->charid,
            ]
        );
    }

    public function updateUserCharacter($char){
        $dbw = $this->openEquipsetsConnection();
        //throw new Exception ( json_encode($char));

        return $dbw->update(
            'user_chars',
            [
                'race' => $char->race ,
                'merits' => $char->getMeritsURLSafe(),
                'def' => $char->def
            ],
            [
                'userid' => $char->userid,
                'charname' => $char->charname
            ]
        );
    }


    public function getSelectedCharacter($char){
        $dbr = $this->openEquipsetsConnection();
        //wfDebugLog( 'Equipsets', get_called_class() . ":getSelectedCharacter:" . json_encode($char) );

        $uid = $char->userid;
        $charname = $char->charname;

        $query = [
            "user_chars.userid = '$uid' AND user_chars.charname = '$charname'",
        ];

        $result = $dbr->newSelectQueryBuilder()
        ->select( [ 'charname', 'charid', 'race', 'merits', 'def'] )
        ->from( 'user_chars' )
        ->where($query)
        ->fetchResultSet();

        foreach($result as $row){
            return new FFXIPH_Character($row->race, null, null, null, null,
                            $row->merits, null, $row->def, $row->charname, $row->charid);
            // return [
            //     'charname' => $row->charname,
            //     'charid' => $row->charid,
            //     'race' => $row->race,
            //     'merits' => $row->merits,
            //     'def' => $row->def
            // ];
        }
        return [];
    }

    public function getDefaultCharacter($uid){
        $dbr = $this->openEquipsetsConnection();

        $query = [ "user_chars.userid = '$uid' AND user_chars.def = 1"];

        $result = $dbr->newSelectQueryBuilder()
        ->select( [ 'charname', 'charid', 'race', 'merits', 'def'] )
        ->from( 'user_chars' )
        ->where($query)
        ->fetchResultSet();

        if( count($result) == 0 ) return null;

        foreach($result as $row){
            return new FFXIPH_Character(
                $row->race, null, null, null, null, $row->merits, null,
                $row->def, $row->charname, $row->charid
            );         
            // return [
            //     'charname' => $row->charname,
            //     'charid' => $row->charid,
            //     'race' => $row->race,
            //     'merits' => $row->merits,
            //     'def' => $row->def
            // ];
        }
        //return [];
    }


    public function setUserCharacter($char){
        $dbw = $this->openEquipsetsConnection();

        return $dbw->insert(
            'user_chars',
            [
                'userid' => $char->userid,
                'charname' => $char->charname,
                'race' => $char->race,
                'merits' => $char->getMeritsURLSafe(),
                'def' => $char->def,
            ],
            __METHOD__
        );
    }

    public function removeUserCharacter($char){
        $dbw = $this->openEquipsetsConnection();

        return $dbw->delete(
            'user_chars',
            [
                'userid' => $char->userid,
                'charname' => $char->charname
            ],
            __METHOD__
        );
    }

    public function getUserSetsFromUserID($uid){
        $dbr = $this->openEquipsetsConnection();

        $savedSets = $dbr->newSelectQueryBuilder()
        //->select( [ 'usersetid', 'mlvl', 'slvl', 'mjob', 'sjob', 'equipment', 'setname'] )
        ->select( [ 'usersetid', 'setname', 'mjob'] )
        ->from( 'user_sets' )
        ->where( [ "user_sets.userid = $uid" ] )
        ->orderBy( 'mjob', 'ASC' )
        ->fetchResultSet();

        $userSets = [];
        foreach($savedSets as $row){
            $jobname = FFXIPackageHelper_Variables::$jobArrayByID[$row->mjob];
            $userSets[$jobname][] = [
                'usersetid' => $row->usersetid,
                // 'mlvl' => $row->mlvl,
                // 'slvl' => $row->slvl,
                //'mjob' => $row->mjob,
                // 'sjob' => $row->sjob,
                // 'equipment' => $row->equipment,
                'setname' => $row->setname
            ];
        }
        //throw new Exception ( json_encode($userSets)  );
        return $userSets;
    }

    public function getUserSetsForJob($uid, $mjob){
        $dbr = $this->openEquipsetsConnection();

        $query = [
            "user_sets.mjob = '$mjob' AND user_sets.userid = '$uid'"
        ];

        $savedSets = $dbr->newSelectQueryBuilder()
        //->select( [ 'usersetid', 'mlvl', 'slvl', 'mjob', 'sjob', 'equipment', 'setname'] )
        ->select( [ 'usersetid', 'setname' ] )
        ->from( 'user_sets' )
        ->where( $query )
        //->orderBy( 'mjob', 'ASC' )
        ->fetchResultSet();

        $jobname = FFXIPackageHelper_Variables::$jobArrayByID[$mjob];

        $userSets = [];
        foreach($savedSets as $row){
            $userSets[$jobname][] = [
                'usersetid' => $row->usersetid,
                // 'mlvl' => $row->mlvl,
                // 'slvl' => $row->slvl,
                //'mjob' => $row->mjob,
                // 'sjob' => $row->sjob,
                // 'equipment' => $row->equipment,
                'setname' => $row->setname
            ];
        }
        //throw new Exception ( json_encode($userSets)  );
        return $userSets;
    }

    public function saveSet($newSet){
        $dbw = $this->openEquipsetsConnection();

        return $dbw->insert(
            'user_sets',
            [
                'userid' => $newSet['userid'],
                'setname' => $newSet['setname'],
                'mlvl' => $newSet['mlvl'],
                'slvl' => $newSet['slvl'],
                'mjob' => $newSet['mjob'],
                'sjob' => $newSet['sjob'],
                'equipment' => $newSet['equipment']
            ],
            __METHOD__
        );
    }

    public function removeSet($uid, $usersetid){
        $dbw = $this->openEquipsetsConnection();

        return $dbw->delete(
            'user_sets',
            [
                'userid' => $uid,
                'usersetid' => $usersetid
            ],
            __METHOD__
        );
    }

    public function fetchSet($params){
        $dbr = $this->openEquipsetsConnection();

        $usersetid = $params['usersetid'];

        $fetchedSet = $dbr->newSelectQueryBuilder()
        ->select( [ 'usersetid', 'mlvl', 'slvl', 'mjob', 'sjob', 'equipment'] )
        ->from( 'user_sets' )
        ->where( [ "user_sets.usersetid = $usersetid" ] )
        ->fetchResultSet();

        //$set = [];
        foreach($fetchedSet as $row){
            return [
                'usersetid' => intval($row->usersetid),
                'race' => $params['race'],
                'mlvl' => intval($row->mlvl),
                'slvl' => intval($row->slvl),
                'mjob' => intval($row->mjob),
                'sjob' => intval($row->sjob),
                'merits' => $params['merits'],
                'equipment' => $row->equipment
                //'setname' => $row->setname
            ];
        }

        return [];
    }

    public function setExists($dbr, $setid){
        //$dbr = $this->openASBSearchConnection();

        // $chars = $dbr->newSelectQueryBuilder()
        // ->select( [ 'charname' ] )
        // ->from( 'user_chars' )
        // ->where( [ "user_chars.userid = $uid" ] )
        // ->fetchResultSet();

        // $userCharacters = [];
        // foreach($chars as $row){
        //     $userCharacters[] = $row->charname;
        // }
        // return $userCharacters;
    }

}

?>