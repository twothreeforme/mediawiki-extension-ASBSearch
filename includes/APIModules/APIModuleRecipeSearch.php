<?php

use ApiBase;

class APIModuleRecipeSearch extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
            'action' => null,
			'craft' => "",
            'recipename' => "",
            'ingredient' => "",
            'crystal' => "0",
            'skillrank' => "0",
            'mincraftlvl' => "0",
            'maxcraftlvl' => "0",
            'includedesynth' => "0"
    		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $queryData = [  $params['craft'],
                        $params['recipename'],
                        $params['ingredient'],
                        $params['crystal'],
                        $params['skillrank'],
                        $params['mincraftlvl'],
                        $params['maxcraftlvl'],
                        $params['includedesynth']
                     ];

        $finalHtml = $this->queryRecipes($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);
        $result->addValue($params['action'], "recipes", $finalHtml);
    }

    private function queryRecipes($queryData){
        $dm = new DataModel();
        $db = new DBConnection();

        $initialQuery = $db->getRecipes($queryData);
        if ( count($initialQuery) > 0 ) $db->incrementHitCounter("recipes");

        $html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";

		/************************
		 * Row counter
		 */
        $html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);

		return $html;
	}

}
?>