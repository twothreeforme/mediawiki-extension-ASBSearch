<?php

class FFXIPH_MobUtils  {

    public function __construct(){

    }

    /**
     * 
     */
    public static function calcStatsFromSQL($SQLmob, $useLvl = 0){
        //$isNM     = $SQLmob->mobType & FFXIPackageHelper_Variables::$mobType["NOTORIOUS"];
        // $this->setName($SQLmob->name);
        // $this->setZone($SQLmob->zonename);
        // $this->setMinlvl( $SQLmob->minLevel );
        //$this->setMaxlvl( $SQLmob->maxLevel );

        if ( $useLvl > 0 ){
            $mLvl = $useLvl;
        }
        else {
            $mLvl = $SQLmob->maxLevel;
        }

        $sLvl     = $mLvl; // mobs have 1:1 ratio for jobs
        $mJob     = $SQLmob->mJob;
        $sJob     = $SQLmob->sJob;
        $familyID = $SQLmob->familyID;

        //$zoneType = PMob->loc.zone->GetTypeMask();

        $mJobGrade = 0; // main jobs grade
        $sJobGrade = 0; // subjobs grade

        $maxHP = 0;
        $maxMP = 0;

        if ($SQLmob->HPmodifier == 0){
            $mobHP = 1; // Set mob HP

            $baseMobHP = 0; // Define base mobs hp
            $sjHP      = 0; // Define base subjob hp

            $mJobGrade = FFXIPH_SkillGrades::$JobGrades[$mJob][0]; // main jobs grade
            $sJobGrade = FFXIPH_SkillGrades::$JobGrades[$sJob][0]; // subjobs grade

            $base     = 0; // Column for base hp
            $jobScale = 1; // Column for job scaling
            $scaleX   = 2; // Column for modifier scale

            $BaseHP     = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$base];     // Main job base HP
            $JobScale   = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$jobScale]; // Main job scaling
            $ScaleXHP   = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$scaleX];   // Main job modifier scale
            $sjJobScale = FFXIPH_SkillGrades::$MobHPScale[$sJobGrade][$jobScale]; // Sub job scaling
            $sjScaleXHP = FFXIPH_SkillGrades::$MobHPScale[$sJobGrade][$scaleX];   // Sub job modifier scale

            $RIgrade = min($mLvl, 5); // RI Grade
            $RIbase  = 1;                        // Column for RI base

            $RI = FFXIPH_SkillGrades::$MobRBI[$RIgrade][$RIbase]; // Random Increment addition per grade vs. base

            $mLvlIf    = ($mLvl > 5 ? 1 : 0);
            $mLvlIf30  = ($mLvl > 30 ? 1 : 0);
            $raceScale = 6;
            $mLvlScale = 0;

            if ($mLvl > 0)
            {
                $baseMobHP = $BaseHP + (min($mLvl, 5) - 1) * ($JobScale + $raceScale - 1) + $RI + $mLvlIf * (min($mLvl, 30) - 5) * (2 * ($JobScale + $raceScale) + min($mLvl, 30) - 6) / 2 + $mLvlIf30 * (($mLvl - 30) * (63 + $ScaleXHP) + ($mLvl - 31) * ($JobScale + $raceScale));
            }

            // 50+ = 1 hp sjstats
            if ($mLvl > 49)
            {
                $mLvlScale = floor($mLvl);
            }
            // 40-49 = 3/4 hp sjstats
            else if ($mLvl > 39)
            {
                $mLvlScale = floor($mLvl * 0.75);
            }
            // 31-39 = 1/2 hp sjstats
            else if ($mLvl > 30)
            {
                $mLvlScale = floor($mLvl * 0.50);
            }
            // 25-30 = 1/4 hp sjstats
            else if ($mLvl > 24)
            {
                $mLvlScale = floor($mLvl * 0.25);
            }
            // 1-24 = no hp sjstats
            else
            {
                $mLvlScale = 0;
            }

            $sjHP = ceil(($sjJobScale * (max(($mLvlScale - 1), 0)) + (0.5 + 0.5 * $sjScaleXHP) * (max($mLvlScale - 10, 0)) + max($mLvlScale - 30, 0) + max($mLvlScale - 50, 0) + max($mLvlScale - 70, 0)) / 2);

            // Orcs 5% more hp
            if (($familyID == 189) || ($familyID == 190) || ($familyID == 334) || ($familyID == 407))
            {
                $mobHP = ($baseMobHP + $sjHP) * 1.05;
            }
            // Quadavs 5% less hp
            else if (($familyID == 200) || ($familyID == 201) || ($familyID == 202) || ($familyID == 337) || ($familyID == 397) || ($familyID == 408))
            {
                $mobHP = ($baseMobHP + $sjHP) * .95;
            }
            // Manticore family has 50% more HP
            else if ($familyID == 179)
            {
                $mobHP = ($baseMobHP + $sjHP) * 1.5;
            }
            else
            {
                $mobHP = $baseMobHP + $sjHP;
            }

            // if (PMob->PMaster != nullptr)
            // {
            //     $mobHP *= 0.30f; // Retail captures have all pets at 30% of the mobs family of the same level
            // }

            $maxHP = $mobHP;
        }
        else{
            $maxHP = $SQLmob->HPmodifier;
        }

        $hasMp = false;

        switch ($mJob)
        {
            case FFXIPackageHelper_Variables::$jobArrayByName["PLD"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["WHM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["BLM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["RDM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["DRK"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["BLU"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["SCH"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["SMN"]:
                $hasMp = true;
                break;
            default:
                break;
        }

        switch ($sJob)
        {
            case FFXIPackageHelper_Variables::$jobArrayByName["PLD"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["WHM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["BLM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["RDM"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["DRK"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["BLU"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["SCH"]:
            case FFXIPackageHelper_Variables::$jobArrayByName["SMN"]:
                $hasMp = true;
                break;
            default:
                break;
        }

        // if (PMob->getMobMod(MOBMOD_MP_BASE))
        // {
        //     hasMp = true;
        // }

        if ($hasMp)
        {
            $scale = $SQLmob->MPscale;

            if ($SQLmob->MPmodifier == 0)
            {
                $maxMP = (18.2 * pow($mLvl, 1.1075) * $scale) + 10;
            }
            else
            {
                $maxMP = $SQLmob->MPmodifier;
            }

        }

        // ((CItemWeapon*)PMob->m_Weapons[SLOT_MAIN])->setDamage(GetWeaponDamage(PMob, SLOT_MAIN));
        // ((CItemWeapon*)PMob->m_Weapons[SLOT_RANGED])->setDamage(GetWeaponDamage(PMob, SLOT_RANGED));

        // // reduce weapon delay of MNK
        // if (PMob->GetMJob() == JOB_MNK)
        // {
        //     ((CItemWeapon*)PMob->m_Weapons[SLOT_MAIN])->resetDelay();
        // }

        // // Deprecate MOBMOD_DUAL_WIELD later, replace if check with value from DB
        // if (PMob->getMobMod(MOBMOD_DUAL_WIELD))
        // {
        //     PMob->m_dualWield = true;
        //     // if mob is going to dualWield then need to have sub slot
        //     // assume it is the same damage as the main slot
        //     static_cast<CItemWeapon*>(PMob->m_Weapons[SLOT_SUB])->setDamage(GetWeaponDamage(PMob, SLOT_MAIN));
        // }

        $fSTR = FFXIPH_SkillGrades::baseToRank($SQLmob->STR, $mLvl);
        $fDEX = FFXIPH_SkillGrades::baseToRank($SQLmob->DEX, $mLvl);
        $fVIT = FFXIPH_SkillGrades::baseToRank($SQLmob->VIT, $mLvl);
        $fAGI = FFXIPH_SkillGrades::baseToRank($SQLmob->AGI, $mLvl);
        $fINT = FFXIPH_SkillGrades::baseToRank($SQLmob->INT, $mLvl);
        $fMND = FFXIPH_SkillGrades::baseToRank($SQLmob->MND, $mLvl);
        $fCHR = FFXIPH_SkillGrades::baseToRank($SQLmob->CHR, $mLvl);

        $mSTR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][2], $mLvl );
        $mDEX = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][3], $mLvl );
        $mVIT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][4], $mLvl );
        $mAGI = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][5], $mLvl );
        $mINT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][6], $mLvl );
        $mMND = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][7], $mLvl );
        $mCHR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][8], $mLvl );

        $sSTR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][2], $sLvl );
        $sDEX = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][3], $sLvl );
        $sVIT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][4], $sLvl );
        $sAGI = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][5], $sLvl );
        $sINT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][6], $sLvl );
        $sMND = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][7], $sLvl );
        $sCHR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][8], $sLvl );


        // As per conversation with Jimmayus, all mobs at any level get bonus stats from subjobs.
        // From lvl 45 onwards, 1/2. Before lvl 30, 1/4. In between, the value gets progresively higher, from 1/4 at 30 to 1/2 at 44.
        // Im leaving that range at 1/3, for now.
        if ($mLvl >= 45)
        {
            $sSTR /= 2;
            $sDEX /= 2;
            $sAGI /= 2;
            $sINT /= 2;
            $sMND /= 2;
            $sCHR /= 2;
            $sVIT /= 2;
        }
        else if ($mLvl > 30)
        {
            $sSTR /= 3;
            $sDEX /= 3;
            $sAGI /= 3;
            $sINT /= 3;
            $sMND /= 3;
            $sCHR /= 3;
            $sVIT /= 3;
        }
        else
        {
            $sSTR /= 4;
            $sDEX /= 4;
            $sAGI /= 4;
            $sINT /= 4;
            $sMND /= 4;
            $sCHR /= 4;
            $sVIT /= 4;
        }




        // [stat] = floor[family Stat] + floor[main job Stat] + floor[sub job Stat]
        return [
            "HP" => $maxHP,
            "MP" => $maxMP,
            "Lvl" => $mLvl,
            "STR" => ($fSTR + $mSTR + $sSTR),
            "DEX" => ($fDEX + $mDEX + $sDEX),
            "VIT" => ($fVIT + $mVIT + $sVIT),
            "AGI" => ($fAGI + $mAGI + $sAGI),
            "INT" => ($fINT + $mINT + $sINT),
            "MND" => ($fMND + $mMND + $sMND),
            "CHR" => ($fCHR + $mCHR + $sCHR),
            "DEF" => self::getBaseDefEva( $mLvl, $SQLmob->DEF ),
            "EVA" => self::getBaseDefEva( $mLvl, $SQLmob->EVA ),
            "MEVA" => self::getMagicEvasion( $mLvl, $SQLmob->DEF)
        ];
        // $this->setSTR($fSTR + $mSTR + $sSTR);
        // $this->setDEX($fDEX + $mDEX + $sDEX);
        // $this->setVIT($fVIT + $mVIT + $sVIT);
        // $this->setAGI($fAGI + $mAGI + $sAGI);
        // $this->setINT($fINT + $mINT + $sINT);
        // $this->setMND($fMND + $mMND + $sMND);
        // $this->setCHR($fCHR + $mCHR + $sCHR);
    }

    private static function jobSkillRankToBaseEvaRank($job){
        $evasionSkillRank = self::getSkillRank( 29 /*SKILL_EVASION*/, $job);

        switch ($evasionSkillRank)
        {
            case 1:
            case 2:
                return 1; // A, A+; A- doesnt exist anymore
            case 3:
            case 4:
            case 5:
                return 2; // B+, B, B-
            case 6:
            case 7:
            case 8:
                return 3; // C+, C, C-
            case 9:
                return 4; // D
            case 10:
                return 5; // E
            default:
                return 3;
        }

        return 3; // Give them C rank as a fallback.
    }

    /************************************************************************
     *                                                                       *
     *  Base value for defense and evasion                                   *
     *                                                                       *
     ************************************************************************/

    private static function getBaseDefEva($lvl, $rank)
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

    private static function getMagicEvasion($mLvl, $evaRank){
        return self::getBaseSkill($mLvl, $evaRank);
    }

     // Gest base skill rankings for ACC/ATT/EVA/MEVA
    private static function getBaseSkill($mlvl, $rank){
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

    private static function getMaxSkill($skillID, $jobID, $level){
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