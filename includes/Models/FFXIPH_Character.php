<?php

class FFXIPH_Character  {

    public int $race; 
    public int $mlvl;
    public int $slvl;
    public int $mjob;
    public int $sjob;
    public string $merits;
    public string $equipment;

    public int $def;
    public string $charname;
    public string $charid; 

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
    public function __construct( $race = 0, $mlvl = 0, $slvl = 0, $mjob = 0, $sjob = 0, $merits = "", $equipment = "",
                                $def = 0, $charname = "", $charid = 0 ) {
        //wfDebugLog( 'Equipsets', json_encode($charDetails) );
            $this->race = (int)$race;
            $this->mlvl = (int)$mlvl;
            $this->slvl = (int)$slvl;
            $this->mjob = (int)$mjob;
            $this->sjob = (int)$sjob;
            $this->merits = (string)$merits;
            $this->equipment = (string)$equipment;
        
            $this->def = (int)$def;
            $this->charname = (string)$charname;
            $this->charid = (int)$charid;
        //wfDebugLog( 'Equipsets', "FFXIPH_Character: " . $this->race );
    }

    public function isDefault(){
        if ( $this->race == 0 &&
            $this->mlvl == 0 && 
            $this->slvl == 0 && 
            $this->mjob == 0 &&
            $this->sjob == 0 && 
            $this->merits == "" &&
            $this->equipment == "" ) return true; 
        else return false;
    }

    public function toArray(){
        return [
            'race' => $this->race, 
             'mlvl' => $this->mlvl, 
             'slvl' => $this->slvl, 
             'mjob'  => $this->mjob, 
             'sjob'  => $this->sjob, 
             'merits'  => $this->merits, 
             'equipment'  => $this->equipment,

             'def'  => $this->def, 
             'charname'  => $this->charname, 
             'charid'  => $this->charid
        ];
    }
     

}

?>