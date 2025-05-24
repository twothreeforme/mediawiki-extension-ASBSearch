<?php


class FFXIPackageHelper_ShowMobDropTable  {
    public function __construct() {
    }
    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('ShowMobDropTable','FFXIPackageHelper_ShowMobDropTable::generateDropsTable' );
        return true;
	}

    static function _tableHeaders(){
		$html = "";
		$html .= "
		<div id=\"FFXIPH_ShowMobDropTable\" >
            <p>Test data goes here</p>
			</div>";
		return $html;
	}

    public static function generateDropsTable( $input, array $params, Parser $parser, PPFrame $frame ) {
        $html = "";

        if(!isset($params['class'])){
            $classname = "horizon-table general-table sortable";
        }
        else $classname = $params['class'];
        
        if(!isset($params['mob'])){
            $pagetitle = $parser->getTitle();
        }
        else $pagetitle = $params['mob'];
        //$html .= $pagetitle;

        $db = new DBConnection();
        $mobDropsRAW = $db->getMobDropRates($pagetitle);

        $dm = new DataModel();
        $dm->parseMobDropData($mobDropsRAW);
        $mobDropsModel = $dm->getDataSet();

        //wfDebugLog( 'ShowMobDrops', get_called_class() . ":" . json_encode( $mobDropsModel) );

        $html .= FFXIPackageHelper_HTMLTableHelper::table_MobDropRates($mobDropsModel, $classname);

    //     if(isset($params['zone'])) {
    //         $pagename = (string)$parser->recursiveTagParse($params['zone'], $frame );
    //         //$html = $parser->recursiveTagParse( $html, $frame );
    //         //print_r("if: " . gettype($pagename));
        
    //     }
    //     else {
    //         $pagename = $parser->getTitle()->__toString();
    //         //print_r("else: " . gettype($pagename));

    //     }
    //     // $pagename = $pagename->getText() ;

        
    //     //$name = ParserHelper::zoneName($row['name']);
    //     //print_r($pagename);
    //    // print_r(str_replace("\'", "", $pagename));
    //     $pagename = ParserHelper::replaceApostrophe($pagename);
    //     $pagename = ParserHelper::zoneERA_forQuery($pagename);
    //     //var_dump($pagename);
        
    //     foreach ( $zoneList as $zone){
    //         if ( $zone->name == $pagename) {
    //             $zoneid = $zone->zoneid;
    //             break;
    //         }
    //         else if ( $zone->zoneid == 4 && $pagename == 'Bibiki_Bay-Purgonorgo_Isle' ){
    //             $zoneid = $zone->zoneid;
    //             break;
    //         }
    //         else if ( $zone->zoneid == 29 && $pagename == 'Riverne-Site_#B01' ){
    //             $zoneid = $zone->zoneid;
    //             break;
    //         }
    //         else if ( $zone->zoneid == 30 && $pagename == 'Riverne-Site_#A01' ){
    //             $zoneid = $zone->zoneid;
    //             break;
    //         }
    //     }
    
         //$html = self::_tableHeaders();

    //     $html .= "</table></div>";

    //     $html = $parser->recursiveTagParse( $html, $frame );
        $html = $parser->recursiveTagParse( $html, $frame );

    //     //$html = $parserOutput->getText();
        
        return 	$html;
    }
}

?>