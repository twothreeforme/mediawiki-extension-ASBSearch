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

		# Get request data from, e.g.
		//$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		
		$zoneNameSearch = $request->getText( 'zoneNameSearch' );
		$mobNameSearch = $request->getText( 'mobNameSearch' );
		$itemNameSearch = $request->getText( 'itemNameSearch' );
		$this->thRatesCheck = $request->getText( 'thRatesCheck' );
		
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

				$wikitext = self::build_table(self::getRates($zoneNameSearch, $mobNameSearch, $itemNameSearch));
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
		
		//$zoneNamesList = self::getZoneNames();
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
			// 'zoneNameDropDown' => [
			// 	'type' => 'limitselect',
			// 	'name' => 'zoneNameDropDown',
			// 	'label' => 'Zone', // Label of the field
			// 	'class' => 'HTMLSelectField', // Input type
			// 	'options' => $zoneNamesList,
			// 	'default' => "Aht Urhgan Whitegate",
			// ],
			
			'mobNameTextField' => [
				'label' => 'Mob Name*', // Label of the field
				'class' => 'HTMLTextField', // Input type
				'name' => 'mobNameSearch'
			],
			'itemNameTextField' => [
				'label' => 'Item Name*', // Label of the field
				'class' => 'HTMLTextField', // Input type
				'name' => 'itemNameSearch'
			],
			'zoneNameTextField' => [
				'label' => 'Zone Name', // Label of the field
				'class' => 'HTMLTextField', // Input type
				'name' => 'zoneNameSearch'
			],
			'thRatesCheck' => [
				'type' => 'check',
				'label' => 'Show TH Rates',
				'name' => 'thRatesCheck',
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

/////////// HTML FORM TESTING
		
		$output->addWikiTextAsInterface( $wikitext );
	}

	// Callback function
	// OnSubmit Callback, here we do all the logic we want to do…
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

	// function getZoneNames(){

	// 	$dbr = $this->openConnection();
	// 	$zonenames =  $dbr->newSelectQueryBuilder()
	// 		->select( [ 'name' ] )
	// 		->from( 'zone_settings' )
	// 		->fetchResultSet(); 

	// 	$result = [];
	// 	foreach ($zonenames as $row) { $result[$row->name]=$row->name; }
	// 	return $result ;
	// }

	function getRates($zoneNameSearch, $mobNameSearch, $itemNameSearch){
		$zoneNameSearch = self::replaceSpaces($zoneNameSearch);
		$mobNameSearch = self::replaceSpaces($mobNameSearch);
		$itemNameSearch = self::replaceSpaces($itemNameSearch);

		$zoneNameSearch = self::replaceApostrophe($zoneNameSearch);
		$mobNameSearch = self::replaceApostrophe($mobNameSearch);
		$itemNameSearch = self::replaceApostrophe($itemNameSearch);

		// $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		// $dbr = $lb->getConnection( DB_REPLICA );
		$dbr = $this->openConnection();

		return $dbr->newSelectQueryBuilder()
			->select( [ //'mob_droplist.name', 
						'mob_droplist.itemRate', 
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

			//->field( 'mob_groups.name', null )
			// |join on=asb_mob_groups.zoneid=asb_zone_settings.zoneid,asb_mob_droplist.dropid=asb_mob_groups.dropid
			->where( [
				"zone_settings.name LIKE '%$zoneNameSearch%'",
				"mob_groups.name LIKE '%$mobNameSearch%'",
				"item_basic.name LIKE '%$itemNameSearch%'"
			])
			->fetchResultSet(); 
	}

	// 0 SET @ALWAYS =     1000;  -- Always, 100%
	// 1 SET @VCOMMON =  999 > 201;  -- Very common, 24%
	// 2 SET @COMMON =   200 > 125;   -- Common, 15%
	// 3 SET @UNCOMMON = 124 > 75; -- Uncommon, 10%
	// 4 SET @RARE = 74 > 30;      -- Rare, 5%
	// 5 SET @VRARE = 30 > 8;     -- Very rare, 1%
	// 6 SET @SRARE = 7 > 3;      -- Super Rare, 0.5%
	// 7 SET @URARE = 3 > 1;      -- Ultra rare, 0.1%

	function build_table($items)
	{

		$html = "<br>
			<div ><i>Disclosure: All data here is from AirSkyBoat. Any Horizon specific changes made to the table will be marked with the Template:Changes->{{Changes}} tag.</i> </div>
			<div style=\"max-height: 900px; overflow: auto; display: inline-block;\">
			<table id=\"dropstable\">
				<tr><th>Zone Name</th>
				<th>Mob Name <sup>(lvl)</sup></th>
				<th>Item Name</th>
				
				<th>Drop Percentage</th>";
				//<th>Item (sort)Name</th>
		if ( $this->thRatesCheck == 1) $html .= "<th>TH1</th><th>TH2</th><th>TH3</th><th>TH4</th>";
		$html .= "</tr>";
		
		foreach ($items as $row)
		{
			//$zn = self::parseZoneName($row->zoneName);
			$zn = self::replaceUnderscores($row->zoneName);
			$zn = str_replace("[S]", "(S)", $zn );

			/*******************************************************
			 * Removing OOE 
			 */
			// First check zone names
			$skipRow = false;
			foreach( ExclusionsHelper::$zones as $v) { 
				//print_r($zn);
				if ( $zn == $v ) { $skipRow = true; break; } }
			if ( $skipRow == true ) continue;
			/*******************************************************/

			$zn = self::parseZoneName($row->zoneName);
			$mn = self::parseMobName($row->mobName, $row->mobMinLevel, $row->mobMaxLevel);
			$in = self::parseItemName($row->itemName);
			
			$droprate = ($row->itemRate)/10;
			if ( $droprate == 0 ) $droprate = 'Steal';
			else $droprate = "$droprate %";
			//if ( $droprate == 0 ) continue;
			
			//$iSn = self::parseItemName($row->itemSortName);

			$html .= "<tr><td><center>$zn</center></td><td><center>$mn</center></td><td><center>$in</center></td><td><center>$droprate</center></td>";
			if ( $this->thRatesCheck == 1){
				
				$cat = 0; // @ALWAYS =     1000;  -- Always, 100%
				if ( $row->itemRate <= 999 && $row->itemRate >= 201 ) $cat = 1; //@VCOMMON =  999 > 201;  -- Very common, 24%
				elseif ( $row->itemRate < 201 && $row->itemRate >= 125 ) $cat = 2; //@COMMON =   200 > 125;   -- Common, 15%
				elseif ( $row->itemRate < 125 && $row->itemRate >= 75 ) $cat = 3; //@UNCOMMON = 124 > 75; -- Uncommon, 10%
				elseif ( $row->itemRate < 74 && $row->itemRate >= 30 ) $cat = 4; //@RARE = 74 > 30;      -- Rare, 5%
				elseif ( $row->itemRate < 30 && $row->itemRate >= 8 ) $cat = 5; //@VRARE = 30 > 8;     -- Very rare, 1%
				elseif ( $row->itemRate < 8 && $row->itemRate >= 3 ) $cat = 6; //@SRARE = 7 > 3;      -- Super Rare, 0.5%
				elseif ( $row->itemRate < 3 && $row->itemRate >= 1 ) $cat = 7; //@URARE = 3 > 1;      -- Ultra rare, 0.1%
				elseif ( $row->itemRate < 1 ) $cat = 8;

				$th1 = 0; $th2 = 0; $th3 = 0; $th4 = 0;

				switch ($cat) {
					case 0:
						$th1 = 100; $th2 = 100; $th3 = 100; $th4 = 100;
						break;
					case 1:
						$th1 = round(($row->itemRate * 2) / 10, 2); $th2 = round(($row->itemRate * 2.333)/ 10, 2); $th3 = round(($row->itemRate * 2.5)/ 10, 2); $th4 = round(($row->itemRate * 2.666)/ 10, 2);
						break;
					case 2:
						$th1 = round(($row->itemRate * 2) / 10, 2); $th2 = round(($row->itemRate * 2.666)/ 10, 2); $th3 = round(($row->itemRate * 2.833)/ 10, 2); $th4 = round(($row->itemRate * 3)/ 10, 2);
						break;
					case 3:
						$th1 = round(($row->itemRate * 1.2) / 10, 2); $th2 = round(($row->itemRate * 1.5)/ 10, 2); $th3 = round(($row->itemRate * 1.65)/ 10, 2); $th4 = round(($row->itemRate * 1.8)/ 10, 2);
						break;
					case 4:
						$th1 = round(($row->itemRate * 1.2) / 10, 2); $th2 = round(($row->itemRate * 1.4)/ 10, 2); $th3 = round(($row->itemRate * 1.5)/ 10, 2); $th4 = round(($row->itemRate * 1.6)/ 10, 2);
						break;	
					case 5:
						$th1 = round(($row->itemRate * 1.5) / 10, 2); $th2 = round(($row->itemRate * 2)/ 10, 2); $th3 = round(($row->itemRate * 2.25)/ 10, 2); $th4 = round(($row->itemRate * 2.5)/ 10, 2);
						break;		
					case 6:
						$th1 = round(($row->itemRate * 1.5) / 10, 2); $th2 = round(($row->itemRate * 2)/ 10, 2); $th3 = round(($row->itemRate * 2.4)/ 10, 2); $th4 = round(($row->itemRate * 2.8)/ 10, 2);
						break;
					case 7:
						$th1 = round(($row->itemRate * 2) / 10, 2); $th2 = round(($row->itemRate * 3)/ 10, 2); $th3 = round(($row->itemRate * 3.5)/ 10, 2); $th4 = round(($row->itemRate * 4)/ 10, 2);
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

		$html .= '</table></div>';
		return $html;
	}

	function replaceApostrophe($inputStr){
		return str_replace("'", "", $inputStr);
	}

	function replaceSpaces($inputStr){
		return str_replace(" ", "_", $inputStr);
	}

	function replaceUnderscores($inputStr){
		return str_replace("_", " ", $inputStr);
	}

	function parseZoneName($zoneName){
		$zoneName = self::replaceUnderscores($zoneName);
		$zoneName = str_replace("[S]", "(S)", $zoneName);
		$zoneName = str_replace("-", " - ", $zoneName);
		return " [[$zoneName]] ";
	}

	function parseMobName($mobName, $minLvl, $maxLvl){
		$fished = false;
		if ( str_contains($mobName, "_fished") ) {
			$mobName = str_replace("_fished", "", $mobName);
			$fished = true;
		}

		$mobName = self::replaceUnderscores($mobName);
		$mobName = ucwords($mobName);

		$mobName = " [[$mobName]]<sup>($minLvl-$maxLvl)</sup> ";
		if ( $fished == true ) return " " . $mobName . " (fished) ";
		else return $mobName;
	}

	function parseItemName($itemName){
		$itemName = self::replaceUnderscores($itemName);
		$itemName = ucwords($itemName);
		return " [[$itemName]] ";
	}
}