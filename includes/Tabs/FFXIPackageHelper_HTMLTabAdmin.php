<?php

use MediaWiki\MediaWikiServices;

class FFXIPackageHelper_HTMLTabAdmin {
    public function __construct() {
    }

    public function showAdmin(){
        $html = "<div id=\"FFXIPackageHelper_tabs_admin_display\">";

        $adminArray = [ "bureaucrat","interface-admin","sysop","senior-editor"];

        $user = RequestContext::getMain()->getUser();
        $userGroups = MediaWikiServices::getInstance()->getUserGroupManager()->getUserGroups( $user );

        //throw new Exception ( json_encode( $userGroups ) );

        $allowed = array_intersect($adminArray, $userGroups);

        if ( count($allowed) == 0 ) return $html . "This page is restricted to administrators and senior-editors.</div>";

        $db = new DatabaseQueryWrapper();
        $drhits = $db->getHitCounter("droprate");
        $eqhits = $db->getHitCounter("equipment");
        $fihits = $db->getHitCounter("fishing");
        $rehits = $db->getHitCounter("recipes");

         $html = "<div id=\"FFXIPackageHelper_tabs_admin_display\">" .
                    "<h3>ASBSearch Clicks:</h3>
                    <pre>Drop Rates: $drhits\nEquipment: $eqhits\nRecipes: $rehits\nFishing: $fihits</pre>" .
                "</div>";
         return $html;
    }
}

?>