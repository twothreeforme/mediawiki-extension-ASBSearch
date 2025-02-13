<?php

use ApiBase;

class APIModuleEquipsets extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'race' => 0,
            'mlvl' => 0,
            'slvl' => 0,
            'mjob' => 0,
            'sjob' => 0,
            'merits' => "",
            'equipment' => "",
            'search' => "",
            'slot' => 0,
            'charname' => null,
    		];
	}

    function execute( ) {

        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $decoded = urldecode($params['equipment']);
        $equipmentString = base64_decode($decoded);
        //if ( !str_contains($equipmentString, "13280") )  throw new Exception($equipmentString); //Snipers Ring debug

        $decoded = urldecode($params['merits']);
        $meritsString = base64_decode($decoded);
        //throw new Exception($meritsString);

        if ( $params['action'] == "equipsets" ) {
            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();

            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $meritsString, $newEquipmentArray );

            $result->addValue( $params['action'], "stats", $newStats->getStats() );
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels($newEquipmentArray) );

            //if ( $params['sjob'] == 4 || $params['sjob'] == 3 ) throw new Exception ( json_encode([$params['action'], "stats", $newStats->getStats()]) );
        }
        else if ( $params['action'] == "equipsets_search" ) {
            $db = new DBConnection();
            $dm = new DataModel();


            $equipList = $db->getEquipment( $params['search'], $params['mlvl'], $params['slot']);
            $finalList = $dm->parseEquipment( $equipList, $params['mjob'] );

            //throw new Exception(json_encode($finalList));

            $result->addValue($params['action'], "search", [$finalList, $params['slot']]);
            //$result->addValue($params['action'], $params['querytype'], $params['search'] );
        }
        else if ( $params['action'] == "equipsets_change" ) {

            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $meritsString, $newEquipmentArray );
            
            // if( $newEquipmentArray[8]["id"] == 0 ) {
            //     throw new Exception ( json_encode($newEquipmentArray) . "\n:::\n" . json_encode($equipmentModel));
            // }

            // send updated HTML back as result
            $incomingEquipmentList = $equipmentModel->getIncomingEquipmentList();

            $tabEquipsets = new FFXIPackageHelper_Equipsets();
            $updatedGrid = $tabEquipsets->updateGridItems($incomingEquipmentList)[0];
            $luaNamesArray = $tabEquipsets->updateGridItems($incomingEquipmentList)[1];


            $stats = $newStats->getStats();
            //if ( !str_contains($equipmentString, "13529") ) throw new Exception ( "here" ); //Alacrity Ring

            $statsEncoded = base64_encode(json_encode($stats));
            $statsURLSafe = urlencode($statsEncoded);
            // $result->addValue($params['action'], "stats", $statsURLSafe );

            $gridEncoded = base64_encode(json_encode($updatedGrid));
            $gridURLSafe = urlencode($gridEncoded);
            // $result->addValue($params['action'], "grid", $gridURLSafe );

            $labelsEncoded = base64_encode(json_encode($this->parseEquipmentLabels($newEquipmentArray)));
            $labelsURLSafe = urlencode($labelsEncoded);
            // $result->addValue( $params['action'], "equipLabels", $labelsURLSafe );

            $luaNamesEncoded = base64_encode(json_encode($luaNamesArray));
            $luaNamesURLSafe = urlencode($luaNamesEncoded );
            // $result->addValue( $params['action'], "luaNames", $luaNamesURLSafe );

            //throw new Exception ( "here" );
            $result->addValue($params['action'], "all", [$statsURLSafe, $gridURLSafe, $labelsURLSafe, $luaNamesURLSafe ] );
            // $result->addValue($params['action'], "grid", $updatedGrid );
            
            //if ( $params['sjob'] == 1  ) throw new Exception ( json_encode($incomingEquipmentList) ."::::\n". json_encode($updatedGrid) );
            //throw new Exception (json_encode($result));
        }
        else if ( $params['action'] == "equipsets_savechar" ) {
            //throw new Exception($params['charname']);
            $user = RequestContext::getMain()->getUser();
            $uid = $user->getId();
            if ( $uid == 0 || $uid == null ){
                $result->addValue( $params['action'], "savecharERROR", "User must be logged in to complete this task." );
                return;
            }

            $db = new DBConnection();
            $userCharacters = $db->getUserCharacters($uid);
            if( count($userCharacters) == 0 ){
                throw new Exception ( "No characters found ");
            }
            //check user for existing character name

            //if doesnt exist then save


        }
    }

    private function parseEquipmentLabels($equipmentArray){
        $equipLabelsArray = [ ];
        for ( $i = 0; $i <= 15; $i++ ){
            $labelHtml = " - ";
            if ( $equipmentArray[$i][5] != null && $equipmentArray[$i][5] != "" ) $labelHtml = ParserHelper::wikiParse( "[[" . $equipmentArray[$i][5] . "]]" );
            $equipLabelsArray[$i] = $labelHtml;
        }
        return $equipLabelsArray;
    }
}


?>