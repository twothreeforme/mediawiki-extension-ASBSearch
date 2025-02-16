<?php

use ApiBase;

class APIModuleFishingSearch extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'baitname' => "",
            'fishname' => "",
            'zonename' => "",
    		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $queryData = [  $params['baitname'],
                        $params['fishname'],
                        $params['zonename']
                     ];

        $finalHtml = $this->queryFishing($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);
        $result->addValue($params['action'], "fishing", $finalHtml);
    }

    private function queryFishing($queryData){
        $dm = new DataModel();
        $db = new DBConnection();

        // USE THIS ONE
        $initialQuery = $db->getFishing($queryData);
        $finalQuery = $dm->parseFishing($initialQuery);

        //throw new Exception(json_encode($finalQuery));

        $html = "";

		if ( !$finalQuery[0] )  return "<i><b> No records (items) found</i></b>";

        $html .= FFXIPackageHelper_HTMLTableHelper::table_FishingQuery($finalQuery);

		return $html;
	}

}
?>