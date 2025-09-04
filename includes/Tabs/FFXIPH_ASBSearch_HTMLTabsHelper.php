<?php


class FFXIPH_ASBSearch_HTMLTabsHelper {
    public function __construct() {
      }

    public function header(){
        return "<div class=\"FFXIPackageHelper_tabs\">" . 
        "<button id=\"FFXIPackageHelper_tabs_droprates\" class=\"tablinks\">Drop Rates</button>" .
        "<button id=\"FFXIPackageHelper_tabs_recipes\" class=\"tablinks\">Recipes</button>" .
        "<button id=\"FFXIPackageHelper_tabs_equipment\" class=\"tablinks\">Equipment</button>" .
        // "<button id=\"FFXIPackageHelper_tabs_equipsets\" class=\"tablinks\">Equipsets</button>" .
        "<button id=\"FFXIPackageHelper_tabs_fishing\" class=\"tablinks\">Fishing</button>" .
        "<button id=\"FFXIPackageHelper_tabs_mobsearch\" class=\"tablinks\">Mob Search</button>" .
        "<button id=\"FFXIPackageHelper_tabs_admin\" class=\"tablinks\">Admin</button>" .
        "</div>";
      }

    public function tab1($content){
        if ( $content === null ) $content = "<p>no content yet</p>";
        $html = "<div id=\"FFXIPackageHelper_tabs_droprates_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }
    
    public function tab2($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_recipes_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }

    public function tab3($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_equipment_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html;
    }

    // public function tab4($content){
    //     if ( !isset($content) ) $content = "<p>no content yet</p>";

    //     //remove when setting up for use on HXI
    //     //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

    //     $html = "<div id=\"FFXIPackageHelper_tabs_equipsets_shown\" class=\"tabcontent\">" . $content . "</div>";
    //     return $html;
    // }

    public function tab5($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_fishing_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }

    public function tab6($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_mobsearch_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html;
    }

    public function tab7($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_admin_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html;
    }
}

?>