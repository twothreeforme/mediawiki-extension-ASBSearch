<?php
//namespace MediaWiki\Extension\MyExtension;

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseFactory;


//set_time_limit(0);

class SpecialASBSearch extends SpecialPage {
    public function __construct( $title = 'ASBSearch' ) {
        parent::__construct( 'ASBSearch' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  { 
		$out->addModules(['inputHandler']);
	}

	private $thRatesCheck = 0;

	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		//$output->addModules(['inputHandler']);

		//$output->enableOOUI();
		$this->setHeaders();

		# Get request data 
		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		//$zoneNameSearch = $request->getText( 'zoneNameSearch' );
		$mobNameSearch = $request->getText( 'mobNameSearch' );
		$itemNameSearch = $request->getText( 'itemNameSearch' );
		$this->thRatesCheck = $request->getText( 'thRatesCheck' );
		//$this->showIDCheck = $request->getText( 'showIDCheck' );

		//print_r("*" . $mobNameSearch . "*");
 
		//$wikitext = 'Hello world!';	
		// $title = Title::newFromText("Special:ASBSearch");
		// $url = $title->getFullURL();
		// print_r($url);
////////////////////////////////////////////
		// if (isset($zoneNameDropDown) ||  ) {
			if ( $mobNameSearch == "" && $itemNameSearch== "" ){
				//$wikitext = self::build_table(self::getFullDBTable());
				$wikitext = "<i>*Please use the search query above to generate a table. Mob name OR Item name are required.</i>";
			}
			else{
				$zoneNameSearch = isset($zoneNameSearch) ? $zoneNameSearch : "*";
				$mobNameSearch = isset($mobNameSearch) ? $mobNameSearch : "*";
				$itemNameSearch = isset($itemNameSearch) ? $itemNameSearch : "*";
				$thRatesCheck = isset($thRatesCheck) ? $thRatesCheck : "0";
				$showIDCheck = isset($showIDCheck) ? $showIDCheck : "0";

				$mobDropRatesData = self::getRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch);
				$bcnmDropRatesData = self::getBCNMCrateRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch);

				$wikitext = self::build_table( [$mobDropRatesData, $bcnmDropRatesData]);
				//$wikitext = self::arrayFromRates(self::getRates($zoneNameDropDown, $mobNameSearch, $itemNameSearch));
				//$wikitext = "<p>testing</p>";
			}
		//}

		// $formDropDown = '<form method="post">;
		// 		<select name="lang" multiple>
		// 			<option>choose zone</option>
		// 			<option value="akjweh">alhkjf</option>
		// 		</select>
		// 	</form>';

		// $formTextInput = '
		// 	<form >
		// 		<input id="zonenametext" name="zonenametext" placeholder="Name" />
		// 		<button type="button" id="submitFormData"  onclick="SubmitFormData();">Submit</button>
		// 	</form>
		// ';

		// $testForm = '
        // <form id="myForm" method="post">
        //         <label for="name">Name:</label>
        //         <input name="name" id="name" type="text" />

        //         <label for="email">Email:</label>
        //         <input name="email" id="email" type="text" />

        //         <label for="phone">Phone No:</label>
        //         <input name="phone" id="phone" type="text" />

        //         <label>Gender:</label>
        //         <input name="gender" type="radio" value="male" id="male"><label for="male">Male</label>
        //         <input name="gender" type="radio" value="female" id="female"><label for="female">Female</label>
 
        //     <button type="button" id="submitFormData" >Submit</button>
        // </form>

		// ';

		/*	
		<form  method="POST" action = "<?php $_PHP_SELF ?>">
		<input type="text" id="a123" name="test" value="submit" >
		*/

		// $uiLayout = new OOUI\HorizontalLayout( [
		// 	'items' => [
		// 		new OOUI\TextInputWidget( [
		// 			'placeholder' => 'A form text field'
		// 		] ),
		// 		new OOUI\ButtonWidget( [
		// 			'label' => 'Search',
		// 			'icon' => 'search',
		// 			'name' => 'search',
		// 			'flags' => [ 'primary', 'progressive'],
		// 			'method' => 'post'
		// 		] )
		// 	]
		// ] );
		// $btn = new OOUI\TextInputWidget( [
		// 	'placeholder' => 'A form text field'
		// ] );

		//$output->addHTML( $uiLayout );
		//$output->addHTML( $formTextInput );
		//$output->addHTML( $testForm );

/////////// HTML FORM TESTING
		
		$zoneNamesList = self::getZoneNames();
		// formDescriptor Array to tell HTMLForm what to build
		$lang = $this->getLanguage();
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
				'help' => '<i>Either the mob name or BCNM name should be used here.</i>'
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
				'default' => "Aht Urhgan Whitegate",
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
				'tooltip' => 'These options are in row 3.', // Tooltip to add to the Row 3 row label
			],
			'showIDCheck' => [
				'type' => 'check',
				'label' => 'Show Entity IDs',
				'name' => 'showIDCheck',
				'tooltip' => 'These options are in row 3.', // Tooltip to add to the Row 3 row label
			]
		];
	
		// Build the HTMLForm object, calling the form 'myform'
    	$htmlForm = new HTMLForm( $formDescriptor, $this->getContext(), 'myform' );
	
		// Text to display in submit button
		$htmlForm->setSubmitText( 'Show Drops' );
	
		// We set a callback function
		$htmlForm->setSubmitCallback( [ $this, 'processInput' ] );  
		// Call processInput() in your extends SpecialPage class on submit

		$htmlForm->setMethod( 'post' );
		$htmlForm->show(); // Display the form
		
		$output->addWikiTextAsInterface( $wikitext );
	}


	public static function processInput( $formData ) {
		// If true is returned, the form won't display again
		// If a string is returned, it will be displayed as an error message with the form
		if ( $formData['mobNameTextField'] == ''  && $formData['itemNameTextField'] == ''  ) {
			return '*Either the Mob field or Item field must be filled.';
		}
		return false;
	}

	public function openConnection() {
       // $status = Status::newGood();
        try {
            $db = ( new DatabaseFactory() )->create( 'mysql', [
                'host' => 'localhost',
                // 'user' => 'root',
                // 'password' => '',
				'user' => 'horizon_wiki',
				'password' => 'KamjycFLfKEyFsogDtqM',
                //'ssl' => $this->getVar( 'wgDBssl' ),
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

	function getRates($zoneNameSearch, $mobNameSearch, $itemNameSearch){
		$mobNameSearch = ParserHelper::replaceSpaces($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceSpaces($itemNameSearch);

		//$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$mobNameSearch = ParserHelper::replaceApostrophe($mobNameSearch);
		$itemNameSearch = ParserHelper::replaceApostrophe($itemNameSearch);

		$query = [ 
			//"zone_settings.name" => $zoneNameSearch,
			"mob_groups.name LIKE '%$mobNameSearch%'",
			"item_basic.name LIKE '%$itemNameSearch%'" ];

			//up_property = 'enotifwatchlistpages'
		if ( $zoneNameSearch !=  'searchallzones' ) {
			$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
			//$str = "zone_settings.name => $zoneNameSearch';
			array_push($query, "zone_settings.name = '$zoneNameSearch'");
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
						'item_basic.sortname AS itemSortName', ] )
			->from( 'mob_droplist' )
			->join( 'mob_groups', null, 'mob_groups.dropid=mob_droplist.dropid' )
			->join( 'item_basic', null, 'item_basic.itemid=mob_droplist.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=mob_groups.zoneid')
			->where( $query	) 
			->fetchResultSet(); 
	}

	function getBCNMCrateRates($zoneNameSearch, $bcnmNameSearch, $itemNameSearch){
		$zoneNameSearch = ParserHelper::replaceSpaces($zoneNameSearch);
		//if ( $zoneNameSearch != 'searchallzones' )
		if ( !ExclusionsHelper::zoneHasBCNM($zoneNameSearch) && $zoneNameSearch != 'searchallzones' ) return;

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
						'item_basic.sortname AS itemSortName', ] )
			->from( 'hxi_bcnm_crate_list' )
			->join( 'bcnm_info', null, 'bcnm_info.bcnmId=hxi_bcnm_crate_list.bcnmId' )
			->join( 'item_basic', null, 'item_basic.itemid=hxi_bcnm_crate_list.itemId')
			->join( 'zone_settings', null, 'zone_settings.zoneid=bcnm_info.zoneId')
			->where( $query	) 
			->fetchResultSet(); 

			// 			'mob_droplist.itemRate',
			// 			'mob_droplist.dropType',
			// 			'mob_droplist.groupId',
			// 			'mob_droplist.groupRate',
			// 			'zone_settings.name AS zoneName',
			// 			'mob_groups.name AS mobName',
			// 			'mob_groups.minLevel AS mobMinLevel',
			// 			'mob_groups.maxLevel AS mobMaxLevel',
			// 			'item_basic.name AS itemName', 
			// 			'item_basic.sortname AS itemSortName', ] )
	}

	// function arrayFromRates($dataset){ //uses schema from self::getRates
	// 	$array = [];
	// 	foreach ($dataset as $row)
	// 	{
			// $zn = ParserHelper::replaceUnderscores($row->zoneName);

			// /*******************************************************
			//  * Removing OOE 
			//  */
			// // First check zone names
			
			// //$zn = str_replace("[S]", "(S)", $zn );
			// // $skipRow = false;
			// // foreach( ExclusionsHelper::$zones as $v) { 
			// // 	//print_r($zn);
			// // 	if ( $zn == $v ) { $skipRow = true; break; } }
			// // if ( $skipRow == true ) continue;			
			// if ( ExclusionsHelper::zoneIsOOE($row->zoneName) ) { continue; }
			// if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
			// /*******************************************************/

			// $zn = ParserHelper::zoneName($row->zoneName);
			// $mn = ParserHelper::mobName($row->mobName, $row->mobMinLevel, $row->mobMaxLevel);
			// $in = ParserHelper::itemName($row->itemName);

			
			// if ( $itemGroup != 0 ) { // item group has been set from a previous iteration and needs to be handled

			// }

			/******************************************************
			 * Handle drop TYPE & RATE
			 */
			// $dropGroup;
			// $droprate;	
			// switch ($row->dropType) {
			// 	case 0;
			// 		$droprate = round(($row->itemRate) / 10 ) ;
			// 		$droprate = "$droprate %";
			// 		$dropGroup = "-";
			// 		break;
			// 	case 1:
			// 		$dropGroup = "Group $row->groupId - " . ($row->groupRate / 10 )."%" ;
			// 		$droprate = round(($row->itemRate) / 10 ) ;
			// 		$droprate = "$droprate %";
			// 		break;
			// 	case 2:
			// 		$droprate = 'Steal';
			// 		$dropGroup = "-";
			// 		break;
			// 	case 4;
			// 		$droprate = 'Despoil';
			// 		$dropGroup = "-";
			// 		break;
			// 	default:
			// 		// $droprate = round(($row->itemRate) / (ParserHelper::getVarRate($row->groupRate)[1] / 100 ) ) ;
			// 		// $droprate = "$droprate %";
			// 		break;
			// }			

			/**
			 * Unique by 1x zonename and 1x mobname
			 * $array['zonename'] = [
			 * 		zonename['mobname'] = [ 
			 * 			moblevel = [ mobminlevel, mobmaxlevel ]
			 * 			dropdata = [ dropgroup, [ itemname, droprate ] 
			 * 		]	
			 * 	]
			 */
			
			
	// 		$dropGroup = [ $row->groupId, $row->groupRate ];
	// 		$itemrate = [ $row->itemName, $row->itemRate ];

	// 		$temp = array (
	// 			'mobName' => $row->mobName, 
	// 			'mobMinLevel' => $row->mobMinLevel,
	// 			'mobMaxLevel' => $row->mobMaxLevel,
	// 			'dropData' => array (
	// 				'groupId' => $row->groupId,
	// 				'groupRate' => $row->groupRate,
	// 				'item' => array(
	// 					'name' => $row->itemName,
	// 					'dropRate' => $row->itemRate
	// 				)));
	// 		// array_push ( $array[$row->zoneName], $temp );

	// 		if ( !array_key_exists($row->zoneName, $array) ) { $array[$row->zoneName] = []; }
	// 		if ( !array_key_exists($row->mobName, $array[$row->zoneName])){ 
	// 			array_push ( $array[$row->zoneName], $temp );
	// 			continue; 
	// 		}
	// 		//unset($temp['mobName']);
	// 		if (  )

	// 		//[ $row->zoneName, $row->mobName, $row->mobMinLevel, $row->mobMaxLevel, [ ] ]
	// 	}
	// 	// foreach ($array as $a){
	// 	// 	$html = "<br>" . $a["zoneName"]["dropData"]["groupId"] ;
	// 	// }
	// 	print_r($array);
	// 	return $html;
	// } 

	function _tableHeaders(){
		$html = "";
		/************************
		 * Initial HTML for the table
		 */
		$html .= "<br>
		<div ><i>Disclosure: All data here is from AirSkyBoat. Any Horizon specific changes made to the table will be marked with the Template:Changes->{{Changes}} tag.</i> </div>
		<div style=\"max-height: 900px; overflow: auto; display: inline-block; width: 100%;\">
		<table id=\"dropstable\">
			<tr><th>Zone Name</th>
			<th>Mob Name <sup>(lvl)</sup></th>
			<th>Drop Group</th>
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
		$items = $dropRatesArray[0];
		
		$html = "";

		/************************
		 * Row counter
		 */
		$totalRows = -1;
		
		foreach ($items as $row) // test total records query'd
		{
			if ( $totalRows < 0 ) $totalRows = 0;
			$totalRows ++;
			if ( $totalRows > 1000){
				return "<b><i>Query produced too many results to display. Queries are limited to 1000 results, for efficiency.
					Please reduce search pool by adding more to any of the search parameters.</i></b>";
			}
		}

		if ( $totalRows >= 0 ) {  $html .= "<i><b> $totalRows records found</i></b>"; }

		$html .= self::_tableHeaders();

		$itemGroup = 0;

		foreach ( $dropRatesArray as $ratesTable ) {
			foreach ($ratesTable as $row)
			{
				$zn = ParserHelper::replaceUnderscores($row->zoneName);

				// This section generally to help deal with gaps between the mob drops and bcnm crate lists
				$minL = null; $maxL = null; $dType = null;
				if ( property_exists($row, 'mobMinLevel') ) $minL = $row->mobMinLevel;
				if ( property_exists($row, 'mobMaxLevel') ) $maxL = $row->mobMaxLevel;
				if ( property_exists($row, 'dropType') ) $dType = $row->dropType;
				else $dType = 1; 	// All bcnm drops are part of a group

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
				//if ( ExclusionsHelper::zoneIsOOE($row->zoneName) ) { continue; }
				if ( ExclusionsHelper::mobIsOOE($row->mobName) ) { continue; }
				/*******************************************************/

				$zn = ParserHelper::zoneName($row->zoneName);
				$mn = ParserHelper::mobName($row->mobName, $minL, $maxL);
				$in = ParserHelper::itemName($row->itemName);

				
				// if ( $itemGroup != 0 ) { // item group has been set from a previous iteration and needs to be handled

				// }

				/******************************************************
				 * Handle drop TYPE & RATE
				 */
				$dropGroup;
				$droprate;	
				switch ($dType) {
					case 0;
						$droprate = round(($row->itemRate) / 10 ) ;
						$droprate = "$droprate %";
						$dropGroup = "-";
						break;
					case 1:
						$dropGroup = "Group $row->groupId - " . ($row->groupRate / 10 )."%" ;
						$droprate = round(($row->itemRate) / 10 ) ;
						$droprate = "$droprate %";
						break;
					case 2:
						$droprate = 'Steal';
						$dropGroup = "-";
						break;
					case 4;
						$droprate = 'Despoil';
						$dropGroup = "-";
						break;
					default:
						// $droprate = round(($row->itemRate) / (ParserHelper::getVarRate($row->groupRate)[1] / 100 ) ) ;
						// $droprate = "$droprate %";
						//$droprate = round(($row->itemRate) / 10 ) ;
						//$dropGroup = "-";
						break;
				}

				/*******************************************************/
				// 					Zone Name  		| 				Mob Name 		| 			Drop Group 		|			Item Name - Drop rate	
				// if the previous item was in a droprate group
				// and this item is not, then the html for the row
				// needs to be closed out
				// allow TH rows to be written after the last item in the group
				// if (  $itemGroup != 0 && $row->groupId == 0) {
				// 	$html .= " </table></td>";
				// 	//continue;
				// }
				// // else if the previous item was in a droprate group
				// // and this item is in the same droprate group then 
				// // continue with the consolidated row 
				// else if (  $itemGroup != 0 && $itemGroup == $row->groupId ) {
				// 	$html .= "<tr><td><center>$in - $droprate</center></td></tr>";
				// 	continue;
				// }
					
				// // else if the previous item was in a droprate group
				// // and this item is NOT in the same droprate group then 
				// // this is the start of a new group and the old one needs 
				// // to be closed first, then a new one started
				// else if (  $itemGroup != 0 && $itemGroup != $row->groupId ) {
				// 	$html .= " </table></td><td><table width=\"100%\"><tr><td><center>$in - $droprate</center></td></tr>";
				// 	continue;
				// }

				// // else if the previous item was in a droprate group
				// // and this item is in the same droprate group then 
				// // continue with the consolidated row 
				// else 
				$html .= "<tr><td><center>$zn</center></td><td><center>$mn</center></td><td><center>$dropGroup</center></td><td><center>$in - $droprate</center></td>";
				
				//$itemGroup = $row->groupId; //reset item group for this iteration


				if ( $this->thRatesCheck == 1){
					
					$cat = 0; // @ALWAYS =     1000;  -- Always, 100%
					if ( $row->itemRate == 0 || $row->dropType != 0 ) $cat = 8;
					elseif ( $row->itemRate == 240 ) $cat = 1; 	//@VCOMMON -- Very common, 24%
					elseif ( $row->itemRate == 150 ) $cat = 2; 	//@COMMON -- Common, 15%
					elseif ( $row->itemRate == 100 ) $cat = 3; 	//@UNCOMMON -- Uncommon, 10%
					elseif ( $row->itemRate == 50 ) $cat = 4; 	//@RARE -- Rare, 5%
					elseif ( $row->itemRate == 10 ) $cat = 5; 	//@VRARE -- Very rare, 1%
					elseif ( $row->itemRate == 5 ) $cat = 6; 	//@SRARE -- Super Rare, 0.5%
					elseif ( $row->itemRate == 1 ) $cat = 7; 	//@URARE -- Ultra rare, 0.1%
					else $cat = 8;

					$th1 = 0; $th2 = 0; $th3 = 0; $th4 = 0;
				
					switch ($cat) {
						case 0:
							$th1 = 100; $th2 = 100; $th3 = 100; $th4 = 100;
							break;
						case 1:
							$th1 = self::thAdjust($row->itemRate, 2); $th2 = self::thAdjust($row->itemRate, 2.333); $th3 = self::thAdjust($row->itemRate, 2.5); $th4 = self::thAdjust($row->itemRate, 2.666);
							break;
						case 2:
							$th1 = self::thAdjust($row->itemRate, 2); $th2 = self::thAdjust($row->itemRate, 2.666); $th3 = self::thAdjust($row->itemRate, 2.833); $th4 = self::thAdjust($row->itemRate, 3);
							break;
						case 3:
							$th1 = self::thAdjust($row->itemRate, 1.2); $th2 = self::thAdjust($row->itemRate, 1.5); $th3 = self::thAdjust($row->itemRate, 1.65); $th4 = self::thAdjust($row->itemRate, 1.8);
							break;
						case 4:
							$th1 = self::thAdjust($row->itemRate, 1.2); $th2 = self::thAdjust($row->itemRate, 1.4); $th3 = self::thAdjust($row->itemRate, 1.5); $th4 = self::thAdjust($row->itemRate, 1.6);
							break;	
						case 5:
							$th1 = self::thAdjust($row->itemRate, 1.5); $th2 = self::thAdjust($row->itemRate, 2); $th3 = self::thAdjust($row->itemRate, 2.25); $th4 = self::thAdjust($row->itemRate, 2.5);
							break;		
						case 6:
							$th1 = self::thAdjust($row->itemRate, 1.5); $th2 = self::thAdjust($row->itemRate, 2); $th3 = self::thAdjust($row->itemRate, 2.4); $th4 = self::thAdjust($row->itemRate, 2.8);
							break;
						case 7:
							$th1 = self::thAdjust($row->itemRate, 2); $th2 = self::thAdjust($row->itemRate, 3); $th3 = self::thAdjust($row->itemRate, 3.5); $th4 = self::thAdjust($row->itemRate, 4);
							break;
						case 8;
							$th1 = "-"; $th2 = "-"; $th3 = "-"; $th4 = "-";
							break;						
						default:
						break;
					}
					$html .= "<td><center>$th1 %</center></td><td><center>$th2 %</center></td><td><center>$th3 %</center></td><td><center>$th4 %</center></td>";
				}
				$html .= "</tr>";
				
			}

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