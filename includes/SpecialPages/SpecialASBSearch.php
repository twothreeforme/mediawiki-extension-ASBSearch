<?php


class SpecialASBSearch extends SpecialPage {

	private $queryLimit = 1500;

    public function __construct( ) {
        parent::__construct( 'ASBSearch' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  {
		//$out->addModules(['FFXIPackageHelper_ASBSearch']);
		if ( $out->getTitle() == "Special:ASBSearch" ) $out->addModules(['FFXIPackageHelper_TabsController']);
	}

	function execute( $par ) {

		//$this->testing();
		$this->setHeaders();
		$request = $this->getRequest();
		$output = $this->getOutput();
		$output->setPageTitle( $this->msg( 'asbsearch' ) );

		// # Get request data

		/**
		 *	Drop Rates Query Request Data
		 */
		$levelRangeMIN =  (int)$request->getText( 'levelRangeMIN' );
		$levelRangeMAX =  (int)$request->getText( 'levelRangeMAX' );
		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
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

		/**
		 *	Equipsets Request Data
		 */
		// $race = (int)$request->getText( 'race' );
		// $mlvl = (int)$request->getText( 'mlvl' );
		// $slvl = (int)$request->getText( 'slvl' );
		// $mjob = (int)$request->getText( 'mjob' );
		// $sjob = (int)$request->getText( 'sjob' );
		// $equipment = $request->getText( 'equipment' );

		// $equipsetsData = null;
		// if ( strlen($equipment) > 0 ){
		// 	$equipsetsData = [
		// 		$race,
		// 		$mlvl,
		// 		$slvl,
		// 		$mjob,
		// 		$sjob,
		// 		$equipment
		// 	];

		// }


        $tabs = new FFXIPH_ASBSearch_HTMLTabsHelper();
        $tabDropRates = new FFXIPackageHelper_HTMLTabDropRates($queryDataDR);
        $tabRecipes = new FFXIPackageHelper_HTMLTabRecipeSearch();
		$tabEquipment = new FFXIPackageHelper_HTMLTabEquipSearch();
        $tabFishing = new FFXIPackageHelper_HTMLTabFishingSearch();
        $tabAdmin = new FFXIPackageHelper_HTMLTabAdmin();
		$tabMobs = new FFXIPH_HTMLTabMobSearch();

        $html = "<div id=\"initialHide\" style=\"display: none;\">" .
					$tabs->header() . 
					$tabs->tab1($tabDropRates->searchForm()) .
					$tabs->tab2($tabRecipes->searchForm()) .
					$tabs->tab3($tabEquipment->searchForm()) .
					// $tabs->tab4($tabEquipsets->showEquipsets()) .
					$tabs->tab5($tabFishing->searchForm()) .
					$tabs->tab6($tabMobs->searchForm()) .
					$tabs->tab7($tabAdmin->showAdmin()) .
                "</div>";

		$output->addHTML( $html );
	}


}