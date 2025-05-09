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
            'slot' => null,
            'charname' => null,
            'def' => 0,
            'setname' => "",
            'usersetid' => 0,
    		];
	}

    function execute( ) {

        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $decoded = urldecode($params['equipment']);
        $equipmentString = base64_decode($decoded);
        //if ( str_contains($equipmentString, "11335") )  throw new Exception($equipmentString); //Snipers Ring debug

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

            $searchString = ParserHelper::replaceApostrophe($params['search']);
            $searchString = ParserHelper::replaceSpaces($searchString);

            $equipList = $db->getEquipment( $searchString, $params['mlvl'], $params['slot']);
            $finalList = $dm->parseEquipment( $equipList, $params['mjob'] );

            //throw new Exception(json_encode($finalList));

            $result->addValue($params['action'], "search", [$finalList, $params['slot']]);
            //$result->addValue($params['action'], $params['querytype'], $params['search'] );
        }
        else if ( $params['action'] == "equipsets_change" ) {
            //throw new Exception ( json_encode($equipmentString));
            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            //throw new Exception ( json_encode($equipmentModel));

            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $meritsString, $newEquipmentArray );
            
            //throw new Exception ( json_encode($newEquipmentArray));

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
            $result->addValue($params['action'], "stats", $statsURLSafe );

            $gridEncoded = base64_encode(json_encode($updatedGrid));
            $gridURLSafe = urlencode($gridEncoded);
            $result->addValue($params['action'], "grid", $gridURLSafe );

            // $labelsEncoded = base64_encode(json_encode($this->parseEquipmentLabels($newEquipmentArray)));
            // $labelsURLSafe = urlencode($labelsEncoded);
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels($newEquipmentArray) );

            $luaNamesEncoded = base64_encode(json_encode($luaNamesArray));
            $luaNamesURLSafe = urlencode($luaNamesEncoded );
            $result->addValue( $params['action'], "luaNames", $luaNamesURLSafe );

            //throw new Exception ( "here" );
            // $result->addValue($params['action'], "stats", $newStats->getStats() );
            // $result->addValue($params['action'], "grid", $updatedGrid );
            
            //if ( $params['sjob'] == 1  ) throw new Exception ( json_encode($incomingEquipmentList) ."::::\n". json_encode($updatedGrid) );
            //throw new Exception (json_encode($result));
        }
        else if ( $params['action'] == "equipsets_savechar" ) {
            $char = $this->createChar($params);

            if ( $char['userid'] == 0 || $char['userid'] == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            //check user for existing character name
            $db = new DBConnection();

            $userCharacters = $db->getUserCharacters($char, true);

            if( gettype($userCharacters) == "string" ){
                // character name already exists, should return error
                //throw new Exception ( json_encode($userCharacters));
                $result->addValue( $params['action'], "status", ["ERROR", "Character name already exists for user."] );
                return;
            }
            else{
                if ( $params['def'] == 1 ){
                    //search for user with default already applied
                    // throw new Exception ( json_encode($userCharacters));
                    foreach ( $userCharacters as &$userChar){
                        if ( $userChar['def'] == 1 ) {
                            $db->removeUserCharDefault($userChar);
                            $userChar['def'] = 0;
                            break;
                        }
                    }
                }

                //char name is new and should be saved
                $db->setUserCharacter($char);
                $userCharacters[] = $char;
                $result->addValue( $params['action'], "status", [$params['charname'], $userCharacters] );
                return;
            }
        }
        else if ( $params['action'] == "equipsets_updatechar" ) {
            //throw new Exception ( json_encode($params) );
            $db = new DBConnection();
            $char = $this->createChar($params);

            if ( $char['userid'] == 0 || $char['userid'] == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            $userCharacters = $db->getUserCharacters($char, false);

            if ( $params['def'] == 1 ){
                //search for user with default already applied
                // throw new Exception ( json_encode($userCharacters));
                foreach ( $userCharacters as &$userChar){
                    if ( $userChar['def'] == 1 ) {
                        $db->removeUserCharDefault($userChar);
                        $userChar['def'] = 0;
                        break;
                    }
                }
            }

            $db->updateUserCharacter($char);

            $userCharacters = $db->getUserCharacters($char, false);

            $result->addValue( $params['action'], "status", [$params['charname'], $userCharacters] );
            //$result->addValue( $params['action'],  "status", "great");
            //throw new Exception ( json_encode( [$params['charname'], $userCharacters]));
        }
        else if ( $params['action'] == "equipsets_selectchar" ) {
            $db = new DBConnection();
            $char = $this->createChar($params);

            $selectedChar = $db->getSelectedCharacter($char);
            $result->addValue( $params['action'], "selectchar", $selectedChar );
        }
        else if ( $params['action'] == "equipsets_removechar" ) {
            $db = new DBConnection();
            $char = $this->createChar($params);
            $db->removeUserCharacter($char);
            $userCharacters = $db->getUserCharacters($char);
            $result->addValue( $params['action'], "userchars", $userCharacters);
        }
        else if ( $params['action'] == "equipsets_saveset" ) {
            $newSet = $this->createSet($params);
            if ( $newSet['userid'] == 0 || $newSet['userid'] == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            $db = new DBConnection();
            $db->saveSet($newSet);
            //$setList = $db->getUserSetsFromUserID($newSet['userid']);
            $setList = $db->getUserSetsForJob($newSet['userid'], $newSet['mjob']);
            $result->addValue( $params['action'], "saveset", [$newSet['setname'], $setList] );
        }
        else if ( $params['action'] == "equipsets_selectset" ) {
            $db = new DBConnection();

            $fetchedSet = $db->fetchSet($params['usersetid']);
            //throw new Exception ( json_encode( $fetchedSet));

            $decodedEquip = urldecode($fetchedSet['equipment']);
            $equipmentString = base64_decode($decodedEquip);

            $decodedMerits = urldecode($fetchedSet['merits']);
            $meritsString = base64_decode($decodedMerits);

            $tabEquipsets = new FFXIPackageHelper_Equipsets();

            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $incomingEquipmentList = $equipmentModel->getIncomingEquipmentList();
            $updatedGrid = $tabEquipsets->updateGridItems($incomingEquipmentList, true)[0];
            $luaNamesArray = $tabEquipsets->updateGridItems($incomingEquipmentList)[1];

            $newStats = new FFXIPackageHelper_Stats( $fetchedSet['race'], $fetchedSet['mlvl'], $fetchedSet['slvl'], $fetchedSet['mjob'], $fetchedSet['sjob'], $meritsString, $newEquipmentArray );

            $stats = $newStats->getStats();
            $statsEncoded = base64_encode(json_encode($stats));
            $statsURLSafe = urlencode($statsEncoded);

            $gridEncoded = base64_encode(json_encode($updatedGrid));
            $gridURLSafe = urlencode($gridEncoded);

            $luaNamesEncoded = base64_encode(json_encode($luaNamesArray));
            $luaNamesURLSafe = urlencode($luaNamesEncoded );

            $result->addValue( $params['action'], "selectset", $fetchedSet );
            $result->addValue($params['action'], "stats", $statsURLSafe );
            $result->addValue($params['action'], "grid", $gridURLSafe );
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels($newEquipmentArray) );
            $result->addValue( $params['action'], "luaNames", $luaNamesURLSafe );

        }
        else if ( $params['action'] == "equipsets_getsets" ) {
            $newSet = $this->createSet($params);

            // if ( $newSet['userid'] == 0 || $newSet['userid'] == null ){
            //     $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
            //     return;
            // }

            $db = new DBConnection();
            //throw new Exception ( json_encode( $params));
            if ( $newSet['mjob'] != 0 ) $setList = $db->getUserSetsForJob($newSet['userid'], $newSet['mjob']);
            else {
                $setList = $db->getUserSetsFromUserID($newSet['userid']);
            }
            $result->addValue( $params['action'], "getsets", $setList );

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

    private function createChar($params){
        $user = RequestContext::getMain()->getUser();
        return [
            'userid' => $user->getId(),
            'charname' => $params['charname'],
            'race' => $params['race'] ,
            'merits' => $params['merits'],
            'def' => $params['def']
        ];
    }

    private function createSet($params){
        $user = RequestContext::getMain()->getUser();
        return [
            'userid' => $user->getId(),
            'setname' => $params['setname'],
            'mlvl' => $params['mlvl'],
            'slvl' => $params['slvl'],
            'mjob' => $params['mjob'],
            'sjob' => $params['sjob'],
            'equipment' => $params['equipment']
        ];
    }
}


?>