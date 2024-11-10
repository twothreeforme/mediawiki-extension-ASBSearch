<?php

use ApiBase;

class APIModuleDropRateSearch extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    private $query_limit = 1500;

    protected function getAllowedParams() {
        return [
            'action' => null,
			'mobname' => "",
            'itemname' => "",
            'zonename' => "",
            'bcnm' => "0",
            'excludenm' => "0",
            'lvlmin' => "0",
            'lvlmax' => "0",
            'showth' => "0"
    		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $queryData = [ $this->query_limit, 
                        $params['mobname'], 
                        $params['itemname'], 
                        $params['zonename'], 
                        $params['bcnm'], 
                        $params['excludenm'],
                        $params['lvlmin'], 
                        $params['lvlmax'], 
                        $params['showth']
                     ];

        $finalHtml = $this->queryDropRates($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);
        $result->addValue($params['action'], $params['querytype'], $finalHtml);
        //$result->addValue($params['action'], $params['querytype'], $queryData[8]);
    }

    private function queryDropRates($queryData){
        $showTH = intval($queryData[8]);
        $showBCNMdrops = intval($queryData[4]);

        $dm = new DataModel();
        $db = new DBConnection();

        $mobDropRatesData = $db->getDropRates($queryData); 
        $dm->parseData($mobDropRatesData);
        if ( $showBCNMdrops == 1) {
            $bcnmDropRatesData = $db->getBCNMCrateRates($queryData); //object output
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

		if ( $totalRows >= 0 ) {  
			if ( $totalRows == $queryData[0] ) $html .= "<i><b> $totalRows records (items) found, which is the search limit. Narrow search parameters.</i></b>";
			else $html .= "<i><b> $totalRows records (items) found.</i></b>";
            
            $html .= FFXIPackageHelper_HTMLTableHelper::table_DropRates($dropRatesArray, $showTH);
		}

		$html .= '</table></div>';

		return $html;
	}

}
?>