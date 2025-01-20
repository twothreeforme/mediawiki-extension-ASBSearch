<?php

/**
 *
 * @param {$equipmentString} string, input from GET request, as itemid for each slot 0-15
 */
class FFXIPackageHelper_Equipment {
    private $equipment = [];
    private $incomingEquipmentList = [];

    public function __construct($equipment) {
        //throw new Exception($equipment);
        $equipment = base64_decode($equipment);


        if ( $this->detectDelimiter($equipment) == ',') $equipment = explode( ",", $equipment);
        else if ( $this->detectDelimiter($equipment) == '|' ) $equipment = explode( "|", $equipment);
        else throw new Exception("unknown detectDelimiter: " . json_encode($equipment));



        for ( $i = 0; $i <= 15; $i++ ){

            $temp = explode(',', $equipment[$i]);
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

                $this->equipment[$i] = [
                    intval($model["id"]),
                    intval($model["slot"]),
                    intval($model["rslot"]),
                    $model["mods"],
                    intval($model["skilltype"]),
                    $name
                ];
        }

        //throw new Exception(json_encode($this->equipment));
    }

    private function queryItem($item){
        $dm = new DataModel();
        $db = new DBConnection();

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
        if( isset($weapon['skilltype']) && $weapon['skilltype'] == 1 ) return true;
        return false;
    }

    public static function is2Handed($weapon){
        if( isset($weapon['skilltype']) && (
            $weapon['skilltype'] == 4 || // G. Sword
            $weapon['skilltype'] == 6 || // G. Axe
            $weapon['skilltype'] == 7 || // Scythe
            $weapon['skilltype'] == 8 || // Polearm
            $weapon['skilltype'] == 10 || // G. Katana
            $weapon['skilltype'] == 12 ) // Staff
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