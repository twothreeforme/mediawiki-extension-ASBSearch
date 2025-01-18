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
        $db = new DBConnection();

        $mobDropRatesData = $db->getDropRates($queryData);

        $dm->parseData($mobDropRatesData);

		$temp = [];
		foreach ($mobDropRatesData as $row){ $temp[]=$row; break; }

        if ( $showBCNMdrops == 1) {
            $bcnmDropRatesData = $db->getBCNMCrateRates($queryData); //object output

			$temp = 0;
			foreach ($bcnmDropRatesData as $row){ $temp[]=$row; break; }
			throw new Exception( json_encode($temp) );


			$dm->parseData($bcnmDropRatesData);
        }
        
        $dropRatesArray = $dm->getDataSet();

		$html = "";
		if ( !$dropRatesArray )  return "<i><b> No records (items) found</i></b>";

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
        $db = new DBConnection();

        // USE THIS ONE
        $initialQuery = $db->getRecipes($queryData);
       // $recipesQuery = $dm->parseRecipes($initialQuery);
        //$recipesQuery = $this->getRecipes($queryData);

        $html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";

        $html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);

		return $html;
	}

	public static function queryFishing($queryData){
		$dm = new DataModel();
        $db = new DBConnection();
		$initialQuery = $db->getFi($queryData);

 		$html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";
        //$html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);
		return $html;
	}

}

?>
