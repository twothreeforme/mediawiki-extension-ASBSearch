<?php
//namespace MediaWiki\Extension\MyExtension;

//use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseFactory;


class SpecialASBSearch extends SpecialPage {
    public function __construct( ) {
        parent::__construct( 'ASBSearch' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  { 
		$out->addModules(['inputHandler']);
	}
	
	private $levelRangeMIN = 0;
	private $levelRangeMAX = 0;
	private $thRatesCheck = 0;
	//private $showIDCheck = 0;
	private $showBCNMdrops = 0;
	private $excludeNMs = 1;
	private $query_limit = 1500;

	private $dbUsername = 'horizon_wiki'; 
	private $dbPassword = 'KamjycFLfKEyFsogDtqM';

	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		//$output->addModules(['inputHandler']);
		$output->setPageTitle( $this->msg( 'asbsearch' ) );
		
		// db login variables - prevents swapping login info between testing server and horizon server		
		//print_r( $_SERVER['SERVER_NAME'] );
		if ( $_SERVER['SERVER_NAME'] == 'localhost' ){ 
			$this->dbUsername = 'root'; $this->dbPassword = '';
		}

		$output->enableOOUI();
		$this->setHeaders();

		# Get request data 
		$this->levelRangeMIN =  (int)$request->getText( 'levelRangeMIN' );
		$this->levelRangeMAX =  (int)$request->getText( 'levelRangeMAX' );
		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		//$zoneNameSearch = $request->getText( 'zoneNameSearch' );
		$mobNameSearch = $request->getText( 'mobNameSearch' );
		$itemNameSearch = $request->getText( 'itemNameSearch' );
		$this->thRatesCheck = $request->getText( 'thRatesCheck' );
		//$this->showIDCheck = $request->getText( 'showIDCheck' );
		$this->showBCNMdrops = $request->getText( 'showBCNMdrops' );
		$this->excludeNMs = $request->getText( 'excludeNMs' );

		$formTextInput = '
				<button type="button" id="copytoclipboard"  onclick="copyURLToClipboard();">Share Query</button>
			';	
////////////////////////////////////////////
		$zoneNamesList = self::getZoneNames();
		$levelRangeList = self::lvlRngList();

		// $uiLayout = new OOUI\FieldLayout(
		// 	new OOUI\TextInputWidget( [
		// 			'label' => 'Mob BCNM Name',
		// 			'help' => 'This is some inlined help. Assistive (optional) text, that isn\'t needed to '
		// 				. 'understand the widget\'s purpose.',
		// 			'placeholder' => 'Required',
		// 			'align' => 'top'
		// 		]
		// 		),
		// 	new OOUI\ButtonInputWidget( [
		// 			'id' => 'shareButton',
		// 			'infusable' => true,
		// 			'label' => 'Search',
		// 			'icon' => 'search',
		// 			'name' => 'search',
		// 			'flags' => [ 'primary', 'progressive'],
		// 			'method' => 'post'
		// 		] ),
		// 	[
		// 		'label' => 'Field layouts'
		// 	]
		// );
		
		// $uiLayout = new OOUI\ActionFieldLayout(
		// 	new OOUI\ButtonWidget( [
		// 		'id' => 'asbsearch-shareButton',
		// 		'infusable' => true,
		// 		'label' => 'Show Drops',
		// 		'icon' => 'search',
		// 		'name' => 'search',
		// 		'flags' => [ 'primary', 'progressive'],
		// 		'method' => 'post'
		// 	] ),
		// 	new OOUI\TextInputWidget( [
		// 		'id' => 'shareButton',
		// 		//'infusable' => true,
		// 		'label' => 'Mob/BCNM Name*',
		// 		//'name' => 'search',
		// 		'flags' => [ 'primary', 'progressive']
		// 	] ),
		// 	[
		// 		'id' => 'asbsearch-fieldlayout',
		// 		'label' => new OOUI\HtmlSnippet( '<i><b>Disclosure:</b>  All data here is from AirSkyBoat, with minor additions/edits made based on direct feedback from Horizon Devs.</i>' ),
		// 		'align' => 'top',
		// 		'help' => 'Test help bubble'
		// 	]
		// 	);
		
		//$output->addHTML( $uiLayout );
		//$output->addHTML( $testForm );

/////////// HTML FORM TESTING
		
		// formDescriptor Array to tell HTMLForm what to build

		$formDescriptor = [
			// 'info' => [
			// 	'type' => 'info',
			// 	'label' => 'info',
			// 	// Value to display
			// 	'default' => 'Select a zone, and enter characters to search for in the mob name. Leave \'Mob name\' field blank to see all mobs. ',
			// 	// If true, the above string won't be HTML escaped
			// 	'raw' => true,
			// ],
			
			
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
				'class' => 'HTMLSelectField', // Input type
				'options' => $levelRangeList,
				//'default' => "-",
			],
			'levelRangeMAX' => [
				'type' => 'limitselect',
				'name' => 'levelRangeMAX',
				'label' => 'Lvl Max', // Label of the field
				'class' => 'HTMLSelectField', // Input type
				'options' => $levelRangeList,
				'help' => '<sup><i>Not Required. Leave as 0 to show all level ranges.</i></sup>'
				//'default' => "0",
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
			// 'showIDCheck' => [
			// 	'type' => 'check',
			// 	'label' => 'Show Entity IDs',
			// 	'name' => 'showIDCheck',
			// 	'tooltip' => 'These options are in row 3.', // Tooltip to add to the Row 3 row label
			// ]
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

		if ( 	$mobNameSearch == "" &&
				$itemNameSearch== "" &&
				( $zoneNameDropDown == "searchallzones" || $zoneNameDropDown == "") &&
				( $this->levelRangeMIN == 0 && $this->levelRangeMAX == 0) ) {
			//$wikitext = self::build_table(self::getFullDBTable());
			$wikitext = "<i>*Please use the search query above to generate a table. Only one of the three fields above is required. </i>";
		}
		else{
			//$zoneNameDropDown = isset($zoneNameSearch) ? $zoneNameSearch : 'searchallzones';

			$mobNameSearch = isset($mobNameSearch) ? $mobNameSearch : "*";
			$itemNameSearch = isset($itemNameSearch) ? $itemNameSearch : "*";

			$thRatesCheck = isset($thRatesCheck) ? $thRatesCheck : "0";
			//$showIDCheck = isset($showIDCheck) ? $showIDCheck : "0";
			$showBCNMdrops = isset($showBCNMdrops) ? $showBCNMdrops : "0";
			$excludeNMs = isset($excludeNMs) ? $excludeNMs : "1";

			$mobDropRatesData = self::getRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch);  //object output
			//$bcnmDropRatesData = self::getBCNMCrateRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch);

			$mobDrops = new DataModel();
			$mobDrops->parseData($mobDropRatesData);
			//print_r($mobDrops);
			if ( $this->showBCNMdrops == 1) {
				$bcnmDropRatesData = self::getBCNMCrateRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch); //object output
				$mobDrops->parseData($bcnmDropRatesData);
			}

			$wikitext = self::build_table($mobDrops->getDataSet());
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
		if ( $formData['levelRangeMIN'] > $formData['levelRangeMAX'] ) return '*Level ranges invalid.';

		return false;
	}

	public function openConnection() {
       // $status = Status::newGood();

		//wfDebugLog( 'userdebug', "ASBSearch->openConnection() username: $this->dbUsername");

        try {
            $db = ( new DatabaseFactory() )->create( 'mysql', [
                'host' => 'localhost',
                'user' => $this->dbUsername,
                'password' => $this->dbPassword,
                'dbname' => 'ASB_Data',
                'flags' => 0,
                'tablePrefix' => ''] );
            //$status->value = $db;
			$returnDB = $db;
        } catch ( DBConnectionError $e ) {
            //$status->fatal( 'config-connection-error', $e->getMessage() );
			print_r('issue');
        }
 

        // return $status;
		return $returnDB;
    }

	function getZoneNames(){
		$dbr = $this->openConnection();
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

	function getRates($zoneNameSearch, $mobNameSearch, $itemNameSearch){
		$mobNameSearch = ParserHelper::replaceSpaces($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$mobNameSearch = ParserHelper::replaceApostrophe($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"mob_groups.name LIKE '%$mobNameSearch%'",
			"item_basic.name LIKE '%$itemNameSearch%'",
			"mob_droplist.dropid !=0 ",
			"( mob_groups.content_tag = 'COP' OR mob_groups.content_tag IS NULL OR mob_groups.content_tag = 'NEODYNA')",
			//"mob_groups.content_tag IS NULL ",
		];

			//up_property = 'enotifwatchlistpages'
		if ( $zoneNameSearch !=  'searchallzones' ) {
			$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
			//$str = "zone_settings.name => $zoneNameSearch';
			array_push($query, "zone_settings.name = '$zoneNameSearch'");
		}
		if ( $this->excludeNMs == 1) {
			array_push($query, "mob_pools.mobType != 2");
			array_push($query, "mob_pools.mobType != 16");
			array_push($query, "mob_pools.mobType != 18");
		}
		if ( $this->levelRangeMIN > 0){
			array_push($query, "mob_groups.minLevel >= '$this->levelRangeMIN'");
		}
		if ( $this->levelRangeMAX > 0){
			array_push($query, "mob_groups.maxLevel <= '$this->levelRangeMAX'");
		}
		$dbr = $this->openConnection();
		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'mob_droplist.itemRate',
						'mob_droplist.dropType',
						'mob_droplist.groupId',
						'mob_droplist.groupRate',
						'zone_settings.name AS zoneName',
						'mob_groups.name AS mobName',
						'mob_groups.minLevel AS mobMinLevel',
						'mob_groups.maxLevel AS mobMaxLevel',
						'item_basic.name AS itemName', 
						//'item_basic.sortname AS itemSortName',
						'mob_groups.changes_tag AS mobChanges',
						'item_basic.changes_tag AS itemChanges',
						'mob_droplist.changes_tag AS dropChanges',
						'mob_pools.mobType'
						] )
			->from( 'mob_droplist' )
			->join( 'mob_groups', null, 'mob_groups.dropid=mob_droplist.dropid' )
			->join( 'item_basic', null, 'item_basic.itemid=mob_droplist.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->join( 'mob_pools', null, 'mob_pools.poolid=mob_groups.poolid')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			->limit( $this->query_limit)
			->fetchResultSet(); 
	}

	function getBCNMCrateRates($zoneNameSearch, $bcnmNameSearch, $itemNameSearch){
		$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
		//if ( $zoneNameSearch != 'searchallzones' )
		if ( !ExclusionsHelper::zoneIsBCNM($zoneNameSearch) && $zoneNameSearch != 'searchallzones' ) return;

		//if ( gettype($itemNameSearch) ==  )
		//print_r(gettype($itemNameSearch));

		$bcnmNameSearch = ParserHelper::replaceSpaces($bcnmNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$bcnmNameSearch = ParserHelper::replaceApostrophe($bcnmNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"bcnm_info.name LIKE '%$bcnmNameSearch%'",
			"item_basic.name LIKE '%$itemNameSearch%'" ];

			//up_property = 'enotifwatchlistpages'
		if ( $zoneNameSearch !=  'searchallzones' ) {
			//$str = "zone_settings.name => $zoneNameSearch';
			array_push($query, "zone_settings.name = '$zoneNameSearch'");
		}

		$dbr = $this->openConnection();
		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'hxi_bcnm_crate_list.itemRate',
						//'hxi_bcnm_crate_list.dropType',
						'hxi_bcnm_crate_list.groupId',
						'hxi_bcnm_crate_list.groupRate',
						'zone_settings.name AS zoneName',
						'bcnm_info.name AS mobName',
						//'mob_groups.minLevel AS mobMinLevel',
						//'mob_groups.maxLevel AS mobMaxLevel',
						'item_basic.name AS itemName', 
						//'item_basic.sortname AS itemSortName',
						'hxi_bcnm_crate_list.changes_tag AS itemChanges',
						'hxi_bcnm_crate_list.gilAmount AS gilAmt',
						'hxi_bcnm_crate_list.itemId'  ] )
			->from( 'hxi_bcnm_crate_list' )
			->join( 'bcnm_info', null, 'bcnm_info.bcnmId=hxi_bcnm_crate_list.bcnmId' )
			->join( 'item_basic', null, 'item_basic.itemid=hxi_bcnm_crate_list.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=bcnm_info.zoneId')
			->orderBy( 'groupId', 'ASC' )
			->where( $query	)
			->limit(500)
			->fetchResultSet(); 
	}

	

	function _tableHeaders(){
		$html = "";
		/************************
		 * Initial HTML for the table
		 */
		$html .= "<br>
		<div ><i><b>Disclosure:</b>  All data here is from AirSkyBoat, with minor additions/edits made based on direct feedback from Horizon Devs.<br>Any Horizon specific changes made to the table will be marked with the Template:Changes->{{Changes}} tag.<br><b>**</b> are nuanced drop rates. Please refer to that specific page for more details on how drop rates are calculated.
		<br> <strike>Item Name</strike><sup>(OOE)</sup> are Out of Era items, and are left in the table because it is still unknown how removing these has effected Group drop rates (mainly from BCNMs).</i> </div>
		<div style=\"max-height: 900px; overflow: auto; display: inline-block; width: 100%;\">
		<table id=\"asbsearch_dropstable\" class=\"sortable\">
			<tr><th>Zone Name</th>
			<th>Mob Name <sup>(lvl)</sup></th>
			<th>Details</th>
			<th>Item - Drop Rate</th>
			";
			//<th>Drop Percentage</th>
			//<th>Item (sort)Name</th>
		if ( $this->thRatesCheck == 1) $html .= "<th>TH1</th><th>TH2</th><th>TH3</th><th>TH4</th>";
		$html .= "</tr>";

		return $html;
	}

	function build_table($dropRatesArray)
	{		
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
				if ( $totalRows > $this->query_limit){
					return "<b><i>Query produced too many results to display. Queries are limited to 1000 results, for efficiency.
						Please reduce search pool by adding more to any of the search parameters.</i></b>";
				}
			}
		}

		if ( $totalRows >= 0 ) {  $html .= "<i><b> $totalRows records (items) found</i></b>"; }

		$html .= self::_tableHeaders();

		foreach ( $dropRatesArray as $row ) {

				/*******************************************************
				 * Removing OOE 
				 */
				// First check zone names
				
				//$zn = str_replace("[S]", "(S)", $zn );
				// $skipRow = false;
				// foreach( ExclusionsHelper::$zones as $v) { 
				// 	//print_r($zn);
				// 	if ( $zn == $v ) { $skipRow = true; break; } }
				// if ( $skipRow == true ) continue;
				$zn = ParserHelper::zoneERA_forList($row['zoneName']);
				if ( !$zn ) { continue; }
				if ( ExclusionsHelper::mobIsOOE($row['mobName']) ) { continue; }
				/*******************************************************/

				/*******************************************************
				 * This section generally to help deal with gaps between the mob drops and bcnm crate lists 
				 */
				$minL = null; $maxL = null; $dType = null; $mobChanges = null;
				// if ( property_exists($row, 'mobMinLevel') ) $minL = $row->mobMinLevel;
				// if ( property_exists($row, 'mobMaxLevel') ) $maxL = $row->mobMaxLevel;
				// if ( property_exists($row, 'dropType') ) $dType = $row->dropType;
				if ( array_key_exists('mobMinLevel', $row) ) $minL = $row['mobMinLevel'];
				if ( array_key_exists('mobMaxLevel', $row) ) $maxL = $row['mobMaxLevel'];
				if ( array_key_exists('type', $row['dropData']) ) $dType = $row['dropData']['type'];
				else $dType = 1; 	// All bcnm drops are part of a group 	
				if ( array_key_exists('mobChanges', $row) ) $mobChanges = $row['mobChanges'];
				else $mobChanges = 0;

				$zn = ParserHelper::zoneName($row['zoneName']);
				$mn = ParserHelper::mobName($row['mobName'], $minL, $maxL, $row['mobType'], $row['zoneName'], $mobChanges); //need to readdress this later
				
				$html .= "<tr><td><center>$zn</center></td><td><center>$mn</center></td>";
				/*******************************************************/


				/*******************
				 * Handle drop details / grouping / type
				 */
				//print_r($dType);
				$dropDetails = "-";
				if ( $row['dropData']['groupId'] != "0" ) {
					$gR = $row['dropData']['groupRate'];
					if ( $gR > 1000 ) $gR = 1000;
					$dropDetails = "Group " . $row['dropData']['groupId'] . " - " . ($row['dropData']['groupRate'] / 10) . "%" ;	
				}
				else {
					switch ($dType) {
							case 2:
								$dropDetails = "Steal";
								break;
							case 4;
								$dropDetails = 'Despoil';
								break;
							default:
								break;
						}
				}
				$html .= "<td><center>$dropDetails</center></td>";
				/*******************************************************/


				/*******************
				 * Add items as individual tables inside a cell
				 */
				$html .= "<td><table id=\"asbsearch_dropstable2\" >";
				for ( $i = 0; $i < count($row['dropData']['items']); $i ++){
					$item = $row['dropData']['items'][$i];

					$i_n = ParserHelper::itemName($item);
					$gR = $row['dropData']['groupRate'];
					if ( $gR < 1000 ) $gR = 1000;
					$i_dr = ((int)$item['dropRate'] / $gR) * 100 ;
					

					if ( $dType == 2 || $dType == 4 ) $html .= "<tr><center>" . $i_n . "</center></tr>";
					else if ( $i_dr == 0 ) $html .= "<tr><center>" . $i_n . " - " . " ??? </center></tr>";
					else if ( $item['id'] == 65535 ) $html .= "<tr><center>[[Image:Gil_icon.png|18px]] " . $i_n . " - " . $item['gilAmt'] ."</center></tr>";
					else $html .= "<tr><center>" . $i_n . " - " . $i_dr ."%</center></tr>";
				}
				$html .= "</table></td>"; 
				/*******************************************************/


				/*******************
				 * Add TH values
				 */
				if ( $this->thRatesCheck == 1){
					$item = $row['dropData']['items'][0];
					$cat = 0; // @ALWAYS =     1000;  -- Always, 100%

					if ( $row['dropData']['groupId'] == "0" ) {
						//print_r($dType);
						if ( $item['dropRate'] == 0 || $dType != 0 ) $cat = 8;
						elseif ( $item['dropRate'] == 240 ) $cat = 1; 	//@VCOMMON -- Very common, 24%
						elseif ( $item['dropRate'] == 150 ) $cat = 2; 	//@COMMON -- Common, 15%
						elseif ( $item['dropRate'] == 100 ) $cat = 3; 	//@UNCOMMON -- Uncommon, 10%
						elseif ( $item['dropRate'] == 50 ) $cat = 4; 	//@RARE -- Rare, 5%
						elseif ( $item['dropRate'] == 10 ) $cat = 5; 	//@VRARE -- Very rare, 1%
						elseif ( $item['dropRate'] == 5 ) $cat = 6; 	//@SRARE -- Super Rare, 0.5%
						elseif ( $item['dropRate'] == 1 ) $cat = 7; 	//@URARE -- Ultra rare, 0.1%
						else $cat = 8;
					}
					else $cat = 8;

					$th1 = 0; $th2 = 0; $th3 = 0; $th4 = 0;
					
					switch ($cat) {
						case 0:
							$th1 = 100; $th2 = 100; $th3 = 100; $th4 = 100;
							break;
						case 1:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 2.333); $th3 = self::thAdjust($item['dropRate'], 2.5); $th4 = self::thAdjust($item['dropRate'], 2.666);
							break;
						case 2:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 2.666); $th3 = self::thAdjust($item['dropRate'], 2.833); $th4 = self::thAdjust($item['dropRate'], 3);
							break;
						case 3:
							$th1 = self::thAdjust($item['dropRate'], 1.2); $th2 = self::thAdjust($item['dropRate'], 1.5); $th3 = self::thAdjust($item['dropRate'], 1.65); $th4 = self::thAdjust($item['dropRate'], 1.8);
							break;
						case 4:
							$th1 = self::thAdjust($item['dropRate'], 1.2); $th2 = self::thAdjust($item['dropRate'], 1.4); $th3 = self::thAdjust($item['dropRate'], 1.5); $th4 = self::thAdjust($item['dropRate'], 1.6);
							break;	
						case 5:
							$th1 = self::thAdjust($item['dropRate'], 1.5); $th2 = self::thAdjust($item['dropRate'], 2); $th3 = self::thAdjust($item['dropRate'], 2.25); $th4 = self::thAdjust($item['dropRate'], 2.5);
							break;		
						case 6:
							$th1 = self::thAdjust($item['dropRate'], 1.5); $th2 = self::thAdjust($item['dropRate'], 2); $th3 = self::thAdjust($item['dropRate'], 2.4); $th4 = self::thAdjust($item['dropRate'], 2.8);
							break;
						case 7:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 3); $th3 = self::thAdjust($item['dropRate'], 3.5); $th4 = self::thAdjust($item['dropRate'], 4);
							break;
						case 8;
							$th1 = "-"; $th2 = "-"; $th3 = "-"; $th4 = "-";
							break;						
						default:
						break;
					}

					$html .= "<td><center>$th1 %</center></td><td><center>$th2 %</center></td><td><center>$th3 %</center></td><td><center>$th4 %</center></td>";
				}
				/*******************************************************/

				$html .= "</tr>";

		}
		$html .= '</table></div>';

		return $html;
	}

	function thAdjust($rate, $multiplier){
		$num = round(($rate * $multiplier) / 10, 2);
		if ( $num >= 100 ) return "~99";
		else return $num;
	}

	
}