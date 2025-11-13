<?php

/**
 *
 * @param {$equipmentString} string, input from GET request, as itemid for each slot 0-15
 */
class FFXIPackageHelper_Equipment {
    private $equipment = [];
    private $incomingEquipmentList = [];

    public function __construct($equipment) {
        if ($equipment == null || $equipment == '' ) return;
        //wfDebugLog( 'Equipsets', get_called_class() . ":" . gettype($equipment) );
        if ( $this->detectDelimiter($equipment) == ',') $equipment = explode( ",", $equipment);
        else if ( $this->detectDelimiter($equipment) == '|' ) $equipment = explode( "|", $equipment);
        else if ( strlen($equipment) == 0 ) throw new Exception ("equp string len = 0");
        else throw new Exception("unknown detectDelimiter: " . json_encode($equipment));

        for ( $i = 0; $i <= 15; $i++ ){

            $temp = explode(',', $equipment[$i]);

            //throw new Exception ( json_encode($temp));

            $incItemID = intval($temp[0]);
            $incItemChangeFlag = intval($temp[1]);

            $model = null;
            //if ( $incItemID != 0 ) {
                $model = $this->queryItem( $incItemID );
                $name = ($model["name"] == null) ? "" : $model["name"];

                $this->incomingEquipmentList[$i] = [
                    $incItemID,
                    "",
                    $incItemChangeFlag,
                    $name
                ];
                
                //if ( $model != null )  throw new Exception ( json_encode($model));
                if ( $incItemID == 0 ) {
                    $this->equipment[$i] = [0,0,0,array(),0,""];
                }
                else {
                    $this->equipment[$i] = [
                        intval($model["id"]),
                        intval($model["slot"]),
                        intval($model["rslot"]),
                        $model["mods"],
                        intval($model["skilltype"]),
                        $name
                    ];

                    // $this->equipment[$i] = [
                    //     "id" => intval($model["id"]),
                    //     "slot" => intval($model["slot"]),
                    //     "rslot" => intval($model["rslot"]),
                    //     "mods" => $model["mods"],
                    //     "skilltype" => intval($model["skilltype"]),
                    //     "name" => $name
                    // ];
                }
        }

        //throw new Exception(json_encode($this->equipment));
    }

    private function queryItem($item){
        $dm = new DataModel();
        $db = new DatabaseQueryWrapper();

        $results = $db->getItem($item);
        return $dm->parseEquipment($results)[0];
    }

    public function getEquipmentArray(){
        return $this->equipment;
    }

    public function getIncomingEquipmentList(){
        return $this->incomingEquipmentList;
    }

    public static function isH2H($weapon){
        //if( isset($weapon['skilltype']) && $weapon['skilltype'] == 1 ) return true;
        if( isset($weapon[4]) && $weapon[4] == 1 ) return true;

        return false;
    }

    public static function is2Handed($weapon){
        // if( isset($weapon['skilltype']) && (
        //     $weapon['skilltype'] == 4 || // G. Sword
        //     $weapon['skilltype'] == 6 || // G. Axe
        //     $weapon['skilltype'] == 7 || // Scythe
        //     $weapon['skilltype'] == 8 || // Polearm
        //     $weapon['skilltype'] == 10 || // G. Katana
        //     $weapon['skilltype'] == 12 ) // Staff
        //     )return true;

        if( isset($weapon[4]) && (
            $weapon[4] == 4 || // G. Sword
            $weapon[4] == 6 || // G. Axe
            $weapon[4] == 7 || // Scythe
            $weapon[4] == 8 || // Polearm
            $weapon[4] == 10 || // G. Katana
            $weapon[4] == 12 ) // Staff
            )return true;

            return false;
    }

    protected function detectDelimiter($line) {
        $test=explode('|', $line);
        if (count($test)>1) return '|';

        $test=explode(',', $line);
        if (count($test)>1) return ',';

        $url=rawurldecode($line);
        $test=explode('|', $url);
        if (count($test)>1) return '|';

        return null;
    }
} 
?>