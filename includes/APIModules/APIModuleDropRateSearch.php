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
            'showth' => "0",
            'includesteal' => "0"
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
                        $params['showth'],
                        $params['includesteal'],
                     ];

        //$finalHtml = $this->queryDropRates($queryData);
        //$finalHtml = ParserHelper::wikiParse($finalHtml);

        $finalHtml = FFXIPackageHelper_QueryController::queryDropRates($queryData);
        $finalHtml = ParserHelper::wikiParse($finalHtml);

        $result->addValue($params['action'], $params['querytype'], $finalHtml);
        //$result->addValue($params['action'], $params['querytype'], $queryData[8]);
    }


}
?>