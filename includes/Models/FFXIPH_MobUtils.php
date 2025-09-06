<?php

class FFXIPH_MobUtils  {

    public function __construct(){

    }


    // private static function jobSkillRankToBaseEvaRank($job){
    //     $evasionSkillRank = self::getSkillRank( 29 /*SKILL_EVASION*/, $job);

    //     switch ($evasionSkillRank)
    //     {
    //         case 1:
    //         case 2:
    //             return 1; // A, A+; A- doesnt exist anymore
    //         case 3:
    //         case 4:
    //         case 5:
    //             return 2; // B+, B, B-
    //         case 6:
    //         case 7:
    //         case 8:
    //             return 3; // C+, C, C-
    //         case 9:
    //             return 4; // D
    //         case 10:
    //             return 5; // E
    //         default:
    //             return 3;
    //     }

    //     return 3; // Give them C rank as a fallback.
    // }

    /************************************************************************
     *                                                                       *
     *  Base value for defense and evasion                                   *
     *                                                                       *
     ************************************************************************/

    public static function getBaseDefEva($lvl, $rank)
    {
        // See: https://w.atwiki.jp/studiogobli/pages/25.html
        // Enemy defense = [f(Lv, racial defense rank) + 8 + [VIT/2] + job characteristics] x racial characteristics
        // Enemy evasion = f(Lv, main job evasion skill rank) + [AGI/2] + job characteristics
        // The funcion f is below

        if ($lvl > 50)
        {
            switch ($rank)
            {
                case 1: // A
                    return floor(153 + ($lvl - 50) * 5.0);
                case 2: // B
                    return floor(147 + ($lvl - 50) * 4.9);
                case 3: // C
                    return floor(142 + ($lvl - 50) * 4.8);
                case 4: // D
                    return floor(136 + ($lvl - 50) * 4.7);
                case 5: // E
                    return floor(126 + ($lvl - 50) * 4.5);
            }
        }
        else
        {
            switch ($rank)
            {
                case 1: // A
                    return floor(6 + ($lvl - 1) * 3.0);
                case 2: // B
                    return floor(5 + ($lvl - 1) * 2.9);
                case 3: // C
                    return floor(5 + ($lvl - 1) * 2.8);
                case 4: // D
                    return floor(4 + ($lvl - 1) * 2.7);
                case 5: // E
                    return floor(4 + ($lvl - 1) * 2.5);
            }
        }

        return 0;
    }

    public static function getMagicEvasion($mLvl, $evaRank){
        return self::getBaseSkill($mLvl, $evaRank);
    }

     // Gest base skill rankings for ACC/ATT/EVA/MEVA
    public static function getBaseSkill($mlvl, $rank){
        switch ($rank)
        {
            case 1:
                return self::getMaxSkill( 6 /* SKILL_GREAT_AXE */ , 1, $mlvl); // A+ Skill (1)
            case 2:
                return self::getMaxSkill( 12 /*SKILL_STAFF */ , 1, $mlvl); // B Skill (2)
            case 3:
                return self::getMaxSkill( 29 /*SKILL_EVASION */, 1, $mlvl); // C Skill (3)
            case 4:
                return self::getMaxSkill( 25 /*SKILL_ARCHERY */, 1, $mlvl); // D Skill (4)
            case 5:
                return self::getMaxSkill( 27 /*SKILL_THROWING */, 2, $mlvl); // E Skill (5)
        }

        return 0;
    }

    public static function getMaxSkill($skillID, $jobID, $level){
        $db = new DatabaseQueryWrapper();
        
        $maxSkillRank = $db->getSkillRank($skillID, $jobID);
        //throw new Exception ( $maxSkillRank );
        
        if ($level > 99){ $level = 99; }

        $maxSkill = $db->getSkillCap($level, $maxSkillRank);
        //wfDebugLog( 'Equipsets', get_called_class() . ": maxSkillRank:" . $maxSkillRank . ", maxSkill:" . $maxSkill . ", skill:" . $skillID . ", jobID:" . $jobID . ", level:" . $level );

        return $maxSkill;
    }

}

?>