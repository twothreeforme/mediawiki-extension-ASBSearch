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
            'maxcraftlvl' => "0"
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
                        $params['maxcraftlvl']
                     ];

        $finalHtml = $this->queryRecipes($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);
        $result->addValue($params['action'], $params['querytype'], $finalHtml);
    }

    private function queryRecipes($queryData){
        $dm = new DataModel();
        $db = new DBConnection();

        // USE THIS ONE
        $initialQuery = $db->getRecipes($queryData);
       // $recipesQuery = $dm->parseRecipes($initialQuery);
        //$recipesQuery = $this->getRecipes($queryData);

        $html = "";

		if ( !$initialQuery[0] )  return "<i><b> No records (items) found</i></b>";

		/************************
		 * Row counter
		 */
        $html .= FFXIPackageHelper_HTMLTableHelper::table_RecipesQuery($initialQuery);

		//$html .= "<p>" . $recipesQuery . "</p>";
        // if ( gettype($recipesQuery) == 'string' ){
        //     $html .= "<p>" . $recipesQuery . "</p>";
        // }
        // if ( gettype($recipesQuery) == 'array' ){
        //     $html .= "<p>" . $row->Crystal . "</p>";
        // }


        //$html .= FFXIPackageHelper_HTMLTableHelper::table_DropRates($dropRatesArray, $showTH);
		// $html .= '</table></div>';
		return $html;
	}

}
?>