<?php

use Wikimedia\Rdbms\DatabaseFactory;

class DBConnection {
	private $dbUsername = 'horizon_wiki'; 
	private $dbPassword = 'KamjycFLfKEyFsogDtqM';

    public function openConnection() {
        if ( $_SERVER['SERVER_NAME'] == 'localhost' ){ 
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


}

?>