<?php
//namespace MediaWiki\Extension\MyExtension;

use Wikimedia\Rdbms\DatabaseFactory;
//use MediaWikiServices;

class SpecialASBSearch extends SpecialPage {
    public function __construct( ) {
        parent::__construct( 'ASBSearch' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  { 
		$out->addModules(['FFXIPackageHelper_ASBSearch']);
		//$parser->getOutput()->addModules(['ext.FFXIMap']);
	}
	
	private $levelRangeMIN = 0;
	private $levelRangeMAX = 0;
	private $thRatesCheck = 0;
	private $showBCNMdrops = 0;
	private $excludeNMs = 1;
	private $query_limit = 1500;

	// private $dbUsername = 'horizon_wiki'; 
	// private $dbPassword = 'KamjycFLfKEyFsogDtqM';

	function execute( $par ) {
		//$this->testing();
		$this->setHeaders();
		$request = $this->getRequest();
		$output = $this->getOutput();
		$output->setPageTitle( $this->msg( 'asbsearch' ) );

		$db = new DBConnection();

		// db login variables - prevents swapping login info between testing server and horizon server		
		//print_r( $_SERVER['HTTP_HOST'] );
		// if ( $_SERVER['HTTP_HOST'] == 'localhost' ){
		// 	$this->dbUsername = 'root'; $this->dbPassword = '';
		// }

		//$output->enableOOUI();
		
		// # Get request data 
		$this->levelRangeMIN =  (int)$request->getText( 'levelRangeMIN' );
		$this->levelRangeMAX =  (int)$request->getText( 'levelRangeMAX' );
		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		$mobNameSearch = $request->getText( 'mobNameSearch' );
		$itemNameSearch = $request->getText( 'itemNameSearch' );
		$this->thRatesCheck = $request->getText( 'thRatesCheck' );
		$this->showBCNMdrops = $request->getText( 'showBCNMdrops' );
		$this->excludeNMs = $request->getText( 'excludeNMs' );
       
		$parser->getOutput()->updateCacheExpiry(0);
        $parser->getOutput()->addModules(['FFXIPackageHelper_dynamicContent']); 
		// $queryData = [ $this->query_limit, 
        //                 $params['mobname'], 
        //                 $params['itemname'], 
        //                 $params['zonename'], 
        //                 $params['bcnm'], 
        //                 $params['excludenm'],
        //                 $params['lvlmin'], 
        //                 $params['lvlmax'], 
        //                 $params['showth']
        //              ];
		
		$formTextInput = '
				<button type="button" id="copytoclipboard"  onclick="copyURLToClipboard();">Share Query</button>
			';	
////////////////////////////////////////////
		$zoneNamesList = self::getZoneNames($db->openConnection());
		$levelRangeList = self::lvlRngList();

		$formDescriptor = [
			'mobNameTextField' => [
				'label' => 'Mob/BCNM Name*', // Label of the field
				'class' => 'HTMLTextField', // Input type
				'name' => 'mobNameSearch',
				'help' => '<sup><i>&emsp;&emsp;&emsp;&emsp;Either the mob name or BCNM name should be used above.</i></sup>'
			],
			'levelRangeMIN' => [
				'type' => 'limitselect',
				'name' => 'levelRangeMIN',
				'label' => 'Lvl Min', // Label of the field
				//'class' => 'HTMLSelectField', // Input type
				'id' => 'asbsearch_LvlSelect',
				'cssclass' => 'asbsearch_LvlSelect',
				'options' => $levelRangeList,
				'default' => $this->levelRangeMIN,
			],
			'levelRangeMAX' => [
				'type' => 'limitselect',
				'name' => 'levelRangeMAX',
				'label' => 'Lvl Max', // Label of the field
				//'class' => 'HTMLSelectField', // Input type
				'cssclass' => 'asbsearch_LvlSelect',
				'options' => $levelRangeList,
				'help' => '<sup><i>Not Required. Leave as 0 to show all level ranges. Lvl Min must be less than or equal to Lvl Max. </i></sup>',
				'default' => $this->levelRangeMAX,
			],
			'itemNameTextField' => [
				'label' => 'Item Name*', // Label of the field
				'class' => 'HTMLTextField', // Input type
				'name' => 'itemNameSearch'
			],
			'zoneNameDropDown' => [
				'type' => 'limitselect',
				'name' => 'zoneNameDropDown',
				'label' => 'Zone', // Label of the field
				'class' => 'HTMLSelectField', // Input type
				'options' => $zoneNamesList,
				'default' => "searchallzones",
			],

			// 'zoneNameTextField' => [
			// 	'label' => 'Zone Name', // Label of the field
			// 	'class' => 'HTMLTextField', // Input type
			// 	'name' => 'zoneNameSearch'
			// ],
			'thRatesCheck' => [
				'type' => 'check',
				'label' => 'Show TH Rates',
				'name' => 'thRatesCheck',
			],
			'showBCNMdrops' => [
				'type' => 'check',
				'label' => 'Include BCNMs',
				'name' => 'showBCNMdrops',
			],
			'excludeNMs' => [
				'type' => 'check',
				'label' => 'Exclude NMs',
				'name' => 'excludeNMs',
			],
		];

		/**
		 * This IF should hold all conditions where the table should NOT be drawn
		 */
		if ( 	$mobNameSearch == "" &&
				$itemNameSearch== "" &&
				( $zoneNameDropDown == "searchallzones" || $zoneNameDropDown == "") &&
				( ($this->levelRangeMIN == 0 && $this->levelRangeMAX == 0) || ( $this->levelRangeMIN > $this->levelRangeMAX )) ) {
			//$wikitext = self::build_table(self::getFullDBTable());
			$wikitext = "<i>*Please use the search query above to generate a table. Only one of the three fields above is required. </i>";
		}
		/**
		 * This ELSE should hold all conditions where the table SHOULD be drawn
		 */
		else{
			//$zoneNameDropDown = isset($zoneNameSearch) ? $zoneNameSearch : 'searchallzones';

			$mobNameSearch = isset($mobNameSearch) ? $mobNameSearch : "*";
			$itemNameSearch = isset($itemNameSearch) ? $itemNameSearch : "*";
			$this->thRatesCheck = isset($thRatesCheck) ? $thRatesCheck : "0";
			//$showIDCheck = isset($showIDCheck) ? $showIDCheck : "0";
			$showBCNMdrops = isset($showBCNMdrops) ? $showBCNMdrops : "0";
			$excludeNMs = isset($excludeNMs) ? $excludeNMs : "0";

			$queryData = [ $this->query_limit, 
							$mobNameSearch, 
							$itemNameSearch, 
							$zoneNameDropDown, 
							$showBCNMdrops, 
							$excludeNMs,
							$this->levelRangeMIN, 
							$this->levelRangeMAX, 
							$this->thRatesCheck
						 ];

			$mobDropRatesData = $db->getDropRates($queryData); 

			$dm = new DataModel();
			$dm->parseData($mobDropRatesData);
			if ( $this->showBCNMdrops == 1) {
				$bcnmDropRatesData = $db->getBCNMCrateRates($queryData); //object output
				$dm->parseData($bcnmDropRatesData);
			}

			//$wikitext = self::build_table($dm->getDataSet());
			$wikitext = FFXIPackageHelper_QueryController::queryDropRates($dm->getDataSet(), $this->thRatesCheck );
			
		}
	
    	$htmlForm = new HTMLForm( $formDescriptor, $this->getContext(), 'ASBSearch_Form' );
		$htmlForm->setMethod( 'get' );
		// Text to display in submit button
		$htmlForm->setSubmitText( 'Show Table' );
	
		// We set a callback function
		$htmlForm->setSubmitCallback( [ $this, 'processInput' ] );

		// Call processInput() in your extends SpecialPage class on submit
		$htmlForm->show(); // Display the form
		
		//print_r($htmlForm->wasSubmitted());

		if ( $mobNameSearch != "" || $itemNameSearch != "" ){  $output->addHTML( "<br>" . $formTextInput  ); }

		$output->addWikiTextAsInterface( $wikitext );

	}

	
	public static function processInput( $formData ) {

		// If true is returned, the form won't display again
		// If a string is returned, it will be displayed as an error message with the form
		if ( $formData['mobNameTextField'] == ''  && $formData['itemNameTextField'] == '') {
			if ( $formData['zoneNameDropDown'] != 'searchallzones'  ) {
				return '*All results for this zone shown, with no additional filter.';
			}
			// if ( $formData['levelRangeMIN'] != 0 || $formData['levelRangeMAX'] != 0){
			// 	return 'safdafw';
			// }
			//else if ( $formData['zoneNameDropDown'] == 'searchallzones' || $formData['zoneNameDropDown'] == 'searchallzones') return '*Nothing was filled out.';
			// return ;
		}

		if ( (int)$formData['levelRangeMIN'] > (int)$formData['levelRangeMAX'] ) return '*Level ranges invalid.';

		return false;
	}

	function getZoneNames($dbr){
		///$dbr = $this->openConnection();
		$zonenames =  $dbr->newSelectQueryBuilder()
			->select( [ 'name' ] )
			->from( 'zone_settings' )
			->fetchResultSet(); 

		$result = [ ];
		foreach ($zonenames as $row) {
			$temp = ParserHelper::zoneERA_forList($row->name);
			if ( !isset($temp) ) { continue; }
			$result[$temp]=$row->name; 
			//print_r($result[$temp] .", " . $row->name);
		}
		$result[' ** Search All Zones ** '] = "searchallzones";
		ksort($result);
		return $result ;
	}

	function lvlRngList(){
		$result = [];
		for ( $i = 0; $i < 86; $i++){
			array_push($result, $i);
		}
		return $result;
	}


	// function showQueryResults($dropRatesArray, $showTH){
	// 	$html = "";

	// 	if ( !$dropRatesArray )  return "<i><b> No records (items) found</i></b>";

	// 	/************************
	// 	 * Row counter
	// 	 */
	// 	$totalRows = -1;
		
	// 	foreach ($dropRatesArray as $row) // test total records query'd
	// 	{
	// 		//print_r("row: " .$row['mobName']);
	// 		if ( $totalRows < 0 ) $totalRows = 0;
	// 		foreach($row['dropData']['items'] as $item ){
	// 			$totalRows ++;
	// 			// if ( $totalRows > $this->query_limit){
	// 			// 	return "<b><i>Query produced too many results to display. Queries are limited to 1000 results, for efficiency.
	// 			// 		Please reduce search pool by adding more to any of the search parameters.</i></b>";
	// 			// }
	// 		}
	// 	}

	// 	if ( $totalRows >= 0 ) {  
	// 		if ( $totalRows == 1500 ) $html .= "<i><b> $totalRows records (items) found, which is the search limit. Narrow search parameters.</i></b>";
	// 		else $html .= "<i><b> $totalRows records (items) found.</i></b>";
	// 		$html .= FFXIPackageHelper_HTMLTableHelper::table_DropRates($dropRatesArray, $showTH);
	// 	}

	// 	$html .= '</table></div>';

	// 	return $html;
	// }

	
	
}