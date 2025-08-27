<?php

class FFXIPH_Mob  {

    public string $name; 
    public int $HP; 
    public int $MP;
    public int $minlvl;
    public int $maxlvl;
    public int $mjob;
    public int $sjob;
    public int $mbSkill;
    public int $cmbDmgMult;
    public int $cmbDelay; 
    public int $cmbSkill;   
    public int $links;
    public int $mobType;
    public int $immunity;

    public int $STR;
    public int $DEX;
    public int $VIT;
    public int $AGI;
    public int $INT;
    public int $MND;
    public int $CHR;
    public int $EVA;
    public int $DEF;
    public int $ATT;
    public int $ACC;


    public int $slash_sdt;
    public int $pierce_sdt;
    public int $h2h_sdt;
    //public int $impact_sdt;

    public int $magical_sdt;
    public int $fire_sdt;
    public int $ice_sdt;
    public int $wind_sdt;
    public int $earth_sdt;
    public int $lightning_sdt;
    public int $water_sdt;
    public int $light_sdt;
    public int $dark_sdt;
    
    public int $fire_res_rank;
    public int $ice_res_rank;
    public int $wind_res_rank;
    public int $earth_res_rank;
    public int $lightning_res_rank;
    public int $water_res_rank;
    public int $light_res_rank;
    public int $dark_res_rank;

    public int $element;

    public function __construct(){

    }

    public function getName(){ return $this->name; }
    public function getHP(){ return $this->HP; }
    public function getMP(){ return $this->MP; }
    public function getMinlvl(){ return $this->minlvl; }
    public function getMaxlvl(){ return $this->maxlvl; }
    public function getMjob(){ return $this->mjob; }
    public function getSjob(){ return $this->sjob; }
    public function getMbSkill(){ return $this->mbSkill; }
    public function getCmbSkill(){ return $this->cmbSkill; }
    public function getCmbDelay(){ return $this->cmbDelay; }
    public function getCmbDmgMult(){ return $this->cmbDmgMult; }
    public function getLinks(){ return $this->links; }
    public function getMobType(){ return $this->mobType; }
    public function getImmunity(){ return $this->immunity; }

    public function getSTR(){ return $this->STR; }
    public function getDEX(){ return $this->DEX; }
    public function getVIT(){ return $this->VIT; }
    public function getAGI(){ return $this->AGI; }
    public function getINT(){ return $this->INT; }
    public function getMND(){ return $this->MND; }
    public function getCHR(){ return $this->CHR; }
    public function getEVA(){ return $this->EVA; }
    public function getDEF(){ return $this->DEF; }
    public function getATT(){ return $this->ATT; }
    public function getACC(){ return $this->ACC; }


    public function setName($name){ $this->name = $name; }
    public function setHP($HP){ $this->HP = $HP; }
    public function setMP($MP){ $this->MP = $MP; }
    public function setMinlvl($minlvl){ $this->minlvl = $minlvl; }
    public function setMaxlvl($maxlvl){ $this->maxlvl = $maxlvl; }
    public function setMjob($mjob){ $this->mjob = $mjob; }
    public function setSjob($sjob){ $this->sjob = $sjob; }
    public function setMbSkill($mbSkill){ $this->mbSkill = $mbSkill; }
    public function setCmbSkill($cmbSkill){ $this->cmbSkill = $cmbSkill; }
    public function setCmbDelay($cmbDelay){ $this->cmbDelay = $cmbDelay; }
    public function setCmbDmgMult($cmbDmgMult){ $this->cmbDmgMult = $cmbDmgMult; }
    public function setLinks($links){ $this->links = $links; }
    public function setMobType($mobType){ $this->mobType = $mobType; }
    public function setImmunity($immunity){ $this->immunity = $immunity; }

    public function setSTR($STR){  $this->STR = $STR; }
    public function setDEX($DEX){  $this->DEX = $DEX; }
    public function setVIT($VIT){  $this->VIT = $VIT; }
    public function setAGI($AGI){  $this->AGI = $AGI; }
    public function setINT($INT){  $this->INT = $INT; }
    public function setMND($MND){  $this->MND = $MND; }
    public function setCHR($CHR){  $this->CHR = $CHR; }
    public function setEVA($EVA){  $this->EVA = $EVA; }
    public function setDEF($DEF){  $this->DEF = $DEF; }
    public function setATT($ATT){  $this->ATT = $ATT; }
    public function setACC($ACC){  $this->ACC = $ACC; }

    public function calcStatsFromSQL($SQLmob){
       //$isNM     = $SQLmob->mobType & FFXIPackageHelper_Variables::$mobType["NOTORIOUS"];
        $mJob     = $SQLmob->mJob;
        $sJob     = $SQLmob->sJob;
        $mLvl     = $SQLmob->maxLevel;
        $sLvl     = $SQLmob->maxLevel;
        $familyID = $SQLmob->familyID;

        //$zoneType = PMob->loc.zone->GetTypeMask();

        $mJobGrade = 0; // main jobs grade
        $sJobGrade = 0; // subjobs grade

        if ($SQLmob->HPmodifier == 0){
            $mobHP = 1; // Set mob HP

            $baseMobHP = 0; // Define base mobs hp
            $sjHP      = 0; // Define base subjob hp

            $mJobGrade = FFXIPH_SkillGrades::$JobGrades[$mJob][0]; // main jobs grade
            $sJobGrade = FFXIPH_SkillGrades::$JobGrades[$sJob][0]; // subjobs grade

            $base     = 0; // Column for base hp
            $jobScale = 1; // Column for job scaling
            $scaleX   = 2; // Column for modifier scale

            $BaseHP     = FFXIPH_SkillGrades::$HPScale[$mJobGrade][$base];     // Main job base HP
            $JobScale   = FFXIPH_SkillGrades::$HPScale[$mJobGrade][$jobScale]; // Main job scaling
            $ScaleXHP   = FFXIPH_SkillGrades::$HPScale[$mJobGrade][$scaleX];   // Main job modifier scale
            $sjJobScale = FFXIPH_SkillGrades::$HPScale[$sJobGrade][$jobScale]; // Sub job scaling
            $sjScaleXHP = FFXIPH_SkillGrades::$HPScale[$sJobGrade][$scaleX];   // Sub job modifier scale

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

            $this->HP = $mobHP;
        }
        else{
            $this->HP = $SQLmob->HPmodifier;
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
                $this->MP = (18.2 * pow($mLvl, 1.1075) * $scale) + 10;
            }
            else
            {
                $this->MP = $SQLmob->MPmodifier;
            }

            // if ($isNM)
            // {
            //     $this->MP = (int32)($this->MP * settings::get<float>("map.NM_MP_MULTIPLIER"));
            // }
            // else
            // {
            //     $this->MP = (int32)($this->MP * settings::get<float>("map.MOB_MP_MULTIPLIER"));
            // }
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

        // $fSTR = FFXIPH_SkillGrades::baseToRank($this->STR, $mLvl);
        // $fDEX = FFXIPH_SkillGrades::baseToRank($this->DEX, $mLvl);
        // $fVIT = FFXIPH_SkillGrades::baseToRank($this->VIT, $mLvl);
        // $fAGI = FFXIPH_SkillGrades::baseToRank($this->AGI, $mLvl);
        // $fINT = FFXIPH_SkillGrades::baseToRank($this->INT, $mLvl);
        // $fMND = FFXIPH_SkillGrades::baseToRank($this->MND, $mLvl);
        // $fCHR = FFXIPH_SkillGrades::baseToRank($this->CHR, $mLvl);

        // $mSTR = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 2), mLvl);
        // $mDEX = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 3), mLvl);
        // $mVIT = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 4), mLvl);
        // $mAGI = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 5), mLvl);
        // $mINT = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 6), mLvl);
        // $mMND = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 7), mLvl);
        // $mCHR = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetMJob(), 8), mLvl);

        // $sSTR = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 2), sLvl);
        // $sDEX = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 3), sLvl);
        // $sVIT = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 4), sLvl);
        // $sAGI = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 5), sLvl);
        // $sINT = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 6), sLvl);
        // $sMND = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 7), sLvl);
        // $sCHR = FFXIPH_SkillGrades::baseToRank(grade::GetJobGrade(PMob->GetSJob(), 8), sLvl);
    }

}

?>