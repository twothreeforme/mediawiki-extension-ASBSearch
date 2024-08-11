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

    function getWeatherHex($vanaDay){
        $hexweatherdata =  $arr[(($vanaDay * 2)  + 1 )] . $arr[($vanaDay * 2) ];
        print_r("<br/>" . "newHex: " . $hexweatherdata . "<br/>"); 
        return $hexweatherdata;
    }

    function getLastWeatherHex($w_vanaDate){
        $hexweatherdata = 0;
        do {
            $w_vanaDate = $w_vanaDate - 1;
            print_r("date: " . $w_vanaDate . "<br/>"); 
            $hexweatherdata = $this->getWeatherHex($w_vanaDate);
        }while ( $hexweatherdata == 0000 );
        return $hexweatherdata;
    }

    function getNextWeatherHex(){
        $hexweatherdata = 0;
        do {
            $w_vanaDate = $w_vanaDate - 1;
            print_r("date: " . $w_vanaDate . "<br/>"); 
            $hexweatherdata = $this->getWeatherHex($w_vanaDate);
        }while ( $hexweatherdata == 0000 );
        return $hexweatherdata;
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
            $dayUpdate = 0;
            $w_vanaDate = $m_vanaDate;  

            // Have to find the first instance of weather in the hex... 
            // If the hex reads 0000 on the current day, then keep going back 1 day until 
            // reach a day with weather
            $w_vanaDate = $w_vanaDate + ($dayUpdate);
            print_r("date: " . $w_vanaDate . "<br/>"); 
            $hexweatherdata = $this->getWeatherHex($w_vanaDate);   
            
            
            
            do {
                if ( $dayUpdate > 0) {
                    print_r( "dayUpdate: " . $dayUpdate . "<br/>");   

                    $hexweatherdata = 0000;
                    do {
                        $m_vanaDate = $m_vanaDate + 1;
                        print_r("date: " . $m_vanaDate . "<br/>"); 

                        $hexweatherdata = $this->getWeatherHex($m_vanaDate);
                    }while ( $hexweatherdata == 0000 );
                }   
                
                $decimal = hexdec($hexweatherdata);
                print_r("hex: " . $hexweatherdata . " dec: " . $decimal . "<br/>" );

                //$binary = strrev(decbin($decimal));
                $binary = decbin($decimal);

                print_r("bin: " . $binary . "<br/>");

                $paddedBinary = str_pad(
                    $binary, 
                    15, 
                    '0', 
                    STR_PAD_LEFT
                );
        
                $split = str_split($paddedBinary, 5);
                print_r(" paddedBin: " . $paddedBinary . "<br/>" );
                print_r(" split0: " . $split[0] . " split1: " . $split[1] . " split2: " . $split[2] . "<br/>");
                
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
                            $weatherType = "Clouds";
                            break;
                        case 3;
                            $weatherType = "Fog";
                            break;
                        case 4;
                            $weatherType = "Hot Spell";
                            break;
                        case 5;
                            $weatherType = "Heat Wave";
                            break;
                        case 6;
                            $weatherType = "Rain";
                            break;
                        case 7;
                            $weatherType = "Squall";
                            break;
                        case 8;
                            $weatherType = "Dust Storm";
                            break;
                        case 9;
                            $weatherType = "Sand Storm";
                            break;
                        case 10;
                            $weatherType = "Wind";
                            break;
                        case 11;
                            $weatherType = "Gales";
                            break;
                        case 12;
                            $weatherType = "Snow";
                            break;
                        case 13;
                            $weatherType = "Blizzard";
                            break;
                        case 14;
                            $weatherType = "Thunder";
                            break;
                        case 15;
                            $weatherType = "Thunderstorms";
                            break;
                        case 16;
                            $weatherType = "Auroras";
                            break;
                        case 17;
                            $weatherType = "Stellar Glare";
                            break;
                        case 18;
                            $weatherType = "Gloom";
                            break;
                        case 19;
                            $weatherType = "Darkness";
                            break;
                        default:
                            break;
                    }		

                    //array_push( $weatherArray, [$ncr => $weatherType]);
                    $dayArray[$ncr] = $weatherType;
                    
                }
            $weatherArray[$dayUpdate] = $dayArray;
            $dayUpdate = $dayUpdate + 1;

            }while( count($weatherArray) < 16 );
            //$weatherArray = array("normal"=>bindec($split[0]), "common"=>bindec($split[1]), "rare"=>bindec($split[2]));
            print_r($weatherArray);
            return $weatherArray;
        }

        return null;
	}


}

?>