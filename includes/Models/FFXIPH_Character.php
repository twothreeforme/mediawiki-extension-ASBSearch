<?php

class FFXIPH_Character  {

    public int $race; 
    public int $mlvl;
    public int $slvl;
    public int $mjob;
    public int $sjob;
    // public array $meritStats = array(
    //     2 => 0, 5 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0
    // );
    // public array $meritSkills = array(
    //     80 => 0, 81 => 0, 82 => 0, 83 => 0, 84 => 0, 85 => 0, 86 => 0, 87 => 0, 88 => 0, 89 => 0, 90 => 0, 91 => 0,
    //     104 => 0, 105 => 0, 106 => 0, 107 => 0, 108 => 0, 109 => 0, 110 => 0,
    //     111 => 0, 112 => 0, 113 => 0, 114 => 0, 115 => 0, 116 => 0, 117 => 0, 118 => 0, 119 => 0, 120 => 0, 121 => 0
    // );

    public array $merits = [];

    public string $equipment;

    public int $def;
    public string $charname;
    public string $charid; 

    //public bool $isDefault = true;
    public string $raceString = "";
    public int $userid = 0;

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

        // /*test*/ $meritsURLSafe = "W3t9LHsiODYiOiI4In1d";
        if ( $meritsURLSafe != "" && !is_null($meritsURLSafe) ) {
            $this->setMerits($meritsURLSafe);
        }

        if ( $this->equipment != "" ) {
            $equipmentdecoded = urldecode($this->equipment);
            $this->equipment = base64_decode($equipmentdecoded);
        }

        $racemodel = new FFXIPH_Races();
        $this->raceString = $racemodel->toString[$this->race];

        $this->userid = RequestContext::getMain()->getUser()->getId();
    }

    /**
     * Bool identifying if the character is a blank model
     * @return bool true: blank model/ false: user character
     */
    public function isDefault(){
        if ( $this->race == 0 &&
            $this->mlvl == 0 && 
            $this->slvl == 0 && 
            $this->mjob == 0 &&
            $this->sjob == 0  
            // && $this->merits == [] &&
            //$this->equipment == "" 
            ) return true; 
        else {
            //throw new Exception ( json_encode( [$this->race,$this->mlvl,$this->slvl,$this->mjob,$this->sjob  ] ));
            return false;
        }
    }

    public function canGenerateStats(){
        if ( 
            //$this->race == 0 ||
            $this->mlvl == 0 || 
            $this->slvl == 0 || 
            $this->mjob == 0 ||
            $this->sjob == 0  
            // && $this->merits == [] &&
            //$this->equipment == "" 
            ) return false; 
        else {
            //throw new Exception ( json_encode( [$this->race,$this->mlvl,$this->slvl,$this->mjob,$this->sjob  ] ));
            return true;
        }
    }

    /**
     * Applies saved merits string to Character model
     * and fills in the remaining with 0
     * $this->merits must be an array of 2x objects [(object)stats, (object)skill]
     * each object { merit : value }
     * @param string $meritsURLSafe // encoded merit string
     */
    public function setMerits($meritsString){
        if ( is_null($meritsString) | $meritsString == "" ) return;

        $meritsdecoded = urldecode($meritsString);
        $meritsbase64decoded = base64_decode($meritsdecoded);
        $meritsJSON = json_decode( $meritsbase64decoded, false);

        //wfDebugLog( 'Equipsets', get_called_class() . ":setMerits: " . gettype($meritsJSON) . " " . json_encode($meritsJSON) );

        if ( is_null($meritsJSON) ) return;

        //This IF accounts for the old version of saved merit objects in the DB
        if ( is_array($meritsJSON) && ( is_object($meritsJSON[0]) || is_object($meritsJSON[1]) ) ){
            // stats
            foreach ($meritsJSON[0] as $key => $value) {
                //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
                //$this->meritStats[$key] = (int)$value;
                $this->merits[$key] = (int)$value;
            }
            //skills
            foreach ($meritsJSON[1] as $key => $value) {
                //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
                //$this->meritSkills[$key] = (int)$value;
                $this->merits[$key] = (int)$value;
            }
        }
        else {
            foreach ($meritsJSON as $key => $value) {
                //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
                //$this->meritSkills[$key] = (int)$value;
                $this->merits[$key] = (int)$value;
            }
        }

        //wfDebugLog( 'Equipsets', get_called_class() . ":setMerits: " . json_encode($this->merits) );

       
    }

    public function setMerit($m, $value){
        if ( is_null($this->getMerit($m)) ){
            //$this->meritSkills[$key] = (int)$value;
            $this->merits[$key] = (int)$value;
        }
    }

    public function getMeritsURLSafe(){
        // $stats = [];
        // // stats
        // foreach ($this->meritStats as $key => $value) {
        //    // wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
        //     if ( $value != 0 ) $stats[$key] = (int)$value;
        // }

        // $skills = [];
        // // skill
        // foreach ($this->meritSkills as $key => $value) {
        //    //wfDebugLog( 'Equipsets', "FFXIPH_Character:setMerits " . $key . ":" . $value );
        //    if ( $value != 0 ) $skills[$key] = (int)$value;
        // }
       
        //$meritsString = json_encode( [$stats, $skills] );
        $meritsString = json_encode( $this->merits );

        
        //$meritsString = json_encode( self::getMerits() );
        $meritsString = base64_encode($meritsString);
        return urlencode($meritsString);
    }

    public function getMerit($m){
        // if (array_key_exists($m, $this->meritStats)) {
        //     return $this->meritStats[$m];
        // }
        // if (array_key_exists($m, $this->meritSkills)) {
        //     return $this->meritSkills[$m];
        // }

        if (array_key_exists($m, $this->merits)) {
            return $this->merits[$m];
        }
        return 0;
    }

    public function getMerits(){
        //return [$this->meritStats, $this->meritSkills];
        return $this->merits;
    }

    public function toArray(){
        return [
            'race' => $this->race, 
             'mlvl' => $this->mlvl, 
             'slvl' => $this->slvl, 
             'mjob'  => $this->mjob, 
             'sjob'  => $this->sjob, 
             //'merits'  => $this->merits,
             'merits' => $this->merits, //[ $this->meritStats, $this->meritSkills],
             'equipment'  => $this->equipment,
             'def'  => $this->def, 
             'charname'  => $this->charname, 
             'charid'  => $this->charid,
             'isDefault' => self::isDefault(),
             'canGenerateStats' => self::canGenerateStats()
        ];
    }

    public function toURLsafeArray(){
        return [
            'race' => $this->race, 
             'mlvl' => $this->mlvl, 
             'slvl' => $this->slvl, 
             'mjob'  => $this->mjob, 
             'sjob'  => $this->sjob, 
             //'merits'  => $this->merits,
             'merits' => $this->getMeritsURLSafe(),
             'equipment'  => $this->equipment,
             'def'  => $this->def, 
             'charname'  => $this->charname, 
             'charid'  => $this->charid,
             'isDefault' => self::isDefault(),
             'canGenerateStats' => self::canGenerateStats()
        ];
    }

    public function hasMeritsSet(){
        // // stats
        // foreach ($this->meritStats as $value) {
        //     if ($value != 0) return true;
        // }
        // // skill
        // foreach ($this->meritSkills as $value) {
        //     if ($value != 0) return true;
        // }
        if ( count($this->merits) > 0 ) return true;
        return false;
    }

}

?>