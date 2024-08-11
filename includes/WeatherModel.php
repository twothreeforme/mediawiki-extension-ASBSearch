<?php


class WeatherModel {
    public function __construct() {
    }
    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('WeatherModel', 'WeatherModel::generateWeatherTable' );
        return true;
	}

    static function _tableHeaders(){
		$html = "";
		$html .= "<br>
		<div style=\"max-height: 150px; overflow: auto; display: inline-block; width: 100%;\">
		<table id=\"asbsearch_forecast\" class=\"sortable\">
			<tr><th>Vana Days from Current</th>
			<th>Normal (50%)</th>
			<th>Common (35%)</th>
			<th>Rare (15%)</th>
			";
		return $html;
	}

    public static function generateWeatherTable( $input, array $params, Parser $parser, PPFrame $frame ) {
        $db = new DBConnection();
        
        $zoneWeather = $db->getZoneWeather(1, 1) ;
    
        $html = WeatherModel::_tableHeaders();

        //$html = $html . "<p>Vana Days from now... Normal (50%)... Common (35%)... Rare (15%)... </p>";

        foreach ($zoneWeather as $daysFromCurrent => $weatherArray) {

            $daysFromCurrent = $daysFromCurrent == 0 ? "Current" : $daysFromCurrent ;
            //$html = $html . "<p>" . $daysFromCurrent . " ... " . "  Normal:" . $weatherArray["normal"] . "  Common:" . $weatherArray["common"] . "  Rare:" . $weatherArray["rare"] . "</p>";
            $html .= "<tr><td><center>$daysFromCurrent</center></td><td><center>" .$weatherArray["normal"] ."</center></td><td><center>" . $weatherArray["common"] . "</center></td><td><center>" . $weatherArray["rare"] . "</center></td></tr>";

        }


        return 	$html;
    }
}

?>