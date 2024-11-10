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
		$showBCNMdrops = $request->getText( 'showBCNMdrops' );
		$excludeNMs = $request->getText( 'excludeNMs' );
       
		// $parser->getOutput()->updateCacheExpiry(0);
        // $parser->getOutput()->addModules(['FFXIPackageHelper_dynamicContent']); 

		$queryDataDR = [ 	$this->query_limit, 
			$mobNameSearch, 
			$itemNameSearch, 		
			$zoneNameDropDown, 
			$showBCNMdrops, 
			$excludeNMs,
			$this->levelRangeMIN, 
			$this->levelRangeMAX, 
			$this->thRatesCheck
		];

        $tabs = new FFXIPackageHelper_HTMLTabsHelper();
        $tabDropRates = new FFXIPackageHelper_HTMLTabDropRates($queryDataDR);
        $tabRecipes = new FFXIPackageHelper_HTMLTabRecipeSearch();
        $tabEquipsets = new FFXIPackageHelper_Equipsets();

        $html = "<div id=\"initialHide\">" . 
                $tabs->header() . 
                $tabs->tab1($tabDropRates->searchForm()) .
                $tabs->tab2($tabRecipes->searchForm()) .
                //$tabs->tab3($tabEquipsets->equipsets()) .
                "</div>";

		$output->addWikiTextAsInterface( $html );

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
}