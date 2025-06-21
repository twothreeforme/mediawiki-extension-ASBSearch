<?php


class ZoneForecast  {
    public function __construct() {
    }
    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('ZoneForecast','ZoneForecast::generateZoneSpecificTable' );
        return true;
	}

    static function _tableHeaders(){
		$html = "";
		$html .= "
		<div class=\"zone-infobox-weather-div\" >
		<table class=\"zone-infobox-weather-table\">
			<tr><th>VanaDays from Today</th>
			<th>Normal (50%)</th>
			<th>Common (35%)</th>
			<th>Rare (15%)</th>
			";
		return $html;
	}

    public static function generateZoneSpecificTable( $input, array $params, Parser $parser, PPFrame $frame ) {
        $db = new DatabaseQueryWrapper();
        $zoneList = $db->getZoneList();

        $zoneid = 0;
        if(isset($params['zone'])) {
            $pagename = (string)$parser->recursiveTagParse($params['zone'], $frame );
            //$html = $parser->recursiveTagParse( $html, $frame );
            //print_r("if: " . gettype($pagename));
        
        }
        else {
            $pagename = $parser->getTitle()->__toString();
            //print_r("else: " . gettype($pagename));

        }
        // $pagename = $pagename->getText() ;

        
        //$name = ParserHelper::zoneName($row['name']);
        //print_r($pagename);
       // print_r(str_replace("\'", "", $pagename));
        $pagename = ParserHelper::replaceApostrophe($pagename);
        $pagename = ParserHelper::zoneERA_forQuery($pagename);
        //var_dump($pagename);
        
        foreach ( $zoneList as $zone){
            if ( $zone->name == $pagename) {
                $zoneid = $zone->zoneid;
                break;
            }
            else if ( $zone->zoneid == 4 && $pagename == 'Bibiki_Bay-Purgonorgo_Isle' ){
                $zoneid = $zone->zoneid;
                break;
            }
            else if ( $zone->zoneid == 29 && $pagename == 'Riverne-Site_#B01' ){
                $zoneid = $zone->zoneid;
                break;
            }
            else if ( $zone->zoneid == 30 && $pagename == 'Riverne-Site_#A01' ){
                $zoneid = $zone->zoneid;
                break;
            }
        }
        
        //var_dump($zoneid);
        
        if ( $zoneid == 0 ){
            return "<div>Error: Forecast for ' $pagename ' not found. Please report to Wiki devs on Discord. </div>";
        }

        $zoneWeather = $db->getZoneWeather($zoneid, 1) ;
    
        $html = ZoneForecast::_tableHeaders();

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