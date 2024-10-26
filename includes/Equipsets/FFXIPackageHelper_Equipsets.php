<?php


class FFXIPackageHelper_Equipsets  {
    public function __construct() {
    }

    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('Equipsets','FFXIPackageHelper_Equipsets::showequipset' );
        return true;
	}

    public function testForm(){ 
        $tabs = new HTMLTabsHelper();

        $html = "<div id=\"initialHide\">" . 
                $tabs->header() . 
                $tabs->tab1(null) .
                $tabs->tab2(null) .
                $tabs->tab3($this->equipsets()) .
                "</div>";
                
        return $html;

        // <input class="FFXIPackageHelper_dynamiccontent_textinput" type="text" placeholder="Add Head" />

        // <div class="d-grid gap-2">
        //     <button class="btn btn-outline-secondary" type="button">Secondary action</button>
        //     <button class="btn btn-primary" type="button">Primary action</button>
        //  </div>
    }
    
    public static function showequipset( $input, array $params, Parser $parser, PPFrame $frame ) {
        
        $parser->getOutput()->updateCacheExpiry(0);
        //$parser->getOutput()->addModules(['FFXIPackageHelper_Parsley']); 
        $parser->getOutput()->addModules(['FFXIPackageHelper_dynamicContent']); 
        

        $test = new FFXIPackageHelper_Equipsets();
        $html = $test->testForm();
        
        return 	$html;
    }

    public function equipsets(){
        $html = "<div class=\"FFXIPackageHelper_Equipsets_container\" >
            <div>
                <table class=\"FFXIPackageHelper_Equipsets_table\">
                    <tr>
                        <th></th>
                        <th>Slot</th>
                        <th>Item</th>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Main</td>
                        <td>heasfdasdgagafad</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Change</button></td>
                        <td>Sub</td>
                        <td>nefsdfsck</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Range</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Ammo</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Head</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Neck</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Ear1</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Ear2</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Body</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Hands</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Ring1</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Ring2</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                                        <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Back</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Waist</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Legs</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                    <tr>
                        <td><button class=\"FFXIPackageHelper_dynamiccontent_addbutton\">Add</button></td>
                        <td>Feet</td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                </table>
            </div>
            <div class=\"FFXIPackageHelper_Equipsets_showset\">
                <p class=\"STR\"><b>Equipment Stats</b></p>
                <p class=\"STR\">STR</p>
                <p class=\"STR\">DEX</p>
                <p class=\"STR\">VIT</p>
                <p class=\"STR\">AGI</p>
                <p class=\"STR\">INT</p>
                <p class=\"STR\">MND</p>
                <p class=\"STR\">CHR</p>
            </div>
        </div>
        ";

        return $html;
    }
}

?>