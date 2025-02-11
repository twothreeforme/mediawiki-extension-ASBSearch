<?php


class FFXIPackageHelper_HTMLTabController {
    public function __construct() {
      }

    static function onParserInit( Parser $parser ) {
        $parser->setHook('HTMLTabController','FFXIPackageHelper_HTMLTabController::showTabs' );
        return true;
	}

    public static function showTabs( $input, array $params, Parser $parser, PPFrame $frame ) {
        
        $parser->getOutput()->updateCacheExpiry(0);
        $parser->getOutput()->addModules(['FFXIPackageHelper_TabsController']);

        $tabs = new FFXIPackageHelper_HTMLTabsHelper();
        $tabDropRates = new FFXIPackageHelper_HTMLTabDropRates();
        $tabRecipes = new FFXIPackageHelper_HTMLTabRecipeSearch();
        $tabEquipment = new FFXIPackageHelper_Equipment();

        $html = "<div id=\"initialHide\">" . 
                $tabs->header() . 
                $tabs->tab1($tabDropRates->searchForm()) .
                $tabs->tab2($tabRecipes->searchForm()) .
                $tabs->tab3($tabEquipment->equipment()) .
                "</div>";
                
        return $html;

        // <input class="FFXIPackageHelper_dynamiccontent_textinput" type="text" placeholder="Add Head" />

        // <div class="d-grid gap-2">
        //     <button class="btn btn-outline-secondary" type="button">Secondary action</button>
        //     <button class="btn btn-primary" type="button">Primary action</button>
        //  </div>
    }
}

?>