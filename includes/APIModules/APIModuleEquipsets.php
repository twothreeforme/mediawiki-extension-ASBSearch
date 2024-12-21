<?php

use ApiBase;

class APIModuleEquipsets extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'race' => "",
            'mlvl' => "",
            'slvl' => "",
            'mjob' => "",
            'sjob' => "",
            'equipment' => "",
            'search' => "",
            'slot' => "",
    		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        //$equipmentList = (explode(",",$params['equipment']));

        //$finalHtml = $this->queryEquipment($queryData);
        //$finalHtml = ParserHelper::wikiParse($finalHtml);
        if ( $params['action'] == "equipsets" ) {
            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $params['equipment'] );
            $result->addValue($params['action'], $params['querytype'], $newStats->getStats());
        }
        else if ( $params['action'] == "equipsets_search" ) {
            $db = new DBConnection();
            $dm = new DataModel();

            $equipList = $db->getEquipment($params['search'], $params['mlvl']);
            $finalList = $dm->parseEquipment( $equipList, $params['mjob'] );

            $result->addValue($params['action'], $params['querytype'], [$finalList, $params['slot'] ]);
        }
    }
}


?>