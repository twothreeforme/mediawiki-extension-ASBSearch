<?php


class DataModel {
    private $dataset = array();  // array of rows

    public function __construct() {
    }

	function getDataSet(){
		return $this->dataset;
	}

    function parseData($param){
		if ( !$param ) return NULL;

		$groupRateMax = 0;
		foreach ( $param as $row ) {
			
			/*******************************************************
			 * Removing OOE 
			 */
			// First check zone names
			$zn = ParserHelper::zoneERA_forList($row->zoneName);

			if ( !$zn || ExclusionsHelper::zoneIsTown($zn) ) { continue; }
			if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
			if ( ExclusionsHelper::itemIsOOE($row->itemName) && intval($row->groupId) == 0) { continue; }

			//wfDebugLog( 'ASBSearch', get_called_class() . ": " . ExclusionsHelper::itemIsOOE($row->itemName) . " : " . $row->groupId );
			/*******************************************************/
			//print_r(gettype($row));
			$r_mobMinLevel = ( property_exists($row, 'mobMinLevel' ) ) ? $row->mobMinLevel : 0; 
			$r_mobMaxLevel = ( property_exists($row, 'mobMaxLevel' ) ) ? $row->mobMaxLevel : 0; 
			
			$r_dropType = ( property_exists($row, 'dropType' ) ) ? $row->dropType : 0;
			//if ( $row->dropType == "2" ) continue;

			$r_dropID = ( property_exists($row, 'dropid' ) ) ? $row->dropid : 0;
			if ( $r_dropID == 3226 ) continue; // Most Garrison mobs share the same dropid

			$r_mobChanges = ( property_exists($row, 'mobChanges' ) ) ? $row->mobChanges : 0;
			$r_aggro = ( property_exists($row, 'aggro' ) ) ? $row->aggro : -1;
			$r_trueDetection = ( property_exists($row, 'true_detection' ) ) ? $row->true_detection : -1;
			$r_detects = ( property_exists($row, 'detects' ) ) ? $row->detects : 0;
			$r_eco = ( property_exists($row, 'ecosystem' ) ) ? $row->ecosystem : 0;
			$r_family = ( property_exists($row, 'superFamily' ) ) ? $row->superFamily : 0;

			// Doing it this way - itemChanges will take precendence over dropChanges...
			// so a Horizon changes tag will take precendence over a nuanced label
			 
			$r_itemChanges = ( property_exists($row, 'dropChanges' ) ) ? $row->dropChanges : 0;
			$r_itemChanges = ( property_exists($row, 'itemChanges' ) && $row->itemChanges != 0 ) ? $row->itemChanges : $r_itemChanges; 

			$r_itemId = ( property_exists($row, 'itemId' ) ) ? $row->itemId : 0;
			$r_gilAmt = ( property_exists($row, 'gilAmt' ) ) ? $row->gilAmt : 0;
			$r_mobType = ( property_exists($row, 'mobType' ) ) ? $row->mobType : 0;
			$r_bcnmChanges = ( property_exists($row, 'bcnmChanges' ) ) ? $row->bcnmChanges : 0;

			if ( $r_bcnmChanges == 1 ) $r_itemChanges = 1;

			/**
			 * Begin additional exclusions here to prevent building the model in the first place
			 */
			if ( $r_mobMinLevel >= 90 && str_contains($zn, "Dynamis") && !str_contains($zn, "Tavna")  ) continue;

			$_item = array(
				'name' => $row->itemName,
				'dropRate' => $row->itemRate,
				'changes' => $r_itemChanges,
				'id' => $r_itemId,
				'gilAmt' => $r_gilAmt
			);

			$workingRow = array (
				'zoneName' => $zn,
				'mobName' => $row->mobName,
				'mobChanges' => $r_mobChanges,
				'mobMinLevel' => $r_mobMinLevel,
				'mobMaxLevel' => $r_mobMaxLevel,
				'mobType' => $r_mobType,
				'bcnmChanges' => $r_bcnmChanges,
				'family' => $r_family,
				'ecosystem' => $r_eco,
				'detects' => $r_detects,
				'aggro' => $r_aggro,
				'trueDetection' => $r_trueDetection,
				'dropData' => array (
					'groupId' => $row->groupId,
					'groupRate' => $row->groupRate,
					'type' => $r_dropType,
					'items' => array(
						$_item
					)));
			//print_r($_item['name'] . "<br>");
			//print_r($row->mobName ."-" . $r_mobType ."...");

			// it doenst exist, so create new entry
			if ( !$this->dataset ) { array_push ( $this->dataset, $workingRow ); continue; }
			
			// i think i only need to view the last item in the array
			// over each iteration
			// fastest method: $x = array_slice($array, -1)[0];
			$prev_row = array_slice($this->dataset, -1)[0];
			if ( $prev_row['zoneName'] != $workingRow['zoneName'] ) { array_push ( $this->dataset, $workingRow ); continue; }
			if ( $prev_row['mobName'] != $workingRow['mobName']) { array_push ( $this->dataset, $workingRow ); continue; }
			if ( $workingRow['dropData']['groupId'] == 0 ) { array_push ( $this->dataset, $workingRow ); continue; }

			//print_r($row->groupId);  //works
			//print_r($workingRow['dropData']['item']['dropRate']);  //works
			//print_r($prev_row['dropData']['groupId'] . ":" .  $workingRow['dropData']['groupId'] . "..."); //works

			// effectively resetting the groupRateMax and starting a new group
			if ( $prev_row['dropData']['groupId'] != $workingRow['dropData']['groupId'] ) {
				if ( $prev_row['dropData']['groupId'] != 0 ){
					if ( $groupRateMax > 1000 ) $prev_row['dropData']['groupRate'] = $groupRateMax;
					else $prev_row['dropData']['groupRate'] = 1000;
				}
				$groupRateMax = $_item['dropRate'];
				array_push ( $this->dataset, $workingRow ); continue;
			}
		
			$l = array_key_last($this->dataset);
			$groupRateMax += $_item['dropRate'];
			if ( $prev_row['dropData']['groupId'] != $workingRow['dropData']['groupId'] ){
				array_push ( $this->dataset, $workingRow ); continue;
			}
			else{
				array_push ( $this->dataset[$l]['dropData']['items'], $_item );
			}
		}

		// foreach ( $this->dataset as $row ){ throw new Exception ( json_encode($this->dataset));}

		return $this->dataset;
    }

	/**
	 * used for mob tables on a wiki page
	 * not associated with ASBSearch
	 */
	function parseMobDropData($param){
       
        if ( !$param ) return NULL;

		$groupRateMax = 0;
		foreach ( $param as $row ) {
			
			//self::showKeys($row);  //Debugging

			/*******************************************************
			 * Removing OOE 
			 */
			// First check zone names
			
			//$zn = str_replace("[S]", "(S)", $zn );
			// $skipRow = false;
			// foreach( ExclusionsHelper::$zones as $v) { 
			// 	//print_r($zn);
			// 	if ( $zn == $v ) { $skipRow = true; break; } }
			// if ( $skipRow == true ) continue;
			$zn = ParserHelper::zoneERA_forList($row->zoneName);

			if ( !$zn || ExclusionsHelper::zoneIsTown($zn) ) { continue; }
			if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
			if ( ExclusionsHelper::itemIsOOE($row->itemName) && $row->groupId == 0 ) { continue; }

			/*******************************************************/
			//print_r(gettype($row));
			$r_mobMinLevel = ( property_exists($row, 'mobMinLevel' ) ) ? $row->mobMinLevel : 0; 
			$r_mobMaxLevel = ( property_exists($row, 'mobMaxLevel' ) ) ? $row->mobMaxLevel : 0; 
			$r_dropType = ( property_exists($row, 'dropType' ) ) ? $row->dropType : 0;
			if ( (int)$r_dropType == 2) { continue; }

			$r_mobChanges = ( property_exists($row, 'mobChanges' ) ) ? $row->mobChanges : 0;
			$r_aggro = ( property_exists($row, 'aggro' ) ) ? $row->aggro : -1;
			$r_trueDetection = ( property_exists($row, 'true_detection' ) ) ? $row->true_detection : -1;
			$r_detects = ( property_exists($row, 'detects' ) ) ? $row->detects : 0;
			$r_eco = ( property_exists($row, 'ecosystem' ) ) ? $row->ecosystem : 0;
			$r_family = ( property_exists($row, 'superFamily' ) ) ? $row->superFamily : 0;

			// Doing it this way - itemChanges will take precendence over dropChanges...
			// so a Horizon changes tag will take precendence over a nuanced label
			 
			$r_itemChanges = ( property_exists($row, 'dropChanges' ) ) ? $row->dropChanges : 0;
			$r_itemChanges = ( property_exists($row, 'itemChanges' ) && $row->itemChanges != 0 ) ? $row->itemChanges : $r_itemChanges; 

			$r_itemId = ( property_exists($row, 'itemId' ) ) ? $row->itemId : 0;
			$r_gilAmt = ( property_exists($row, 'gilAmt' ) ) ? $row->gilAmt : 0;
			$r_mobType = ( property_exists($row, 'mobType' ) ) ? $row->mobType : 0;
			$r_bcnmChanges = ( property_exists($row, 'bcnmChanges' ) ) ? $row->bcnmChanges : 0;

			if ( $r_bcnmChanges == 1 ) $r_itemChanges = 1;

			/**
			 * Begin additional exclusions here to prevent building the model in the first place
			 */
			if ( $r_mobMinLevel >= 90 && str_contains($zn, "Dynamis") && !str_contains($zn, "Tavna")  ) continue;



			$_item = array(
				'name' => $row->itemName,
				'dropRate' => $row->itemRate,
				'changes' => $r_itemChanges,
				'id' => $r_itemId,
				'gilAmt' => $r_gilAmt
			);

			$_group = array(
				'groupId' => $row->groupId,
				'groupRate' => $row->groupRate,
				'type' => $r_dropType,
				'items' => array(
					$_item
				)
			);

			$workingRow = array (
				'zoneName' => $zn,
				//'mobName' => $row->mobName,
				//'mobChanges' => $r_mobChanges,
				'mobMinLevel' => $r_mobMinLevel,
				'mobMaxLevel' => $r_mobMaxLevel,
				'mobType' => $r_mobType,
				'bcnmChanges' => $r_bcnmChanges,
				'family' => $r_family,
				'ecosystem' => $r_eco,
				'detects' => $r_detects,
				'aggro' => $r_aggro,
				'trueDetection' => $r_trueDetection,
				'dropData' => array (
					// 'groupId' => $row->groupId,
					// 'groupRate' => $row->groupRate,
					// 'type' => $r_dropType,
					// 'items' => array(
					// 	$_item
					$_group
					
					));
			//print_r($_item['name'] . "<br>");
			//print_r($row->mobName ."-" . $r_mobType ."...");

			// it doenst exist, so create new entry
			if ( !$this->dataset ) { array_push ( $this->dataset, $workingRow ); continue; }
			
			// i think i only need to view the last item in the array
			// over each iteration
			// fastest method: $x = array_slice($array, -1)[0];
			$prev_row = array_slice($this->dataset, -1)[0];
			if ( $prev_row['zoneName'] != $workingRow['zoneName'] ) { array_push ( $this->dataset, $workingRow ); continue; }
			//if ( $prev_row['mobName'] != $workingRow['mobName']) { array_push ( $this->dataset, $workingRow ); continue; }
			//if ( $workingRow['dropData']['group']['groupId'] == 0 ) { array_push ( $this->dataset, $workingRow ); continue; }
			if ( $prev_row['mobMaxLevel'] != $workingRow['mobMaxLevel'] &&
				$prev_row['mobMinLevel'] != $workingRow['mobMinLevel'] ) { array_push ( $this->dataset, $workingRow ); continue; }


			//print_r($row->groupId);  //works
			//print_r($workingRow['dropData']['item']['dropRate']);  //works
			//print_r($prev_row['dropData']['groupId'] . ":" .  $workingRow['dropData']['groupId'] . "..."); //works

			// effectively resetting the groupRateMax and starting a new group
			$_l = array_key_last($this->dataset);
			$_g = array_key_last($this->dataset[$_l]['dropData']);
			$prev_row_group = &$this->dataset[$_l]['dropData'][$_g];

			if ( $prev_row_group['groupId'] != $_group['groupId'] ) {
				if ( $prev_row_group['groupId'] != 0 ){
					if ( $groupRateMax > 1000 ) $prev_row_group['groupRate'] = $groupRateMax;
					else $prev_row_group['groupRate'] = 1000;
				}
				$groupRateMax = $_item['dropRate'];
				array_push($this->dataset[$_l]['dropData'], $_group); continue;
			}
		
			$groupRateMax += $_item['dropRate'];
			array_push ( $this->dataset[$_l]['dropData'][$_g]['items'], $_item );
			
		}

		// foreach ( $this->dataset as $row ){ throw new Exception ( json_encode($this->dataset));}

		return $this->dataset;
    }

	function parseEquipment($param, $job = null){
        if ( !$param ) return NULL;

		$iDetails = new FFXIPackageHelper_ItemDetails();

		foreach ( $param as $row ) {
			//throw new Exception($row->jobs);
			if ( $job != NULL && $job > 0 && isset($row->jobs)) {
				if ( !ParserHelper::checkJob($job, $row->jobs) ) continue;
			}

			/**
			 * We can skip some of these effects...
			 */

			$_mod = null;
			$statusEffect = false;
			if  ( $row->modid && $row->modValue){
				
				if ( $row->modid == 499 || 		//-- Animation ID of Spikes and Additional Effects
					$row->modid == 501 || 		//-- Chance of an items Additional Effect or Spikes
					$row->modid == 950 ||		//-- Element of the Additional Effect or Spikes, for resist purposes
					$row->modid == 953 ||	 	//-- Base Duration for effect in MOD_ITEM_ADDEFFECT_STATUS
					$row->modid == 1180 )		//-- Additional parameters for more specific latents required to proc
					$statusEffect = true;

				$_mod = array(
					'id' => $row->modid,
					'value' => $row->modValue
				);
			}

			//First, address HXI custom gear
			//this should be the last calls to $row->itemId
			$itemID_includingHXICustoms = $row->itemId;
			//if ( $row->itemId >= 50000 ) $itemID_includingHXICustoms = $iDetails->items[$row->itemId]["forward"];
			if ( $row->itemId >= 50000 ) $itemID_includingHXICustoms = $iDetails->replacement[$row->itemId];

			$name = "";
			if ( $itemID_includingHXICustoms != "" && $itemID_includingHXICustoms != 0 ) $name = $iDetails->items[ $itemID_includingHXICustoms ]["name"];

			$workingRow = array (
				'id' => $row->itemId,
				'DATid' => $itemID_includingHXICustoms,
				//'id' => $row->itemId,
				'name' => $name,
				'level' => $row->level,
				'skilltype' => (property_exists($row, 'skilltype' ) ) ? $row->skilltype : 0,
				'jobs' => $row->jobs,
				'slot' => $row->slot,
				'rslot' => $row->rslot,
				'hasstatuseffect' => $statusEffect ? $statusEffect : false,
				'mods' => ($_mod == null) ? array() : array ( $_mod )
			);


			// it doenst exist, so create new entry
			if ( !$this->dataset ) { array_push ( $this->dataset, $workingRow ); continue; }
			
			// i think i only need to view the last item in the array
			// over each iteration
			// fastest method: $x = array_slice($array, -1)[0];
			$prev_row = array_slice($this->dataset, -1)[0];
			if ( $prev_row['name'] != $workingRow['name']) { array_push ( $this->dataset, $workingRow ); continue; }

			$l = array_key_last($this->dataset);
			if ( $statusEffect == true && $this->dataset[$l]['hasstatuseffect'] != true ) $this->dataset[$l]['hasstatuseffect'] = true;
			array_push ( $this->dataset[$l]['mods'], $_mod );

		}

		return $this->dataset;
	}

	function parseFishing($param){
        if ( !$param ) return NULL;

		foreach ( $param as $row ) {

			//throw new Exception ( json_encode( $row) );

			$_bait = $row->baitname;
			$_zone = $row->zonename;

			$workingRow = array (
				'fishname' => $row->fishname,
				'fishid' => $row->fishid,
				'zonelist' => array( $_zone),
				'baitlist' => array( $_bait)
			);

			// it doenst exist, so create new entry
			if ( !$this->dataset ) { array_push ( $this->dataset, $workingRow ); continue; }
			
			// i think i only need to view the last item in the array
			// over each iteration
			// fastest method: $x = array_slice($array, -1)[0];
			$prev_row = array_slice($this->dataset, -1)[0];
			if ( $prev_row['fishname'] != $workingRow['fishname']) {
				array_push ( $this->dataset, $workingRow );
				continue;
			}

			$l = array_key_last($this->dataset);
			if ( !in_array($_zone, $this->dataset[$l]['zonelist'])) array_push ( $this->dataset[$l]['zonelist'], $_zone );
			if ( !in_array($_bait, $this->dataset[$l]['baitlist'])) array_push ( $this->dataset[$l]['baitlist'], $_bait );
			else continue;
		}

		for( $f = 0; $f < count($this->dataset); $f++ ){
			sort($this->dataset[$f]['zonelist']);
		}

		return $this->dataset;
	}
}

?>