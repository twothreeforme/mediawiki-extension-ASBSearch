<?php

use ApiBase;

class APIModuleEquipsets extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'race' => null,
            'mlvl' => 0,
            'slvl' => 0,
            'mjob' => 0,
            'sjob' => 0,
            'merits' => null,
            'equipment' => null,
            'search' => "",
            'slot' => null,
            'charname' => null,
            'def' => 0,
            'setname' => "",
            'usersetid' => 0,

            // Combatsim search
            'mobname' => null,
            'zonename' => null,
            'moblvl' => null
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

        //wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . json_encode( $params) );


        if ( $params['action'] == "equipsets" ) {
            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            $newEquipmentArray = $equipmentModel->getEquipmentArray();

            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $meritsString, $newEquipmentArray );
            $stats =  $newStats->getStats();
            //if ( $params['mjob'] == 6 ) throw new Exception ( json_encode($params) );

            $char = $this->createChar($params, $meritsString, $newEquipmentArray );
            $equipsets = new FFXIPackageHelper_Equipsets($char);

            $result->addValue( $params['action'], "stats", $equipsets->statsSection($stats) );
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels($newEquipmentArray) );

        }
        else if ( $params['action'] == "equipsets_search" ) {
            $resultsHTML = FFXIPackageHelper_QueryController::queryEquipsetsSearchItems($params);
            //throw new Exception ( $resultsHTML  );
            $result->addValue($params['action'], "search", [$resultsHTML, $params['slot']]);
        }
        else if ( $params['action'] == "equipsets_change" ) {
            //throw new Exception ( json_encode($equipmentString));
            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            //throw new Exception ( json_encode($equipmentModel));

            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $char = $this->createChar($params, $meritsString, $newEquipmentArray);

            $newStats = new FFXIPackageHelper_Stats( $params['race'], $params['mlvl'], $params['slvl'], $params['mjob'], $params['sjob'], $meritsString, $newEquipmentArray );
            
            // send updated HTML back as result
            $incomingEquipmentList = $equipmentModel->getIncomingEquipmentList();


            $tabEquipsets = new FFXIPackageHelper_Equipsets($char);
            $updatedGrid = $tabEquipsets->updateGridItems($incomingEquipmentList)[0];
            $luaNamesArray = $tabEquipsets->updateGridItems($incomingEquipmentList)[1];

            $stats = $newStats->getStats();
//wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . json_encode( [$stats, $newEquipmentArray]  ) );
            // $statsEncoded = base64_encode(json_encode($stats));
            // $statsURLSafe = urlencode($statsEncoded);
            //$result->addValue($params['action'], "stats", $statsURLSafe );
            $result->addValue( $params['action'], "stats", $tabEquipsets->statsSection($stats) );
            $result->addValue( $params['action'], "resistances", $tabEquipsets->resistances($stats) );

            $gridEncoded = base64_encode(json_encode($updatedGrid));
            $gridURLSafe = urlencode($gridEncoded);
            $result->addValue($params['action'], "grid", $gridURLSafe );

            // $labelsEncoded = base64_encode(json_encode($this->parseEquipmentLabels($newEquipmentArray)));
            // $labelsURLSafe = urlencode($labelsEncoded);
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels( $newEquipmentArray) );

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
            $char = $this->createChar($params, $params['merits']);

           //wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . json_encode( $char) );

            if ( $char->userid == 0 || $char->userid == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            //check user for existing character name
            $db = new DatabaseQueryWrapper();

            $userCharacters = $db->getUserCharacters($char, true);

            if( gettype($userCharacters) == "string" ){
                // character name already exists, should return error
                //throw new Exception ( json_encode($userCharacters));
                $result->addValue( $params['action'], "status", ["ERROR", "Character name already exists for user."] );
                return;
            }
            else{
                // trying to save edits to a char, and the default flag is set
                if ( $params['def'] == 1 ){
                        // search for user with default already applied
                        // throw new Exception ( json_encode($userCharacters));
                        foreach ( $userCharacters as &$userChar){
                            if ( $userChar->def == 1 ) {
                                $db->removeUserCharDefault($userChar);
                                $userChar->def = 0;
                                break;
                            }
                        }
                }

                //char name is new and should be saved
                $db->setUserCharacter($char);
                $userCharacters[] = $char;
                $result->addValue( $params['action'], "status", [$params['charname'], $userCharacters] );


                $charSelectBar = $this->getCharSelectButtonsBar($userCharacters, $params['charname']);
                $result->addValue( $params['action'], "charSelectButtonsBar", $charSelectBar );

                return;
            }
        }
        else if ( $params['action'] == "equipsets_updatechar" ) {
//wfDebugLog( 'Equipsets', "***");
            //throw new Exception ( json_encode($params) );
            $db = new DatabaseQueryWrapper();
            $char = $this->createChar($params, $params['merits']);

//wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . $params['merits'] );

            if ( $char->userid == 0 || $char->userid == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            $userCharacters = $db->getUserCharacters($char, false);

            // trying to save edits to a char, and the default flag is set
           if ( $params['def'] == 1 ){
                // search for user with default already applied
                // throw new Exception ( json_encode($userCharacters));
                foreach ( $userCharacters as &$userChar){
                    if ( $userChar->def == 1 ) {
                        $db->removeUserCharDefault($userChar);
                         $userChar->def = 0;
                        break;
                    }
                }
           }

            $db->updateUserCharacter($char);

            $userCharacters = $db->getUserCharacters($char, false);

            $result->addValue( $params['action'], "status", [$params['charname'], $userCharacters] );

            $charSelectBar = $this->getCharSelectButtonsBar($userCharacters, $params['charname']);
            $result->addValue( $params['action'], "charSelectButtonsBar", $charSelectBar );
//wfDebugLog( 'Equipsets', "***");

        }
        else if ( $params['action'] == "equipsets_selectchar" ) {
            $db = new DatabaseQueryWrapper();

            $char = $this->createChar($params);
            
            if ( is_null($params['charname']) || $params['charname'] == ""  ){
                $selectedChar = $this->createChar($params);
                // [
                //     'charname' => null,
                //     'charid' => null,
                //     'race' => 0,
                //     'merits' => null,
                //     'def' => 0
                // ];
            }
            else {
                $selectedChar = $db->getSelectedCharacter($char);
            }
            
            $userCharacters = $db->getUserCharacters($selectedChar, false);

            $tabEquipsets = new FFXIPackageHelper_Equipsets($char);

            $result->addValue( $params['action'], "selectchar", $selectedChar->toURLsafeArray() );

            $result->addValue( $params['action'], "showcharacters", $tabEquipsets->showCharacters( $userCharacters, false, $selectedChar) );
            //$result->addValue( $params['action'], "merits", $tabEquipsets->showMeritsTable($selectedChar) );

        }
        else if ( $params['action'] == "equipsets_removechar" ) {
            $db = new DatabaseQueryWrapper();
            $char = $this->createChar($params);
            $db->removeUserCharacter($char);
            $userCharacters = $db->getUserCharacters($char);
            $result->addValue( $params['action'], "userchars", $userCharacters);


            $result->addValue( $params['action'], "charSelectButtonsBar", $this->getCharSelectButtonsBar($userCharacters, false));
        }
        else if ( $params['action'] == "equipsets_saveset" ) {
            $newSet = $this->createSet($params);
            if ( $newSet['userid'] == 0 || $newSet['userid'] == null ){
                $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
                return;
            }

            $db = new DatabaseQueryWrapper();
            $db->saveSet($newSet);
            $setList = $db->getUserSetsForJob($newSet['userid'], $newSet['mjob']);

            //$result->addValue( $params['action'], "saveset", [$newSet['setname'], $setList] );
            $result->addValue( $params['action'], "getsets", $this->getHTMLSetsListTable($db, $newSet['userid']) );

        }
        else if ( $params['action'] == "equipsets_selectset" ) {
            $db = new DatabaseQueryWrapper();

            $fetchedSet = $db->fetchSet($params);
            
            $decodedEquip = urldecode($fetchedSet['equipment']);
            $equipmentString = base64_decode($decodedEquip);

            $decodedMerits = urldecode($fetchedSet['merits']);
            $meritsString = base64_decode($decodedMerits);

            $char = $this->createChar($params, $params['merits'], $newEquipmentArray);
            $tabEquipsets = new FFXIPackageHelper_Equipsets($char);

            $equipmentModel = new FFXIPackageHelper_Equipment( $equipmentString );
            $incomingEquipmentList = $equipmentModel->getIncomingEquipmentList();
            $updatedGridItems = $tabEquipsets->updateGridItems($incomingEquipmentList, true);
            $updatedGrid = $updatedGridItems[0];
            $luaNamesArray = $updatedGridItems[1];

            $newEquipmentArray = $equipmentModel->getEquipmentArray();
            $newStats = new FFXIPackageHelper_Stats( $fetchedSet['race'], $fetchedSet['mlvl'], $fetchedSet['slvl'], $fetchedSet['mjob'], $fetchedSet['sjob'], $meritsString, $newEquipmentArray );
            
            $stats = $newStats->getStats();
            // if ( $fetchedSet['mjob'] == 6 ) throw new Exception ( json_encode($fetchedSet) );

            $statsEncoded = base64_encode(json_encode($stats));
            $statsURLSafe = urlencode($statsEncoded);

            $gridEncoded = base64_encode(json_encode($updatedGrid));
            $gridURLSafe = urlencode($gridEncoded);

            $luaNamesEncoded = base64_encode(json_encode($luaNamesArray));
            $luaNamesURLSafe = urlencode($luaNamesEncoded );

            $result->addValue( $params['action'], "selectset", $fetchedSet );
            // $result->addValue($params['action'], "stats", $statsURLSafe );

            $result->addValue($params['action'], "stats", $tabEquipsets->statsSection($stats) );
            $result->addValue( $params['action'], "resistances", $tabEquipsets->resistances($stats) );

            $result->addValue($params['action'], "grid", $gridURLSafe );
            $result->addValue( $params['action'], "equipLabels", $this->parseEquipmentLabels( $newEquipmentArray) );
            $result->addValue( $params['action'], "luaNames", $luaNamesURLSafe );

        }
        else if ( $params['action'] == "equipsets_getsets" ) {
            $newSet = $this->createSet($params);

            // if ( $newSet['userid'] == 0 || $newSet['userid'] == null ){
            //     $result->addValue( $params['action'], "status", ["ERROR", "User must be logged in to save anything."] );
            //     return;
            // }

            $db = new DatabaseQueryWrapper();
            //throw new Exception ( json_encode( $params));
            if ( $newSet['mjob'] != 0 ) {
                $newSetList = $db->getUserSetsForJob($newSet['userid'], $newSet['mjob']);
            }
            else {
                $newSetList = $db->getUserSetsFromUserID($newSet['userid']);
            }
            $setList = FFXIPackageHelper_HTMLOptions::setsListTable($newSetList);
            $result->addValue( $params['action'], "getsets", $setList );

        }
        else if ( $params['action'] == "equipsets_removeset" ) {
            $newSet = $this->createSet($params);
            //throw new Exception ( json_encode( $newSet));
            $db = new DatabaseQueryWrapper();
            $setList = $db->getUserSetsFromUserID($newSet['userid']);

            foreach($setList as $jobSets){
                foreach($jobSets as $jobSet){
                    if ( $jobSet["usersetid"] ==  $newSet['usersetid']){
                        //trigger removing
                        $db->removeSet($newSet['userid'], $newSet['usersetid']);

                        //$sendSetList = $this->getHTMLSetsListTable($db, $newSet['userid']);

                        // $userSets = $db->getUserSetsFromUserID($newSet['userid']);
                        // $sendSetList = FFXIPackageHelper_HTMLOptions::setsListTable($userSets);

                        $result->addValue( $params['action'], "getsets", $this->getHTMLSetsListTable($db, $newSet['userid']) );
                        $result->addValue( $params['action'], "status", ["Set removed from users set list."] );
                        return;
                    }
                }
            }

            $result->addValue( $params['action'], "status", ["ERROR", "This user cannot remove this set."] );
        }
        else if ( $params['action'] == "combatsim_mobsearch" ) {
            // throw new Exception( $params['mobname'] );
            $db = new DatabaseQueryWrapper();
            $dm = new DataModel();

            $mobandzone = $db->getMobAndZone($params['mobname'], $params['zonename']);
            $dm->parseMobZoneList($mobandzone);

            // throw new Exception( json_encode( $dm->getDataSet() ) );
            $final = FFXIPackageHelper_HTMLTableHelper::table_MobAndZoneList( $dm->getDataSet() );
            if ( strlen($final) > 0 ){
                $finalHtml = ParserHelper::wikiParse($final);
                $result->addValue( $params['action'], "moblisttable", $finalHtml );
            }
            else {
                $result->addValue( $params['action'], "noresults", "<i><b> No records (mobs) found</i></b>" );
            }
        }
        else if ( $params['action'] == "combatsim_selectedmob" ) {
            
            $db = new DatabaseQueryWrapper();
            $mob = $db->getMob($params['mobname'], $params['zonename']);
            wfDebugLog( 'Equipsets', get_called_class() . ":" . $params['action'] . ":" . $mob->getName() . ":" . $mob->getHP() . ":" . $mob->getMP() );
            
        }

    }

    private function getHTMLSetsListTable($db, $uid){
        $userSets = $db->getUserSetsFromUserID($uid);
        if ( count($userSets) > 0 ){
            return FFXIPackageHelper_HTMLOptions::setsListTable($userSets);
        }
        else return "";
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


    private function createChar($params, $meritsURLSafe = null, $equipmentString = null){
        //$user = RequestContext::getMain()->getUser();
        return new FFXIPH_Character($params['race'], null, null, null, null,
                            $meritsURLSafe, null, $params['def'], $params['charname'], null);
    }

    private function createSet($params){
        $user = RequestContext::getMain()->getUser();
        return [
            'userid' => $user->getId(),
            'setname' => $params['setname'],
            'race' => $params['race'],
            'mlvl' => $params['mlvl'],
            'slvl' => $params['slvl'],
            'mjob' => $params['mjob'],
            'sjob' => $params['sjob'],
            'equipment' => $params['equipment'],
            'usersetid' => $params['usersetid']
        ];
    }


    private function getCharacterHeader(){
        //$user = RequestContext::getMain()->getUser();
        $uid = self::getUserID();
        if ( $uid != 0 ) { // User is logged in
            $db = new DatabaseQueryWrapper();
            $userChars = $db->getUserCharactersFromUserID($uid);
        }
        return FFXIPackageHelper_HTMLOptions::charactersButtonsList($userChars );
    }

    // div id=FFXIPackageHelper_equipsets_charSelect
    private function getCharSelectButtonsBar($userchars, $selectChar = null){
        return FFXIPackageHelper_HTMLOptions::charactersButtonsList($userchars, $selectChar );
    }

    private function getUserID(){
        return RequestContext::getMain()->getUser()->getId();
    }

    private function getUserCharacters(){
        $uid = self::getUserID();
        if ( $uid != 0 ) { // User is logged in
            $db = new DatabaseQueryWrapper();
            return $db->getUserCharactersFromUserID($uid);
        }
        return null;
    }
}


?>