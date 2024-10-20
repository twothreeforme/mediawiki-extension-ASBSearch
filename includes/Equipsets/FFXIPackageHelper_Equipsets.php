<?php


class FFXIPackageHelper_Equipsets  {
    public function __construct() {
    }

    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('Equipsets','FFXIPackageHelper_Equipsets::showequipset' );
        return true;
	}
    
    public static function showequipset( $input, array $params, Parser $parser, PPFrame $frame ) {
        $parser->getOutput()->updateCacheExpiry(0);
        $parser->getOutput()->addModules(['FFXIPackageHelper_Equipsets']);

        $html = "<div class=\"FFXIPackageHelper_Equipsets\">TEST GOOD</div>";
        return 	$html;
    }
}

?>