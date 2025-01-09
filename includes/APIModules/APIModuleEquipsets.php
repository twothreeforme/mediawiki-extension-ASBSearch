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

        //throw new Exception($params['action']);

        //$equipmentList = (explode(",",$params['equipment']));

        //$finalHtml = $this->queryEquipment($queryData);
        //$finalHtml = ParserHelper::wikiParse($finalHtml);
        if ( $params['action'] == "equipsets" ) {
            // throw new Exception($params['equipment']);
            $equipmentModel = new FFXIPackageHelper_Equipment( $params['equipment'] );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $newEquipmentArray );

            $result->addValue($params['action'], $params['querytype'], $newStats->getStats());

        }
        else if ( $params['action'] == "equipsets_search" ) {
            $db = new DBConnection();
            $dm = new DataModel();


            $equipList = $db->getEquipment( $params['search'], $params['mlvl'], $params['slot']);
            //$finalList = $dm->parseEquipment( $equipList, $params['mjob'] );

            //throw new Exception(json_encode($finalList));
            //$result->addValue($params['action'], $params['querytype'], [$finalList, $params['slot']]);
            $result->addValue($params['action'], $params['querytype'], $params['search'] );
        }
        else if ( $params['action'] == "equipsets_change" ) {

            // $equipIDs = [];
            // $incomingEquipmentList = explode('|', $params['equipment']);
            // for ( $i = 0; $i <= 15; $i++){
            //     $temp = explode(',', $incomingEquipmentList[$i]);
            //     $incomingEquipmentList[$i] = [ $temp[0], "", $temp[1] ];
            //     $equipIDs[] =  $temp[0];
            // }

            // need all equipment
            //throw new Exception($params['equipment']);
            $equipmentModel = new FFXIPackageHelper_Equipment( $params['equipment'] );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $newEquipmentArray );

            // send updated HTML back as result
            $incomingEquipmentList = $equipmentModel->getIncomingEquipmentList();

            $tabEquipsets = new FFXIPackageHelper_Equipsets();
            $updatedGrid = $tabEquipsets->updateGridItems($incomingEquipmentList);

            //$updatedStats = $newStats->getStats();
            //throw new Exception (  json_encode($updatedStats)) ;

            $result->addValue($params['action'], $params['querytype'], [ $newStats->getStats(), $updatedGrid]);
        }
    }
}


?>