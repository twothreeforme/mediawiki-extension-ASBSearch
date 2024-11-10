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
			$r_bcnmChanges = ( property_exists($row, 'bcnmChanges' ) ) ? $row->bcnmChanges : 0;

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
				'dropData' => array (
					'groupId' => $row->groupId,
					'groupRate' => $row->groupRate,
					'type' => $r_dropType,
					'items' => array(
						$_item
					)));
			print_r($_item['name'] . "<br>");
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

		return $this->dataset;
    }

	function showKeys($arr){
			//print_r( $arr );
			//print_r( $arr->zoneName );
	}

	function getDataSet(){
		return $this->dataset;
	}



	function parseRecipes($param){

        if ( !$param ) return NULL;

		// need to add throw exception here
		// $param should be array from DBConnection
		// [ $recipesQueryResult, $items ]
		if ( gettype($param) != 'array' ) return NULL;

		foreach ( $param[0] as $row ) {

			$workingRow = array (
				'Desynth' => $row->Desynth,
				'KeyItem' => $this->getItemName($param[1], $row->KeyItem),
				'Wood' => $row->Wood,
				'Smith' => $row->Smith,
				'Gold' => $row->Gold,
				'Cloth' => $row->Cloth,
				'Leather' => $row->Leather,
				'Bone' => $row->Bone,
				'Alchemy' => $row->Alchemy,
				'Cook' => $row->Cook,
				'Crystal' => $this->getItemName($param[1], $row->Crystal),
				'HQCrystal' => $this->getItemName($param[1], $row->HQCrystal),
				'Ingredient1' => $this->getItemName($param[1], $row->Ingredient1),
				'Ingredient2' => $this->getItemName($param[1], $row->Ingredient2),
				'Ingredient3' => $this->getItemName($param[1], $row->Ingredient3),
				'Ingredient4' => $this->getItemName($param[1], $row->Ingredient4),
				'Ingredient5' => $this->getItemName($param[1], $row->Ingredient5),
				'Ingredient6' => $this->getItemName($param[1], $row->Ingredient6),
				'Ingredient7' => $this->getItemName($param[1], $row->Ingredient7),
				'Ingredient8' => $this->getItemName($param[1], $row->Ingredient8),
				'Result' => $this->getItemName($param[1], $row->Result),
				'ResultHQ1' => $this->getItemName($param[1], $row->ResultHQ1),
				'ResultHQ2' => $this->getItemName($param[1], $row->ResultHQ2),
				'ResultHQ3' => $this->getItemName($param[1], $row->ResultHQ3),
				'ResultQty' => $row->ResultQt,
				'ResultHQ1Qty' => $row->ResultHQ1Qty,
				'ResultHQ2Qty' => $row->ResultHQ2Qty,
				'ResultHQ3Qty' => $row->ResultHQ3Qty,
				'ResultName' => $row->ResultName,
			);
			array_push ( $this->dataset, $workingRow );
		}
        return $this->dataset;
    }


}

?>