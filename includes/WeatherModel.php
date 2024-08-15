<?php


class WeatherModel  {
    public function __construct() {
    }
    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('ZoneForecast','WeatherModel::generateZoneSpecificTable' );
        return true;
	}

    static function _tableHeaders(){
		$html = "";
		$html .= "<br>
		<div class=\"zone-infobox-weather-div\" >
		<table class=\"sortable zone-infobox-weather-table\">
			<tr><th>VanaDays from Today</th>
			<th>Normal (50%)</th>
			<th>Common (35%)</th>
			<th>Rare (15%)</th>
			";
		return $html;
	}

    public static function generateZoneSpecificTable( $input, array $params, Parser $parser, PPFrame $frame ) {
        $db = new DBConnection();
        $zoneList = $db->getZoneList();

        $zoneid = 0;
        if(isset($params['zone'])) {
            $pagename = $parser->recursiveTagParse($params['zone'], $frame );
            //$html = $parser->recursiveTagParse( $html, $frame );
        }
        else $pagename = $parser->getTitle();
        foreach ( $zoneList as $zone){
            $pagename = ParserHelper::replaceApostrophe($pagename);
            $pagename = ParserHelper::replaceSpaces($pagename);
            if ( $zone->name == $pagename) {
                $zoneid = $zone->zoneid;
                break;
            }
        }
        if ( $zoneid == 0 ){
            return "<div>Error: Forecast for ' $pagename ' not found. Please report to Wiki devs on Discord. </div>";
        }

        $zoneWeather = $db->getZoneWeather($zoneid, 1) ;
    
        $html = WeatherModel::_tableHeaders();

        //$html = $html . "<p>Vana Days from now... Normal (50%)... Common (35%)... Rare (15%)... </p>";

        foreach ($zoneWeather as $daysFromCurrent => $weatherArray) {

            $daysFromCurrent = $daysFromCurrent == 0 ? "Today" : $daysFromCurrent ;
            $daysFromCurrent = $daysFromCurrent == 1 ? "Tomorrow" : $daysFromCurrent ;
            //$html = $html . "<p>" . $daysFromCurrent . " ... " . "  Normal:" . $weatherArray["normal"] . "  Common:" . $weatherArray["common"] . "  Rare:" . $weatherArray["rare"] . "</p>";
            $html .= "<tr><td><center>$daysFromCurrent</center></td><td><center>" .$weatherArray["normal"] ."</center></td><td><center>" . $weatherArray["common"] . "</center></td><td><center>" . $weatherArray["rare"] . "</center></td></tr>";
        }

        $html .= "</table></div>";

        $html = $parser->recursiveTagParse( $html, $frame );

        //$html = $parserOutput->getText();

        return 	$html;
    }
}

?>