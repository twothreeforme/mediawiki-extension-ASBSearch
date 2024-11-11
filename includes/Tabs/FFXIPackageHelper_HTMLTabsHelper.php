<?php


class FFXIPackageHelper_HTMLTabsHelper {
    public function __construct() {
      }

    public function header(){
        return "<div class=\"FFXIPackageHelper_tabs\">" . 
        "<button id=\"FFXIPackageHelper_tabs_droprates\" class=\"tablinks\">Drop Rates</button><button id=\"FFXIPackageHelper_tabs_recipes\" class=\"tablinks\">Recipes</button><button id=\"FFXIPackageHelper_tabs_equipsets\" class=\"tablinks\">Equipsets</button>" .
        "</div>";
        // "
        // <div id=\"FFXIPackageHelper_tabs_recipes_shown\" class=\"tabcontent\">
        //     <h3>Recipes</h3>
        //     <p>Paris is the capital of France.</p>
        // </div>
        // <div id=\"FFXIPackageHelper_tabs_equipsets_shown\" class=\"tabcontent\">
        //     <h3>Equipsets</h3>
        //     <p>Tokyo is the capital of Japan.</p>
        // </div>";
    }

    public function tab1($content){
        if ( $content === null ) $content = "<p>no content yet</p>";
        $html = "<div id=\"FFXIPackageHelper_tabs_droprates_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }
    
    public function tab2($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";
        $html = "<div id=\"FFXIPackageHelper_tabs_recipes_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }

    public function tab3($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_equipsets_shown\" class=\"tabcontent\">" . $content . "</div>";
        return $html; 
    }
}

?>