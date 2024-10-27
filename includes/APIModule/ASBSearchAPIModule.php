<?php

use ApiBase;

class ASBSearchAPIModule extends ApiBase {
    public function __construct( $main, $action ) {
        parent::__construct( $main, $action);
    }

    protected function getAllowedParams() {
        return [
			'dropratequery' => null
		];
	}

    function execute( ) {
        $params = $this->extractRequestParams();
        $result = $this->getResult();

        $dropratedata = $params['dropratequery'];

        $temp = $dropratedata;
        if(!isset($dropratedata)) $temp = "notset";

        $testing = "<div id=\"FFXIPackageHelper_tabs_droprates_searchForm\"><h3>Search Form</h3>
                        <table><tbody><tr><td>
                        <table><tbody>
                        <tr>
                            <td>Mob/BCNM Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Item Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"itemNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Zone <br><button id=\"FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                        </tr>
                        </tbody></table>
                    </td>
                        <td>Level: Min-><select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select> Max-> <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMAX\"></select><br></td>
                    </tr></tbody></table>
                </div>";

        // $this->dieWithError( 'apierror-thiswasclicked', $temp );
        $result->addValue("dropratequery", "namesdfsd", $testing);
    }


}
?>