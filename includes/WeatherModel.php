<?php


class WeatherModel {
    public function __construct() {
    }
    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('WeatherModel', 'WeatherModel::generateWeatherTable' );
        return true;
	}

    

    public static function generateWeatherTable( $input, array $params, Parser $parser, PPFrame $frame ) {
        $db = new DBConnection();
        
        $zoneWeather = $db->getZoneWeather(1, 1) ;
    
        $html = "";

        //$html = $html . "<p>Normal:" . $zoneWeather["normal"] . "  Common:" . $zoneWeather["common"] . "  Rare:" . $zoneWeather["rare"] . "</p>";

        return 	$html;
    }
}

?>