<?php


class FFXIPackageHelper_LuaSetsHelper {

    public function __construct() {
    }

    public function __getSetsHTML($set){
        $luashitacast = "<h2>Luashitacast</h2><pre>SetNameHere = {\n";
        $ashitacast = "<h2>AshitaCast (LegacyAC)</h2><pre>&lt;set name=\"SetNameHere\"&gt;\n";

        for ($e = 0; $e <= 15; $e++) {

            if ( $set[$e] != 0 ){
            $item = str_replace("'", "\\'", $set[$e]); 

            $luashitacast .= "\t" . $this->LAC_slotName[$e] . "'" . $item . "',\n";
            $ashitacast .= $this->AC_slotName($e, $item) . " \n";
            }
        }

        $luashitacast .= "},</pre>";
        $ashitacast .= "&lt;/set&gt;";

        return $luashitacast . $ashitacast;
    }

    private $LAC_slotName = array(
        0 => "Main = " ,
        1 => "Sub = " ,
        2 => "Range = " ,
        3 => "Ammo = " ,
        4 => "Head = " ,
        5 => "Neck = ",
        6 => "Ear1 = ",
        7 => "Ear2 = ",
        8 => "Body = " ,
        9 => "Hands = " ,
        10 => "Ring1 = ",
        11 => "Ring2 = ",
        12 => "Back = ",
        13 => "Waist = ",
        14 => "Legs = ",
        15 => "Feet = ",
    );


    private function AC_slotName($slot,$item){
    switch($slot){
        case 0: return "\t&lt;main&gt;" . $item . "&lt;/main&gt;";
        case 1: return "\t&lt;sub&gt;" . $item . "&lt;/sub&gt;";
        case 2: return "\t&lt;range&gt;" . $item . "&lt;/range&gt;";
        case 3: return  "\t&lt;ammo&gt;" . $item . "&lt;/main&gt;";
        case 4: return  "\t&lt;head&gt;" . $item . "&lt;/head&gt;";
        case 5: return  "\t&lt;neck&gt;" . $item . "&lt;/neck&gt;";
        case 6: return  "\t&lt;ear1&gt;" . $item . "&lt;/ear1&gt;";
        case 7: return  "\t&lt;ear2&gt;" . $item . "&lt;/ear2&gt;";
        case 8: return  "\t&lt;body&gt;" . $item . "&lt;/body&gt;";
        case 9: return  "\t&lt;hands&gt;" . $item . "&lt;/hands&gt;";
        case 10: return "\t&lt;ring1&gt;" . $item . "&lt;/ring1&gt;";
        case 11: return "\t&lt;ring2&gt;" . $item . "&lt;/ring2&gt;";
        case 12: return "\t&lt;back&gt;" . $item . "&lt;/back&gt;";
        case 13: return "\t&lt;waist&gt;" . $item . "&lt;/waist&gt;";
        case 14: return "\t&lt;legs&gt;" . $item . "&lt;/legs&gt;";
        case 15: return "\t&lt;feet&gt;" . $item . "&lt;/feet&gt;";
    }
    }
    
}

?>
