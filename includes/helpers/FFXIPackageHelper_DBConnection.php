<?php

use Wikimedia\Rdbms\DatabaseFactory;

class DBConnection {

    /*
    // $queryData = [ $queryLimit, $mobNameSearch, $itemNameSearch, $zoneNameDropDown, $showBCNMdrops, $excludeNMs, $levelRangeMIN, $levelRangeMAX ];
    $queryLimit = $queryData[0];
    $mobNameSearch = $queryData[1];
    $itemNameSearch = $queryData[2];
    $zoneNameSearch = $queryData[3];
    $showBCNMdrops = $queryData[4];
    $excludeNMs = $queryData[5];
    $levelRangeMIN = $queryData[6];
    $levelRangeMAX = $queryData[7];
    $showTH = $queryData[8];
    */

	private $dbUsername = 'horizon_wiki'; 
	private $dbPassword = 'KamjycFLfKEyFsogDtqM';

    public function openConnection() {
        if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] == 'localhost' ){
			$this->dbUsername = 'root'; $this->dbPassword = '';
		}
        try {
            $db = ( new DatabaseFactory() )->create( 'mysql', [
                'host' => 'localhost',
                'user' => $this->dbUsername,
                'password' => $this->dbPassword,
                // 'user' => 'horizon_wiki',
                // 'password' => 'KamjycFLfKEyFsogDtqM',
                'dbname' => 'ASB_Data',
                'flags' => 0,
                'tablePrefix' => ''] );
            //$status->value = $db;
            $returnDB = $db;
        } catch ( DBConnectionError $e ) {
            //$status->fatal( 'config-connection-error', $e->getMessage() );
            print_r('issue');
        }


        // return $status;
        return $returnDB;
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

    function getVanaDate(){
        return intval(((floor(microtime(true) ) - 1009810800) / 3456)) % 2160 ;
    }

    function getZoneList() {
		$dbr = $this->openConnection();
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

    function getZoneWeather($zone, $numberOfDays ) {
		$dbr = $this->openConnection();
        $query = "zone_weather.zone = $zone";
		$weather = $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'zone_weather' )
			->where( $query )
			->fetchResultSet(); 
        
        foreach ( $weather as $row ) {
            $bin = unpack("H*",$row->weather);        
            $arr = str_split($bin[1], 2);
                        
            $m_vanaDate = $this->getVanaDate();

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
        $dbr = $this->openConnection();
        $query = "zone_weather.zone = $zone";
		return $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'zone_weather' )
			->where( $query )
			->fetchResultSet(); 
    }

    public function getForecastFromDB() {
        $dbr =  $this->openConnection();
        // $query = "zone_weather.zone = $zone";
		return $dbr->newSelectQueryBuilder()
			->select( [ 'name', 'zoneid', 'weather' ] )
			->from( 'zone_weather' )
            ->join( 'zone_settings', null, 'zone_weather.zone=zone_settings.zoneid' )
			// ->where( $query )
			->fetchResultSet();
    }

    public function getWeather($forDiggersPage){
        $forDiggersPage ? $forDiggersPage : false;

        $dbr = new DBConnection();
        $allZonesWeather = $dbr->getForecastFromDB();

        $result = [ ];
        foreach( $allZonesWeather as $row ){
            //Filter zones for those in Era
            $temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) ) { continue; }

            $dayCount = 30;
            //check if on the diggers special page
            //should only include the weather for the zones listed in ExclusionsHelper::$diggingRelevantZones
            if ( $forDiggersPage == true) {
                if ( !array_key_exists($row->zoneid, ExclusionsHelper::$diggingRelevantZones) ) continue;
                $dayCount = 4;
            }

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
        // $queryData = [ $queryLimit, $mobNameSearch, $itemNameSearch, $zoneNameDropDown, $showBCNMdrops, $excludeNMs, $levelRangeMIN, $levelRangeMAX ];
        $queryLimit = $queryData[0];
        $mobNameSearch = $queryData[1];
        $itemNameSearch = $queryData[2];
        $zoneNameSearch = $queryData[3];
        $showBCNMdrops = intval($queryData[4]);
        $excludeNMs = intval($queryData[5]);
        $levelRangeMIN = intval($queryData[6]);
        $levelRangeMAX = intval($queryData[7]);

		$mobNameSearch = ParserHelper::replaceSpaces($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$mobNameSearch = ParserHelper::replaceApostrophe($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"mob_groups.name LIKE '%$mobNameSearch%'",
			"item_basic.name LIKE '%$itemNameSearch%'",
			"mob_droplist.dropid !=0 ",
			"( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')",
			//"mob_groups.content_tag IS NULL ",
		];

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
		$dbr = $this->openConnection();
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
						'mob_pools.mobType'
						] )
			->from( 'mob_droplist' )
			->join( 'mob_groups', null, 'mob_groups.dropid=mob_droplist.dropid' )
			->join( 'item_basic', null, 'item_basic.itemid=mob_droplist.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			->limit( $queryLimit)
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

		$dbr = $this->openConnection();
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
						//'item_basic.sortname AS itemSortName',
						'hxi_bcnm_crate_list.changes_tag AS itemChanges',
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
        $skillrank = intval($queryData[4]);
        $mincraftlvl = $queryData[5];
        $maxcraftlvl = $queryData[6];

		$recipename = ParserHelper::replaceSpaces($recipename);
		$ingredient = ParserHelper::replaceSpaces($ingredient);
        $recipename = ParserHelper::replaceApostrophe($recipename);
		$ingredient = ParserHelper::replaceApostrophe($ingredient);

        // $html .= "<option value=\"any\">Any</option>";
        // $html .= "<option value=\"amatuer\">Amatuer</option>";
        // $html .= "<option value=\"recruit\">Recruit</option>";
        // $html .= "<option value=\"initiate\">Initiate</option>";
        // $html .= "<option value=\"novice\">Novice</option>";
        // $html .= "<option value=\"apprentice\">Apprentice</option>";
        // $html .= "<option value=\"journeyman\">Journeyman</option>";
        // $html .= "<option value=\"craftsman\">Craftsman</option>";
        // $html .= "<option value=\"artisan\">Artisan</option>";
        // $html .= "<option value=\"adept\">Adept</option>";
        // $html .= "<option value=\"veteran\">Veteran</option>";


        $dbr = $this->openConnection();

        $items = $dbr->newSelectQueryBuilder()
            ->select( [ 'item_basic.name, item_basic.itemid' ] )
            ->from( 'item_basic' )
            ->fetchResultSet();
        $itemArray = [];
        foreach( $items as $item ){
            $itemArray[$item->itemid] = $item->name;
        }

        $query = [ "( synth_recipes.ContentTag = 'COP' OR synth_recipes.ContentTag IS NULL )" ];
        $ingr = [];

        if ( isset($ingredient) && $ingredient != "" ) {
            $_ingr = $dbr->newSelectQueryBuilder()
            ->select( [ 'item_basic.name, item_basic.itemid' ] )
            ->from( 'item_basic' )
            ->where( "item_basic.name LIKE '%$ingredient%'"	)
            ->fetchResultSet();

            foreach ( $_ingr as $row ) {
                array_push( $ingr , strval($row->itemid));
            }

            $query = [ $dbr->makeList( [ 'synth_recipes.Ingredient1' => $ingr ,
                                            'synth_recipes.Ingredient2' => $ingr ,
                                            'synth_recipes.Ingredient3' => $ingr ,
                                            'synth_recipes.Ingredient4' => $ingr ,
                                            'synth_recipes.Ingredient5' => $ingr ,
                                            'synth_recipes.Ingredient6' => $ingr ,
                                            'synth_recipes.Ingredient7' => $ingr ,
                                            'synth_recipes.Ingredient8' => $ingr ],
                                            $dbr::LIST_OR)];
        }

        //$prelimQuery = [ ];
       // "( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')",
        if ( isset($recipename) && $recipename != "" ){ array_push ( $query, "synth_recipes.ResultName LIKE '%$recipename%'"); }
        if ( isset($crystal) && $crystal != 0 ){ array_push ( $query, "synth_recipes.Crystal = '$crystal'"); }
        // if ( isset($skillrank) && $skillrank != 0 ){
        //     $high = $skillrank + 9;
        //     array_push ( $query, $dbr->expr( 'cat_pages', '>', 0 ));
        // }

        // DEBUGGING
        //return $query; }
        /////////////
        $recipesQueryResult = $dbr->newSelectQueryBuilder()
			->select( [ '*' ] )
			->from( 'synth_recipes' )
            ///->join( 'item_basic', null, 'item_basic.name=synth_recipes.ResultName')
			->where( $query )
			->fetchResultSet();

        return [ $recipesQueryResult, $itemArray ];
    }
}

?>