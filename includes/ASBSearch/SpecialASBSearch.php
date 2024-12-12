<?php
//namespace MediaWiki\Extension\MyExtension;

use Wikimedia\Rdbms\DatabaseFactory;
//use MediaWikiServices;

class SpecialASBSearch extends SpecialPage {

	private $queryLimit = 1500;

    public function __construct( ) {
        parent::__construct( 'ASBSearch' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  { 
		//$out->addModules(['FFXIPackageHelper_ASBSearch']);
		$out->addModules(['FFXIPackageHelper_dynamicContent']);
	}
	
	function execute( $par ) {

		//$this->testing();
		$this->setHeaders();
		$request = $this->getRequest();
		$output = $this->getOutput();
		$output->setPageTitle( $this->msg( 'asbsearch' ) );

		// # Get request data 
		$levelRangeMIN =  (int)$request->getText( 'levelRangeMIN' );
		//$levelRangeMIN = isset($levelRangeMIN) ? $levelRangeMIN : 0;

		$levelRangeMAX =  (int)$request->getText( 'levelRangeMAX' );
		//$levelRangeMAX = isset($levelRangeMAX) ? $levelRangeMAX : 0;

		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		//$zoneNameDropDown = isset($zoneNameDropDown) ? $zoneNameDropDown : 0;

		$mobNameSearch = $request->getText( 'mobNameSearch' );
		$itemNameSearch = $request->getText( 'itemNameSearch' );
		$thRatesCheck = (int)$request->getText( 'thRatesCheck' );
		$showBCNMdrops = (int)$request->getText( 'showBCNMdrops' );
		$excludeNMs = (int)$request->getText( 'excludeNMs' );
		$includeFished = (int)$request->getText( 'includeFished' );

		$queryDataDR = NULL;
		if ( 	$mobNameSearch == "" &&
				$itemNameSearch== "" &&
				( $zoneNameDropDown == "searchallzones" || $zoneNameDropDown == "") &&
				( ($levelRangeMIN == 0 && $levelRangeMAX == 0) || ( $levelRangeMIN > $levelRangeMAX )) ) {
					//do nothing
		}
		else {
			if ( $zoneNameDropDown != "searchallzones") $zoneNameDropDown = ucfirst($zoneNameDropDown);

			$queryDataDR = [
				$this->queryLimit,
				$mobNameSearch,
				$itemNameSearch,
				$zoneNameDropDown,
				$showBCNMdrops,
				$excludeNMs,
				$levelRangeMIN,
				$levelRangeMAX,
				$thRatesCheck,
				$includeFished
			];
		}
		//print_r($queryDataDR);

        $tabs = new FFXIPackageHelper_HTMLTabsHelper();
        $tabDropRates = new FFXIPackageHelper_HTMLTabDropRates($queryDataDR);
        $tabRecipes = new FFXIPackageHelper_HTMLTabRecipeSearch();
		$tabEquipment = new FFXIPackageHelper_HTMLTabEquipSearch();
        //$tabEquipsets = new FFXIPackageHelper_Equipsets();

        $html = "<div id=\"initialHide\" style=\"display: none;\">" .
                $tabs->header() . 
                $tabs->tab1($tabDropRates->searchForm()) .
                $tabs->tab2($tabRecipes->searchForm()) .
				$tabs->tab3($tabEquipment->searchForm()) .
                //$tabs->tab4($tabEquipment->equipsets()) .
                "</div>";

		$output->addHTML( $html );
	}


}