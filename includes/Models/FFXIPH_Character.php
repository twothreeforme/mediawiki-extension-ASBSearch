<?php

class FFXIPH_Character  {

    public int $race; 
    public int $mlvl;
    public int $slvl;
    public int $mjob;
    public int $sjob;
    public array $meritStats = array(
        2 => 0, 5 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0
    );
    public array $meritSkills = array(
        80 => 0, 81 => 0, 82 => 0, 83 => 0, 84 => 0, 85 => 0, 86 => 0, 87 => 0, 88 => 0, 89 => 0, 90 => 0, 91 => 0,
        104 => 0, 105 => 0, 106 => 0, 107 => 0, 108 => 0, 109 => 0, 110 => 0,
        111 => 0, 112 => 0, 113 => 0, 114 => 0, 115 => 0, 116 => 0, 117 => 0, 118 => 0, 119 => 0, 120 => 0, 121 => 0
    );
    // = array (
    //         // stats
    //         {2 : 0, 5 : 0, 8 : 0, 9 : 0, 10 : 0, 11 : 0, 12 : 0, 13 : 0, 14 : 0},
    //         // skills
    //         {80 => 0, 81 => 0, 82 => 0, 83 => 0, 84 => 0, 85 => 0, 86 => 0, 87 => 0, 88 => 0, 89 => 0, 90 => 0, 91 => 0,
    //          104 => 0, 105 => 0, 106 => 0, 107 => 0, 108 => 0, 109 => 0, 110 => 0,
    //          111 => 0, 112 => 0, 113 => 0, 114 => 0, 115 => 0, 116 => 0, 117 => 0, 118 => 0, 119 => 0, 120 => 0, 121 => 0},
    //         );
    public string $equipment;

    public int $def;
    public string $charname;
    public string $charid; 

    public bool $isDefault = true;

     /**
     * Character Model
     * @param int $race
     * @param int $mlvl
     * @param int $slvl
     * @param int $mjob
     * @param int $sjob
     * @param string $merits
     * @param string $equipment
     * 
     * @param int $def
     * @param string $charname
     * @param int $charid
     */
    public function __construct( $race = 0, $mlvl = 0, $slvl = 0, $mjob = 0, $sjob = 0, $meritsURLSafe = "", $equipment = "",
                                $def = 0, $charname = "", $charid = 0 ) {
        //wfDebugLog( 'Equipsets', json_encode($charDetails) );
        $this->race = (int)$race;
        $this->mlvl = (int)$mlvl;
        $this->slvl = (int)$slvl;
        $this->mjob = (int)$mjob;
        $this->sjob = (int)$sjob;
        // $this->merits = [];
        $this->equipment = (string)$equipment;

        $this->def = (int)$def;
        $this->charname = (string)$charname;
        $this->charid = (int)$charid;


        //$meritsURLSafe = "W3t9LHsiODYiOiI4In1d";
        if ( $meritsURLSafe != "" ) {
            $meritsdecoded = urldecode($meritsURLSafe);
            $meritsString = base64_decode($meritsdecoded);
            $meritsJSON = json_decode( $meritsString, true);
            $this->setMerits($meritsJSON);
        }



        if ( $this->equipment != "" ) {
            $equipmentdecoded = urldecode($this->equipment);
            $this->equipment = base64_decode($equipmentdecoded);
        }

    }

    public function isDefault(){
        if ( $this->race == 0 &&
            $this->mlvl == 0 && 
            $this->slvl == 0 && 
            $this->mjob == 0 &&
            $this->sjob == 0 && 
            //$this->merits == [] &&
            $this->equipment == "" ) return true; 
        else return false;
    }

    /**
     * Applies saved merits string to Character model
     * and fills in the remaining with 0
     * $this->merits must be an array of 2x objects [(object)stats, (object)skill]
     * each object { merit : value }
     * @param object $meritsString // json_decode of merit string
     */
    private function setMerits($meritsJSON){
        // stats
        foreach ($meritsJSON[0] as $key => $value) {
            //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
            $this->meritStats[$key] = (int)$value;
        }
        // skill
        foreach ($meritsJSON[1] as $key => $value) {
           //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
           $this->meritSkills[$key] = (int)$value;
        }

        //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . json_encode($this->meritSkills) );

    }

    public function toArray(){
        return [
            'race' => $this->race, 
             'mlvl' => $this->mlvl, 
             'slvl' => $this->slvl, 
             'mjob'  => $this->mjob, 
             'sjob'  => $this->sjob, 
             //'merits'  => $this->merits,
             'equipment'  => $this->equipment,

             'def'  => $this->def, 
             'charname'  => $this->charname, 
             'charid'  => $this->charid
        ];
    }



}

?>