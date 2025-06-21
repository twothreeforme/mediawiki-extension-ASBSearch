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

        $db = new DatabaseQueryWrapper();
        $mobDropsRAW = $db->getMobDropRates($pagetitle);

        $dm = new DataModel();
        $dm->parseMobDropData($mobDropsRAW);
        $mobDropsModel = $dm->getDataSet();

        //wfDebugLog( 'ShowMobDrops', get_called_class() . ":" . json_encode( $mobDropsModel) );

        $html .= FFXIPackageHelper_HTMLTableHelper::table_MobDropRates($mobDropsModel, $classname);

   
        $html = $parser->recursiveTagParse( $html, $frame );

        //$html = $parserOutput->getText();
        
        return 	$html;
    }
}

?>