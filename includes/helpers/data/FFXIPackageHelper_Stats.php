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

    //Advanced Stats
    public $ACC = 0;
    public $EVA = 0;



    public $modifiers = [];
    public $equipment;
    public $skillCaps = [];

    public function __construct($race, $mlvl, $slvl, $mjob, $sjob, $e) {

        // Get skill ranks
        $this->setSkillCaps($mjob, $mlvl, $sjob, $slvl);


        // Pull traits from SQL
        $traits = $this->getTraits( $mlvl, $slvl, $mjob, $sjob );
        $this->applyToModifiers($traits);

        // Pull equipment from SQL
        if ( $e != null){
            $this->equipment = $e;
            //throw new Exception(json_encode($e));
            // if ( gettype($equipmentString) == 'string') $this->applyEquipment(explode(",", $equipmentString));

            $this->applyEquipment();
        }

        //throw new Exception ( json_encode($this->modifiers));

        // Set all base stats in the class
        $this->setBaseStats( $race, $mlvl, $slvl, $mjob, $sjob);

        // Apply modifiers to the base stats
        $this->modifiers["DEF"] += $mlvl + $this->clamp($mlvl - 50, 0, 10);

        // Apply modifiers from equipment
        $this->setStatsWithMods($mlvl);

        // Calc additional stats after all modifiers and equipment have been applied
        // $this->DEF = $this->getDEF();
        // $this->ATT = $this->getATT();
        // $this->ACC = $this->getACC();
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
        [ 0,    0,   0,    0 ],             // 0
        [ 5, 0.50, 0.10, 0.35 ],    // A
        [ 4, 0.45, 0.225, 0.35 ],   // B
        [ 4, 0.40, 0.285, 0.35 ],   // C
        [ 3, 0.35, 0.35, 0.35 ],    // D
        [ 3, 0.30, 0.35, 0.35 ],    // E
        [ 2, 0.25, 0.425, 0.35 ],   // F
        [ 2, 0.20, 0.425, 0.35 ]    // G
    );

    /*
    --     SKILL LEVEL CALCULATOR
    --     Returns a skill level based on level and rating.
    --
    --    See: https://wiki.ffo.jp/html/2570.html
    --
    --    The arguments are skill rank (numerical), and level.  1 is A+, 2 is A-, and so on.

    -- skillLevelTable contains matched pairs based on rank; First value is multiplier, second is additive value.  Index is the subtracted
    -- baseInRange value (see below)
    -- Original formula: ((level - <baseInRange>) * <multiplier>) + <additive>; where level is a range defined in utils.getSkillLvl

    */

    public $SkillLevelTable = array(

    //  --         A+             A-             B+             B              B-             C+             C              C-             D              E              F             G
    1  => [ [ 3.00,   6 ], [ 3.00,   6 ], [ 2.90,   5 ], [ 2.90,   5 ], [ 2.90,   5 ], [ 2.80,   5 ], [ 2.80,   5 ], [ 2.80,   5 ], [ 2.70,   4 ], [ 2.50,   4 ], [ 2.30,   4 ], [ 2.00,   3 ] ], //-- Level <= 50
    50 => [ [ 5.00, 153 ], [ 5.00, 153 ], [ 4.90, 147 ], [ 4.90, 147 ], [ 4.90, 147 ], [ 4.80, 142 ], [ 4.80, 142 ], [ 4.80, 142 ], [ 4.70, 136 ], [ 4.50, 126 ], [ 4.30, 116 ], [ 4.00, 101 ] ], //-- Level > 50 and Level <= 60
    60 => [ [ 4.85, 203 ], [ 4.10, 203 ], [ 3.70, 196 ], [ 3.23, 196 ], [ 2.70, 196 ], [ 2.50, 190 ], [ 2.25, 190 ], [ 2.00, 190 ], [ 1.85, 183 ], [ 1.95, 171 ], [ 2.05, 159 ], [ 2.00, 141 ] ], //-- Level > 60 and Level <= 70
    70 => [ [ 5.00, 251 ], [ 5.00, 244 ], [ 4.60, 233 ], [ 4.40, 228 ], [ 3.40, 223 ], [ 3.00, 215 ], [ 2.60, 212 ], [ 2.00, 210 ], [ 1.85, 201 ], [ 2.00, 190 ], [ 2.00, 179 ], [ 2.00, 161 ] ], //-- Level > 70 and Level <= 75
    75 => [ [ 5.00, 251 ], [ 5.00, 244 ], [ 5.00, 256 ], [ 5.00, 250 ], [ 5.00, 240 ], [ 5.00, 230 ], [ 5.00, 225 ], [ 5.00, 220 ], [ 4.00, 210 ], [ 3.00, 200 ], [ 2.00, 189 ], [ 2.00, 171 ] ], //-- Level > 75 and Level <= 80
    // [80] = [ [ 6.00, 301 ], [ 6.00, 294 ], [ 6.00, 281 ], [ 6.00, 275 ], [ 6.00, 265 ], [ 6.00, 255 ], [ 6.00, 250 ], [ 6.00, 245 ], [ 5.00, 230 ], [ 4.00, 215 ], [ 3.00, 199 ], [ 2.00, 181 ] ], //-- Level > 80 and Level <= 90
    // [90] = [ [ 7.00, 361 ], [ 7.00, 354 ], [ 7.00, 341 ], [ 7.00, 335 ], [ 7.00, 325 ], [ 7.00, 315 ], [ 7.00, 310 ], [ 7.00, 305 ], [ 6.00, 280 ], [ 5.00, 255 ], [ 4.00, 229 ], [ 2.00, 201 ] ], //- Level > 90
    );

    // -- Get the corresponding table entry to use in skillLevelTable based on level range
    private function getSkillLevelIndex($level, $rank){
        $rangeId = null;

        if ( $level <= 50 ) $rangeId = 1;
        elseif ($level <= 60) $rangeId = 50;
        elseif ($level <= 70) $rangeId = 60;
        elseif ($level <= 75 && $rank > 2) $rangeId = 75;
        else $rangeId = 70;

        return $rangeId;
    }

    private function getSkillLvl($rank, $level){
        $rank = $rank - 1;
        $levelTableIndex = $this->getSkillLevelIndex($level, $rank);
        return floor(( ($level - $levelTableIndex) * $this->SkillLevelTable[$levelTableIndex][$rank][0]) + $this->SkillLevelTable[$levelTableIndex][$rank][1]);
    }

    private function setSkillCaps( $mjob, $mlvl, $sjob, $slvl ){
        $db = new DBConnection();
        $results = $db->getSkillRanks($mjob, $sjob);

        foreach( $results as $row ){ // [skillid], [mjob], [sjob]
            $mainSkillCap = $this->getSkillLvl(intval($row->mjob), $mlvl );
            $subSkillCap = $this->getSkillLvl(intval($row->sjob), $slvl );

            if ( $subSkillCap >= $mainSkillCap ){ $this->skillCaps[$row->skillid] = $subSkillCap; }
            else { $this->skillCaps[$row->skillid] = $mainSkillCap; }
        }
    }

    public function getSkillCap( $skillid ){
        if ( isset($this->skillCaps[$skillid]) ) return $this->skillCaps[$skillid];
        else return 0;
    }


// ASB/LSB functions
    function getJobGrade($job, $stat)
    {
        return $this->JobGrades[$job][$stat];
    }

    // shown as RANK in other formulas
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
    private function clamp($current, $min, $max) {
        return max($min, min($max, $current));
    }

    private function setBaseStats( $race, $mlvl, $slvl, $mjob, $sjob){ // ASB/LSB functions
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
                    $this->VIT = $temp;
                    break;
                case 3:
                    $this->AGI = $temp;
                    break;
                case 4:
                    $this->INT = $temp;
                    break;
                case 5:
                    $this->MND = $temp;
                    break;
                case 6:
                    $this->CHR = $temp;
                    break;
            }
            $counter++;
        }
    }



    public function getStats(){

        return [
            // base stats
            $this->HP,      //0
            $this->MP,      //1
            $this->STR,     //2
            $this->DEX,     //3
            $this->VIT,     //4
            $this->AGI,     //5
            $this->INT,     //6
            $this->MND,     //7
            $this->CHR,     //8

            //additional stats
            $this->DEF,     //9
            $this->ATT,     //10

            //resistances
            $this->Fire,    //11
            $this->Wind,    //12
            $this->Lightning,     //13
            $this->Light,   //14
            $this->Ice,   //15
            $this->Earth,   //16
            $this->Water, //17
            $this->Dark,     //18


            //advanced stats
            $this->ACC,       //19
            $this->EVA       //20
        ];
    }

    private function setStatsWithMods(  ){

        $this->HP += $this->modifiers["HP"];

        $this->MP += $this->modifiers["MP"];

        $this->STR += $this->modifiers["STR"];
        $this->DEX += $this->modifiers["DEX"];
        $this->VIT += $this->modifiers["VIT"];
        $this->AGI += $this->modifiers["AGI"];
        $this->INT += $this->modifiers["INT"];
        $this->MND += $this->modifiers["MND"];
        $this->CHR += $this->modifiers["CHR"];



        $this->Fire += $this->modifiers["FIRE_MEVA"];
        $this->Wind += $this->modifiers["WIND_MEVA"];
        $this->Ice += $this->modifiers["ICE_MEVA"];
        $this->Light += $this->modifiers["LIGHT_MEVA"];
        $this->Water += $this->modifiers["WATER_MEVA"];
        $this->Earth += $this->modifiers["EARTH_MEVA"];
        $this->Lightning += $this->modifiers["THUNDER_MEVA"];
        $this->Dark += $this->modifiers["DARK_MEVA"];


        $this->DEF = floor((8 + $this->modifiers["DEF"]) + ($this->VIT / 2));

        $this->ATT += $this->getATT();
        $this->ACC += $this->getACC();
        $this->EVA += $this->getEVA();

    }

    private function applyToModifiers($mods){
        $vars = new FFXIPackageHelper_Variables();

        foreach ($mods as $m => $v) {

            $mod = $vars->modArray[$m];
            if ( !isset($this->modifiers[$mod]) ) $this->modifiers[$mod] = intval($v);
            else $this->modifiers[$mod] += intval($v);

            //if ( $mod == "VIT" )  throw new Exception($this->modifiers[$mod]);
        }
        // throw new Exception(implode(',', array_keys($this->modifiers)) );
    }

    private function getTraits( $mlvl, $slvl, $mjob, $sjob ){
        $db = new DBConnection();
       // $vars = new FFXIPackageHelper_Variables();

        $results = $db->getTraits( $mlvl, $slvl, $mjob, $sjob );

        // organize all traits pulled from DB
        // highest traits.value takes precedence
        $traits = [];
        foreach ( $results as $row ) {
           // $mod = $vars->modArray[$row->modifier];
            if ( !isset($traits[$row->modifier]) ) $traits[$row->modifier] = $row->value;
            else if ( $traits[$row->modifier] < $row->value ) $traits[$row->modifier] = $row->value;
        }

        return $traits;
    }

    function applyEquipment( ){
       // $db = new DBConnection();
        //$vars = new FFXIPackageHelper_Variables();

        for ( $i = 0; $i <= 15; $i++ ){
            if ( $this->equipment[$i][0] != 0 ) {
                //throw new Exception(json_encode($this->equipment[$i])) ;
                foreach( $this->equipment[$i][3] as $mod ){
                    //throw new Exception($mod["id"]) ;
                    $this->applyToModifiers([$mod["id"] => $mod["value"]] );

                }

                //throw new Exception(gettype($this->equipment[$i][0])) ;
            }
        }
        //throw new Exception(json_encode($this->equipment[6]));
    }

    function getDEF(){
        return floor((8 + $this->modifiers["DEF"]) + ($this->VIT / 2));
    }

    function getATT(){ // https://horizonffxi.wiki/Attack
        $ATT = 8 + $this->modifiers["ATT"];
        $ATTP = 0;

        if ( FFXIPackageHelper_Equipment::is2Handed($this->equipment[0]) ) {
            $ATT += $this->STR * 0.7; //Horizon change
        }
        else if ( FFXIPackageHelper_Equipment::isH2H($this->equipment[0]) ){
            $ATT += $this->STR * 0.625; //Horizon change
        }
        else {
            $ATT += $this->STR * 0.65; //Horizon change
        }

        $ATT +=  $this->getSkillCap( intval($this->equipment[0][4]) );
        //if ( intval($this->equipment[0][4]) > 0)
        //throw new Exception($ATT . ":" .  $this->STR . ":" . json_encode($this->equipment[0]) . ":" . intval($this->equipment[0][4]) . ":" . $this->getSkillCap( intval($this->equipment[0][4])) );


        // Smite applies when using 2H or H2H weapons
        if ( ( FFXIPackageHelper_Equipment::is2Handed($this->equipment[0]) || FFXIPackageHelper_Equipment::isH2H($this->equipment[0])) && isset($this->modifiers["SMITE"]) ) {
            $ATTP += $this->modifiers["SMITE"] / 256; // Divide smite value by 256
        }

        //throw new Exception(json_encode($this->equipment));
        return max(1, floor($ATT + ($ATT * $ATTP / 100)));
    }

    function getACC(){
        $ACC = $this->getSkillCap( intval($this->equipment[0][4]) );
        $ACC = ($ACC > 200) ? ((($ACC - 200) * 0.9) + 200) : $ACC;
        if ( FFXIPackageHelper_Equipment::is2Handed($this->equipment[0]) ) {
            $ACC += ($this->DEX * 0.70); //Horizon change
        }
        else if ( FFXIPackageHelper_Equipment::isH2H($this->equipment[0]) ){
            $ACC += $this->DEX * 0.65; //Horizon change
        }
        else{
            $ACC += ($this->DEX * 0.65); //Horizon change
        }
        $ACC += $this->modifiers["ACC"];
        return max(0, floor($ACC));
    }

    function getEVA(){
        //  29 => "EVASION",
        $EVA = $this->getSkillCap(29);
        if ( $EVA > 200) $EVA = 200 + ($EVA - 200) * 0.9;
        $EVA += $this->AGI / 2;
        return max(1, floor($EVA + $this->modifiers["EVASION"]));
    }

}

?>