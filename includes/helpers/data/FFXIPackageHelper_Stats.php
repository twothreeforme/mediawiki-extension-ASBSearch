<?php


class FFXIPackageHelper_Stats {
    
    // Base Stats
    public $HP = 0;
    public $MP = 0;
    public $STR = 0;
    public $DEX = 0;
    public $VIT = 0;
    public $AGI = 0;
    public $INT = 0;
    public $MND = 0;
    public $CHR = 0;

    // Additional Stats
    public $DEF = 0;
    public $ATT = 0;

    //Resistances
    public $Fire = 0;
    public $Wind = 0;
    public $Ice = 0;
    public $Light = 0;
    public $Water = 0;
    public $Earth = 0;
    public $Lightning = 0;
    public $Dark = 0;


    public $modifiers = [];
    public $equipment = [];

    public function __construct($race, $mlvl, $slvl, $mjob, $sjob, $equipmentString) {

        // Pull traits from SQL
        $traits = $this->getTraits( $mlvl, $slvl, $mjob, $sjob );
        $this->applyToModifiers($traits);

        // Pull equipment from SQL
        $this->applyEquipment(explode(",", $equipmentString));

        // Set all base stats in the class
        $this->setBaseStats( $race, $mlvl, $slvl, $mjob, $sjob);

        // Apply modifiers to the base stats
        $this->modifiers["DEF"] += $mlvl + $this->clamp($mlvl - 50, 0, 10);
        $this->applyMods($mlvl);

        // $this->DEF = $this->getDEF();
        // $this->ATT = $this->getATT();
    }

    /**
     * 0 HP
     * 1 MP
     * 2 STR
     * 3 DEX
     * 4 VIT
     * 5 AGI
     * 6 INT
     * 7 MND
     * 8 CHR
    */
// ASB/LSB functions
    public $JobGrades = array(
        // HP,MP,STR,DEX,VIT,AGI,INT,MND,CHR
        [ 0, 0, 0, 0, 0, 0, 0, 0, 0 ], // NON
        [ 2, 0, 1, 3, 4, 3, 6, 6, 5 ], // WAR
        [ 1, 0, 3, 2, 1, 6, 7, 4, 5 ], // MNK
        [ 5, 3, 4, 6, 4, 5, 5, 1, 3 ], // WHM
        [ 6, 2, 6, 3, 6, 3, 1, 5, 4 ], // BLM
        [ 4, 4, 4, 4, 5, 5, 3, 3, 4 ], // RDM
        [ 4, 0, 4, 1, 4, 2, 3, 7, 7 ], // THF
        [ 3, 6, 2, 5, 1, 7, 7, 3, 3 ], // PLD
        [ 3, 6, 1, 3, 3, 4, 3, 7, 7 ], // DRK
        [ 3, 0, 4, 3, 4, 6, 5, 5, 1 ], // BST
        [ 4, 0, 4, 4, 4, 6, 4, 4, 2 ], // BRD
        [ 5, 0, 5, 4, 4, 1, 5, 4, 5 ], // RNG
        [ 2, 0, 3, 3, 3, 4, 5, 5, 4 ], // SAM
        [ 4, 0, 3, 2, 3, 2, 4, 7, 6 ], // NIN
        [ 3, 0, 2, 4, 3, 4, 6, 5, 3 ], // DRG
        [ 7, 1, 6, 5, 6, 4, 2, 2, 2 ], // SMN
        [ 4, 4, 5, 5, 5, 5, 5, 5, 5 ], // BLU
        [ 4, 0, 5, 3, 5, 2, 3, 5, 5 ], // COR
        [ 4, 0, 5, 2, 4, 3, 5, 6, 3 ], // PUP
        [ 4, 0, 4, 3, 5, 2, 6, 6, 2 ], // DNC
        [ 5, 4, 6, 4, 5, 4, 3, 4, 3 ], // SCH
        [ 3, 2, 6, 4, 5, 4, 3, 3, 4 ], // GEO
        [ 3, 6, 3, 4, 5, 2, 4, 4, 6 ]  // RUN
     ) ;


/************************************************************************
 *                                                                        *
 *  Array with the levels of characteristics by race                      *
 *                                                                        *
 ************************************************************************/

    public $RaceGrades = array(
       // HP,MP,STR,DEX,VIT,AGI,INT,MND
        [ 4, 4, 4, 4, 4, 4, 4, 4, 4 ], // Hume
        [ 3, 5, 2, 5, 3, 6, 6, 2, 4 ], // Elvaan
        [ 7, 1, 6, 4, 5, 3, 1, 5, 4 ], // Tarutaru
        [ 4, 4, 5, 1, 5, 2, 4, 5, 6 ], // Mithra
        [ 1, 7, 3, 4, 1, 5, 5, 4, 6 ], // Galka
    );

/************************************************************************
 *                                                                        *
 *  Array with the levels of palyer HP Scale per rank                     *
 *                                                                        *
 ************************************************************************/

    public $HPScale = array(
    // base, <30, <60, <75, >75
        [ 0, 0, 0, 0, 0 ],  // 0
        [ 19, 9, 1, 3, 3 ], // A
        [ 17, 8, 1, 3, 3 ], // B
        [ 16, 7, 1, 3, 3 ], // C
        [ 14, 6, 0, 3, 3 ], // D
        [ 13, 5, 0, 2, 2 ], // E
        [ 11, 4, 0, 2, 2 ], // F
        [ 10, 3, 0, 2, 2 ] // G
    );

/************************************************************************
 *                                                                        *
 *  Array with the levels of MP Scale per rank                            *
 *                                                                        *
 ************************************************************************/

    public $MPScale = array(
        // base, <60, >60
        [ 0, 0, 0 ],    // 0
        [ 16, 6, 4 ],   // A
        [ 14, 5, 4 ],   // B
        [ 12, 4, 4 ],   // C
        [ 10, 3, 4 ],   // D
        [ 8, 2, 3 ],    // E
        [ 6, 1, 2 ],    // F
        [ 4, 0.5, 1 ] // G
    );

/************************************************************************
 *                                                                        *
 *  Array with the levels of base stat scale per rank                     *
 *                                                                        *
 ************************************************************************/

    public $StatScale = array(
      // base, <60, <75, >75
        [ 0, 0, 0, 0 ],             // 0
        [ 5, 0.50, 0.10, 0.35 ],    // A
        [ 4, 0.45, 0.225, 0.35 ],   // B
        [ 4, 0.40, 0.285, 0.35 ],   // C
        [ 3, 0.35, 0.35, 0.35 ],    // D
        [ 3, 0.30, 0.35, 0.35 ],    // E
        [ 2, 0.25, 0.425, 0.35 ],   // F
        [ 2, 0.20, 0.425, 0.35 ]    // G
    );

// ASB/LSB functions
    function getJobGrade($job, $stat)
    {
        return $this->JobGrades[$job][$stat];
    }

    function getRaceGrades($race, $stat)
    {
        return $this->RaceGrades[$race][$stat];
    }

    function getHPScale($rank, $scale)
    {
        return $this->HPScale[$rank][$scale];
    }

    function getMPScale($rank, $scale)
    {
        return $this->MPScale[$rank][$scale];
    }

    function getStatScale($rank, $scale)
    {
        return $this->StatScale[$rank][$scale];
    }


    // https://stackoverflow.com/questions/17664565/does-a-clamp-number-function-exist-in-php
    function clamp($current, $min, $max) {
        return max($min, min($max, $current));
    }

    function setBaseStats( $race, $mlvl, $slvl, $mjob, $sjob){ // ASB/LSB functions
        // $HP = 0; $MP = 0; $STR = 0; $DEX = 0; $VIT = 0; $AGI = 0; $INT = 0; $MND = 0; $CHR = 0; $DEF = 0; $ATTACK = 0;
        // $Fire = 0; $Wind = 0; $Ice = 0; $Light = 0; $Water = 0; $Earth = 0; $Lightning = 0; $Dark = 0;

        $baseValueColumn   = 0; // Column number with base number HP
        $scaleTo60Column   = 1; // Column number with modifier up to 60 levels
        $scaleOver30Column = 2; // Column number with modifier after level 30
        $scaleOver60Column = 3; // Column number with modifier after level 60
        $scaleOver60       = 2; // Column number with modifier for MP calculation after level 60

        /**
         * HP Calculation
         */
        // HP Calculation from Main Job
        $mainLevelOver30     = $this->clamp($mlvl - 30, 0, 30); // Calculation of the condition + 1HP each LVL after level 30
        $mainLevelUpTo60     = ($mlvl < 60 ? $mlvl - 1 : 59);  // The first time spent up to level 60 (is also used for MP)
        $mainLevelOver60To75 = $this->clamp($mlvl - 60, 0, 15); // The second calculation mode after level 60

        // Calculation of the bonus amount of HP
        $mainLevelOver10           = ($mlvl < 10 ? 0 : $mlvl - 10);  // + 2hp at each level after 10
        $mainLevelOver50andUnder60 = $this->clamp($mlvl - 50, 0, 10);  // + 2hp at each level between 50 to 60 level
        $mainLevelOver60           = ($mlvl < 60 ? 0 : $mlvl - 60);

        // HP calculation of an additional profession
        $subLevelOver10 = $this->clamp($slvl - 10, 0, 20); // + 1HP for each level after 10 (/ 2)
        $subLevelOver30 = ($slvl < 30 ? 0 : $slvl - 30);  // + 1HP for each level after 30

         // Calculation of race
        $grade = $this->getRaceGrades($race, 0);

        $raceStat = $this->getHPScale($grade, $baseValueColumn) + ($this->getHPScale($grade, $scaleTo60Column) * $mainLevelUpTo60) +
                   ($this->getHPScale($grade, $scaleOver30Column) * $mainLevelOver30) + ($this->getHPScale($grade, $scaleOver60Column) * $mainLevelOver60To75);

        // Calculation on Main Job
        $grade = $this->getJobGrade($mjob, 0);

        $jobStat = $this->getHPScale($grade, $baseValueColumn) + ($this->getHPScale($grade, $scaleTo60Column) * $mainLevelUpTo60) +
                  ($this->getHPScale($grade, $scaleOver30Column) * $mainLevelOver30) + ($this->getHPScale($grade, $scaleOver60Column) * $mainLevelOver60To75);

        // Calculation of bonus HP.
        $bonusStat = ($mainLevelOver10 + $mainLevelOver50andUnder60) * 2;

        // Calculation on Support Job
        if ($slvl > 0)
        {
            $grade = $this->getJobGrade($sjob, 0);

            $sJobStat = $this->getHPScale($grade, $baseValueColumn) + ($this->getHPScale($grade, $scaleTo60Column) * ($slvl - 1)) +
                       ($this->getHPScale($grade, $scaleOver30Column) * $subLevelOver30) + $subLevelOver30 + $subLevelOver10;
            $sJobStat = $sJobStat / 2;
        }

        $MeritBonus = 0;
        $this->HP = floor(($raceStat + $jobStat + $bonusStat + $sJobStat) + $MeritBonus) ;

        /**
         * MP Calculation
         */
        $raceStat = 0;
        $jobStat  = 0;
        $sJobStat = 0;

        // Calculation of the MP race.
        $grade = $this->getRaceGrades($race, 1);

        // If Main Job has no MP rating, we calculate a racial bonus based on the level of the subjob level (provided that he has a MP rating)
        if ($this->getJobGrade($mjob, 1) == 0)
        {
            if ($this->getJobGrade($sjob, 1) != 0 && $slvl > 0)
            {
                $raceStat = ($this->getMPScale($grade, 0) + $this->getMPScale($grade, $scaleTo60Column) * ($slvl - 1)) / 2;
            }
        }
        else
        {
            // Calculation of a normal racial bonus
            $raceStat = $this->getMPScale($grade, 0) + $this->getMPScale($grade, $scaleTo60Column) * $mainLevelUpTo60 +
                       $this->getMPScale($grade, $scaleOver60) * $mainLevelOver60;
        }

        // Main Job
        $grade = $this->getJobGrade($mjob, 1);
        if ($grade > 0)
        {
            $jobStat = $this->getMPScale($grade, 0) + $this->getMPScale($grade, $scaleTo60Column) * $mainLevelUpTo60 +
                      $this->getMPScale($grade, $scaleOver60) * $mainLevelOver60;
        }

        // Subjob
        if ($slvl > 0)
        {
            $grade    = $this->getJobGrade($sjob, 1);
            $sJobStat = ($this->getMPScale($grade, 0) + $this->getMPScale($grade, $scaleTo60Column) * ($slvl - 1)) / 2;
        }

        $this->MP = floor(($raceStat + $jobStat + $sJobStat) + $MeritBonus);

        /**
         * Base stats
         */
        $counter = 0;
        //$baseStats = [];
        for ( $StatIndex = 2; $StatIndex <= 8; $StatIndex++ )
        {
            // Calculation of race
            $grade    = $this->getRaceGrades($race, $StatIndex);
            $raceStat = floor($this->getStatScale($grade, 0) + $this->getStatScale($grade, $scaleTo60Column) * $mainLevelUpTo60);

            if ($mainLevelOver60 > 0)
            {
                $raceStat += $this->getStatScale($grade, $scaleOver60) * $mainLevelOver60;
            }

            // Calculation by profession
            $grade   = $this->getJobGrade($mjob, $StatIndex);
            $jobStat = floor($this->getStatScale($grade, 0) + $this->getStatScale($grade, $scaleTo60Column) * $mainLevelUpTo60);

            if ($mainLevelOver60 > 0)
            {
                $jobStat += $this->getStatScale($grade, $scaleOver60) * $mainLevelOver60;
            }

            // Calculation for an additional profession
            if ($slvl > 0)
            {
                $grade    = $this->getJobGrade($sjob, $StatIndex);
                $sJobStat = floor(($this->getStatScale($grade, 0) / 2) + $this->getStatScale($grade, $scaleTo60Column) * ($slvl - 1) / 2);
            }
            else
            {
                $sJobStat = 0;
            }

            // Rank A Race + Rank A Job = 71 stat -> Clamp max base stat of 70
            $totalStat = $this->clamp(($raceStat + $jobStat), 0, 70);

            // get each merit bonus stat, str,dex,vit and so on...
            //MeritBonus = PChar->PMeritPoints->GetMeritValue(statMerit[StatIndex - 2], PChar);

            //ref<uint16>(&PChar->stats, counter) = floor(($totalStat + $sJobStat) + $MeritBonus);
            $temp = floor(($totalStat + $sJobStat) + $MeritBonus);

            switch($counter){
                case 0:
                    $this->STR = $temp;
                    break;
                case 1:
                    $this->DEX = $temp;
                    break;
                case 2:
                    $this->VIT = $this->modifiers["VIT"] + $temp;
                    break;
                case 3:
                    $this->AGI = $this->modifiers["AGI"] + $temp;
                    break;
                case 4:
                    $this->INT = $this->modifiers["INT"] + $temp;
                    break;
                case 5:
                    $this->MND = $this->modifiers["MND"] + $temp;
                    break;
                case 6:
                    $this->CHR = $this->modifiers["CHR"] + $temp;
                    break;
            }
            $counter++;
        }
    }



    function getStats(){
        return [
                $this->HP,      //0
                $this->MP,      //1
                $this->STR,     //2
                $this->DEX,     //3
                $this->VIT,     //4
                $this->AGI,     //5
                $this->INT,     //6
                $this->MND,     //7
                $this->CHR,     //8
                $this->DEF,     //9
                $this->ATT,     //10
                $this->Fire,    //11
                $this->Wind,    //12
                $this->Ice,     //13
                $this->Light,   //14
                $this->Water,   //15
                $this->Earth,   //16
                $this->Lightning, //17
                $this->Dark     //18
            ];
    }

    private function applyMods(  ){

        $this->HP += $this->modifiers["HP"];

        $this->MP += $this->modifiers["MP"];

        $this->STR += $this->modifiers["STR"];
        $this->DEX += $this->modifiers["DEX"];
        $this->VIT += $this->modifiers["VIT"];
        $this->AGI += $this->modifiers["AGI"];
        $this->INT += $this->modifiers["INT"];
        $this->MND += $this->modifiers["MND"];
        $this->CHR += $this->modifiers["CHR"];

        $this->DEF = floor((8 + $this->modifiers["DEF"]) + ($this->VIT / 2));

    }

    private function applyToModifiers($mods){
        foreach ($mods as $m => $v) {
            if ( !isset($this->modifiers[$m]) ) $this->modifiers[$m] = $v;
            else $this->modifiers[$m] += $v;
        }
        //throw new Exception(implode(',', array_values($this->modifiers)) );
    }

    function getTraits( $mlvl, $slvl, $mjob, $sjob ){
        $db = new DBConnection();
        $vars = new FFXIPackageHelper_Variables();

        $results = $db->getTraits( $mlvl, $slvl, $mjob, $sjob );

        // organize all traits pulled from DB
        // highest traits.value takes precedence
        $traits = [];
        foreach ( $results as $row ) {
            $mod = $vars->modArray[$row->modifier];
            if ( !isset($traits[$mod]) ) $traits[$mod] = $row->value;
            else if ( $traits[$mod] < $row->value ) $traits[$mod] = $row->value;
        }

        return $traits;
    }

    function applyEquipment( $equipmentArray ){
        $db = new DBConnection();
        for ( $i = 0; $i <= 15; $i++ ){
            if ( $equipmentArray[$i] != 0 ) {
                $results = $db->getItem($equipmentArray[$i]);
                foreach ( $results as $row ) {
                    $this->equipment[] = [ $row->itemId, $row->slot, $row->rslot, $row->modid, $row->modValue ];
                }
            }
        }
        //throw new Exception(json_encode($this->equipment));
    }

    function getDEF(){
        return floor((8 + $this->modifiers["DEF"]) + ($this->VIT / 2));
    }

    function getATT(){
        $ATT = 8 + $this->modifiers["ATT"];
        //2H weapon means the item has
        // item_equipment
        // slot = 1
        // rslot = 0

        // auto* weapon = dynamic_cast<CItemWeapon*>(m_Weapons[slot]);
        // if (weapon && weapon->isTwoHanded())
        // {
        //     ATT += (STR() * 3) / 4;
        // }
        // else
        // {
        //     ATT += STR() / 2;
        // }
        return $ATT;
    }
}

?>