<?php

use ApiBase;

class APIModuleEquipmentSearch extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'equipmentname' => "0",
            'job' => "0",
            'minitemlvl' => "0"
    		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $queryData = [  $params['equipmentname'],
                        $params['job'],
                        $params['minitemlvl']
                     ];

        $finalHtml = $this->queryEquipment($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);
        $result->addValue($params['action'], $params['querytype'], $finalHtml);
    }

    private function queryEquipment($queryData){
        $dm = new DataModel();
        $db = new DBConnection();

        // USE THIS ONE
        $initialQuery = $db->getEquipmentFromDB($queryData);

        //return $initialQuery;
        //$recipesQuery = $dm->parseRecipes($initialQuery);
        //$recipesQuery = $this->getRecipes($queryData);

        $html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";

		/************************
		 * Row counter
		 */
        //$html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);
        $html .= $initialQuery;
		return $html;
	}

}
?>