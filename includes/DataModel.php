<?php


class DataModel {
    private $dataset = array();  // array of rows

	// public $zoneName;
    // public $mobName;
    // public $mobMinLevel;
    // public $mobMaxLevel;
    // public $dropGroup = array();
    // public $dropGroupRate;
    // public $dropType;
    // public $item = array();
    // public $itemName;
    // public $itemRate;

    public function __construct() {
      //$this->dataset = $param;
      //self::parseData($param);
    }

    function parseData($param){
        //print_r($this->dataset);
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
			if ( !$zn ) { continue; }
			if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
			/*******************************************************/
			//print_r(gettype($row));
			$r_mobMinLevel = ( property_exists($row, 'mobMinLevel' ) ) ? $row->mobMinLevel : 0; 
			$r_mobMaxLevel = ( property_exists($row, 'mobMaxLevel' ) ) ? $row->mobMaxLevel : 0; 
			$r_dropType = ( property_exists($row, 'dropType' ) ) ? $row->dropType : 0;
			$r_mobChanges = ( property_exists($row, 'mobChanges' ) ) ? $row->mobChanges : 0;

			// Doing it this way - itemChanges will take precendence over dropChanges...
			// so a Horizon changes tag will take precendence over a nuanced label
			 
			$r_itemChanges = ( property_exists($row, 'dropChanges' ) ) ? $row->dropChanges : 0;
			$r_itemChanges = ( property_exists($row, 'itemChanges' ) && $row->itemChanges != 0 ) ? $row->itemChanges : $r_itemChanges; 

			$r_itemId = ( property_exists($row, 'itemId' ) ) ? $row->itemId : 0;
			$r_gilAmt = ( property_exists($row, 'gilAmt' ) ) ? $row->gilAmt : 0;
			$r_mobType = ( property_exists($row, 'mobType' ) ) ? $row->mobType : 0;

			

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
				'dropData' => array (
					'groupId' => $row->groupId,
					'groupRate' => $row->groupRate,
					'type' => $r_dropType,
					'items' => array(
						$_item
					)));

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
			


			// if ( $prev_row['dropData']['groupId'] == $workingRow['dropData']['groupId'] ) {
			// 	print_r('here');
			// 	$groupRateMax += $_item['dropRate']; 
			// 	//array_push ( $this->dataset['dropData']['items'], $_item );
			// 	//print_r($this->dataset['dropData']['items']);
			// 	continue;
			// }
			// else {
			// 	$l = array_key_last($this->dataset);
			// 	//print_r($this->dataset[$l]);

			// 	//array_push ( $this->dataset['dropData']['items'], $_item );
			// }


			// $zn = ParserHelper::zoneName($row->zoneName);
			// $mn = ParserHelper::mobName($row->mobName, $row->mobMinLevel, $row->mobMaxLevel, $zn);
			// $in = ParserHelper::itemName($row->itemName);

			// /******************************************************
			//  * Handle drop TYPE & RATE
			//  */
			// $dropGroup;
			// $droprate;	
			// switch ($row->dropType) {
			// 	case 0;
			// 		$droprate = round(($row->itemRate) / 10 ) ;
			// 		$droprate = "$droprate %";
			// 		$dropGroup = "-";
			// 		break;
			// 	case 1:
			// 		$dropGroup = "Group $row->groupId - " . ($row->groupRate / 10 )."%" ;
			// 		$droprate = round(($row->itemRate) / 10 ) ;
			// 		$droprate = "$droprate %";
			// 		break;
			// 	case 2:
			// 		$droprate = 'Steal';
			// 		$dropGroup = "-";
			// 		break;
			// 	case 4;
			// 		$droprate = 'Despoil';
			// 		$dropGroup = "-";
			// 		break;
			// 	default:
			// 		// $droprate = round(($row->itemRate) / (ParserHelper::getVarRate($row->groupRate)[1] / 100 ) ) ;
			// 		// $droprate = "$droprate %";
			// 		break;
			// }

			//print_r($zn);

		}

		// foreach($this->dataset as $row){
		// 	if ( $row['dropData']['groupId'] == 0 ) continue;
		// 	print_r(" zone: ".$row['zoneName']);
		// 	//print_r(" mob: ".$row['mobName']);
		// 	print_r(" gId: ".$row['dropData']['groupId']);
		// 	//print_r(" gR: ".$row['dropData']['groupRate']);
			
		// 	foreach($row['dropData']['items'] as $item){
		// 		print_r(" iN: ".$item['name']);
		// 		print_r(" iR: ".$item['dropRate']);
		// 	}
			
		// }

        return $this->dataset;
    }

	function showKeys($arr){
			//print_r( $arr );
			//print_r( $arr->zoneName );
	}

	function getDataSet(){
		return $this->dataset;
	}


    // function arrayFromRates($dataset){ //uses schema from self::getRates
	// 	$array = [];
	// 	foreach ($dataset as $row)
	// 	{
			
	// 		/*******************************************************
	// 		 * Removing OOE 
	// 		 */
	// 		// First check zone names
			
	// 		//$zn = str_replace("[S]", "(S)", $zn );
	// 		// $skipRow = false;
	// 		// foreach( ExclusionsHelper::$zones as $v) { 
	// 		// 	//print_r($zn);
	// 		// 	if ( $zn == $v ) { $skipRow = true; break; } }
	// 		// if ( $skipRow == true ) continue;
	// 		$zn = ParserHelper::zoneERA_forList($row->zoneName);
	// 		if ( !$zn ) { continue; }
	// 		if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
	// 		/*******************************************************/


	// 		$zn = ParserHelper::zoneName($row->zoneName);
	// 		$mn = ParserHelper::mobName($row->mobName, $row->mobMinLevel, $row->mobMaxLevel, $zn);
	// 		$in = ParserHelper::itemName($row->itemName);

			
	// 		// if ( $itemGroup != 0 ) { // item group has been set from a previous iteration and needs to be handled

	// 		// }

	// 		/******************************************************
	// 		 * Handle drop TYPE & RATE
	// 		 */
	// 		// $dropGroup;
	// 		// $droprate;	
	// 		// switch ($row->dropType) {
	// 		// 	case 0;
	// 		// 		$droprate = round(($row->itemRate) / 10 ) ;
	// 		// 		$droprate = "$droprate %";
	// 		// 		$dropGroup = "-";
	// 		// 		break;
	// 		// 	case 1:
	// 		// 		$dropGroup = "Group $row->groupId - " . ($row->groupRate / 10 )."%" ;
	// 		// 		$droprate = round(($row->itemRate) / 10 ) ;
	// 		// 		$droprate = "$droprate %";
	// 		// 		break;
	// 		// 	case 2:
	// 		// 		$droprate = 'Steal';
	// 		// 		$dropGroup = "-";
	// 		// 		break;
	// 		// 	case 4;
	// 		// 		$droprate = 'Despoil';
	// 		// 		$dropGroup = "-";
	// 		// 		break;
	// 		// 	default:
	// 		// 		// $droprate = round(($row->itemRate) / (ParserHelper::getVarRate($row->groupRate)[1] / 100 ) ) ;
	// 		// 		// $droprate = "$droprate %";
	// 		// 		break;
	// 		// }			

			/**
			 * Unique by 1x zonename and 1x mobname
			 * $array['zonename'] = [
			 * 		zonename['mobname'] = [ 
			 * 			moblevel = [ mobminlevel, mobmaxlevel ]
			 * 			dropdata = [ dropgroup, [ itemname, droprate ] 
			 * 		]	
			 * 	]
			 */
			
			
	// 		$group = [ $row->groupId, $row->groupRate ];
	// 		$item = [ $row->itemName, $row->itemRate ];

	// 		$temp = array (
	// 			// 'mobName' => $row->mobName, 
	// 			// 'mobMinLevel' => $row->mobMinLevel,
	// 			// 'mobMaxLevel' => $row->mobMaxLevel,
	// 			'mobName' => $mn,
	// 			'dropData' => array (
	// 				'groupId' => $row->groupId,
	// 				'groupRate' => $row->groupRate,
	// 				'item' => array(
	// 					'name' => $row->itemName,
	// 					'dropRate' => $row->itemRate
	// 				)));

	// 		if ( !array_key_exists($zn, $array) ) { $array[$zn] = []; }	// set zoneName in array
	// 		if ( !array_key_exists($mn, $array[$zn])){ 					// set mobName in array
	// 			// array_push ( $array[$zn], $mn );
	// 			$array[$zn][$mn] = $mn;
	// 			$array[$zn]['dropData'] = array(
	// 				'groupId' => $row->groupId,
	// 				'groupRate' => $row->groupRate,
	// 				'item' => array(
	// 					'name' => $row->itemName,
	// 					'dropRate' => $row->itemRate
	// 				));

	// 			continue; 
	// 		}
	// 		unset($temp['mobName']);
	// 		if ( !array_key_exists('dropData', $array[$zn])){			// set dropData in array

	// 		}	
			

	// 		//[ $row->zoneName, $row->mobName, $row->mobMinLevel, $row->mobMaxLevel, [ ] ]
	// 	}
	// 	$html = "";
	// 	foreach ($array as $key => $mobArray){
	// 		//$html = "<br>" . $a["zoneName"]["dropData"]["groupId"] ;
	// 		$html .= "<br> $key";

	// 		foreach ($mobArray as $mob){
	// 			$html .= "	<br>". $mob["mobName"] . ":" . $mob["dropData"];
	// 		} 
	// 	}
	// 	//print_r($array);
	// 	return $html;
	// } 


}

?>