<?php

class FFXIPackageHelper_QueryController {


    // public static function queryDropRates($queryData){
    //     $finalHtml = FFXIPackageHelper_QueryController::buildDropRates($queryData);
    //     return ParserHelper::wikiParse($finalHtml);
    // }

    public static function queryDropRates($queryData){
        $showTH = intval($queryData[8]);
        $showBCNMdrops = intval($queryData[4]);

        $dm = new DataModel();
        $db = new DatabaseQueryWrapper();

        $mobDropRatesData = $db->getDropRates($queryData);
				wfDebugLog( 'ASBSearch', get_called_class() . ":" . json_encode($mobDropRatesData) );

        $dm->parseData($mobDropRatesData);

		//$temp = array();
		//foreach ($mobDropRatesData as $row){ array_push($temp, $row); break; }

        if ( $showBCNMdrops == 1) {
            $bcnmDropRatesData = $db->getBCNMCrateRates($queryData); //object output
			//foreach ($bcnmDropRatesData as $row){ array_push($temp, $row); break; }
			$dm->parseData($bcnmDropRatesData);
        }

		$dropRatesArray = $dm->getDataSet();

		$html = "";
		if ( !$dropRatesArray )  return "<i><b> No records (items) found</i></b>";
		else $db->incrementHitCounter("droprate");

		/************************
		 * Row counter
		 */
		$totalRows = -1;
		
		foreach ($dropRatesArray as $row) // test total records query'd
		{

			//print_r("row: " .$row['mobName']);
			if ( $totalRows < 0 ) $totalRows = 0;
			foreach($row['dropData']['items'] as $item ){
				$totalRows ++;
				// if ( $totalRows > $this->query_limit){
				// 	return "<b><i>Query produced too many results to display. Queries are limited to 1000 results, for efficiency.
				// 		Please reduce search pool by adding more to any of the search parameters.</i></b>";
				// }
			}
		}

		//throw new Exception( "Total rows: " . $totalRows );


		if ( $totalRows >= 0 ) {  
			if ( $totalRows == $queryData[0] ) $html .= "<i><b> $totalRows records (items) found, which is the search limit. Narrow search parameters.</i></b>";
			else $html .= "<i><b> $totalRows records (items) found.</i></b>";
            
            $html .= FFXIPackageHelper_HTMLTableHelper::table_DropRates($dropRatesArray, $showTH);
		}

		$html .= '</table></div>';

		return $html;
	}

    public static function queryRecipes($queryData){
        $dm = new DataModel();
        $db = new DatabaseQueryWrapper();

        // USE THIS ONE
        $initialQuery = $db->getRecipes($queryData);
       // $recipesQuery = $dm->parseRecipes($initialQuery);
        //$recipesQuery = $this->getRecipes($queryData);

        $html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";

        $html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);

		return $html;
	}

	/**
	 * Supports Equipsets modal window equipment search
	 * @param queryData params from APIModuleEquipsets
	 * @return string html list with query results
	 * should fit into <dl> with id="FFXIPackageHelper_equipsets_searchResults"
	 */
	public static function queryEquipsetsSearchItems($queryData){
		$db = new DatabaseQueryWrapper();
		$dm = new DataModel();

		$searchString = ParserHelper::replaceApostrophe($queryData['search']);
		$searchString = ParserHelper::replaceSpaces($searchString);

		$equipList = $db->getEquipment( $searchString, $queryData['mlvl'], $queryData['slot']); // get data from DB

		$finalList = $dm->parseEquipment( $equipList, $queryData['mjob'] ); // build associative array with data so its easier to build list

		$html = "";

		if ( count($finalList) == 0 ) return $html;

		//global $wgServer;
    	global $wgScript;

		for ($l = 0; $l < count($finalList); $l++) {
			if ( $l == 0 ) $tabindex = 0;
			else $tabindex = -1;

			if ( $finalList[$l]['id'] >= 50000 ) $id = $finalList[$l]['DATid'];
			else $id = $finalList[$l]['id'];

			$html .= "<dt tabindex=\"" . $tabindex . "\" style=\"\" data-id=\"" . $id . "\"> ";

			$imgURL = $wgScript . "/Special:Filepath/itemid_" . $id . ".png";
			$html .= "<img src=\"" . $imgURL . "\" width=\"20\" height=\"20\">";
			$html .= "" . $finalList[$l]['name'] . "";
			$html .= "</dt>";
		}

		return $html;
	}

}

?>
