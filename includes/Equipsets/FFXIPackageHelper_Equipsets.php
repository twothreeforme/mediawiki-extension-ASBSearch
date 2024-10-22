<?php


class FFXIPackageHelper_Equipsets  {
    public function __construct() {
    }

    
    static function onParserInit( Parser $parser ) {
        $parser->setHook('Equipsets','FFXIPackageHelper_Equipsets::showequipset' );
        return true;
	}


    public function testForm(){
  

        return 
        '
        <div style="display: grid;grid-template-columns: auto 400px;background-color: #878787;">
            <div>
                <table class="FFXIPackageHelper_Equipsets_table">
                    <tr>
                        <th></th>
                        <th>Item</th>
                    </tr>
                    <tr>
                        <td><button class="FFXIPackageHelper_dynamiccontent_addbutton">Add</button></td>
                        <td>heasfdasdgagafad</td>
                    </tr>
                    <tr>
                        <td><button class="FFXIPackageHelper_dynamiccontent_addbutton">Change</button></td>
                        <td>nefsdfsck</td>
                    </tr>
                    <tr>
                        <td><button class="FFXIPackageHelper_dynamiccontent_addbutton">Add</button></td>
                        <td>hansdfs sf sfds</td>
                    </tr>
                </table>
            </div>
            <div class="FFXIPackageHelper_Equipsets_showset">show sets here</div>
        </div>
            
        ';

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
}

?>