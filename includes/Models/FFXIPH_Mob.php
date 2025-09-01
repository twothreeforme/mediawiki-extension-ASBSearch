<?php

class FFXIPH_Mob  {

    public string $zone; 
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

    public function getZone(){ return $this->zone ?? ""; }
    public function getName(){ return $this->name ?? ""; }
    public function getHP(){ return $this->HP ?? 0; }
    public function getMP(){ return $this->MP ?? 0; }
    //public function getMinlvl(){ return $this->minlvl ?? 0; }
    public function getMaxlvl(){ return $this->maxlvl ?? 0; }
    public function getMjob(){ return $this->mjob ?? 0; }
    public function getSjob(){ return $this->sjob ?? 0; }
    public function getMbSkill(){ return $this->mbSkill ?? 0; }
    public function getCmbSkill(){ return $this->cmbSkill ?? 0; }
    public function getCmbDelay(){ return $this->cmbDelay ?? 0; }
    public function getCmbDmgMult(){ return $this->cmbDmgMult ?? 0; }
    public function getLinks(){ return $this->links ?? 0; }
    public function getMobType(){ return $this->mobType ?? 0; }
    public function getImmunity(){ return $this->immunity ?? 0; }

    public function getSTR(){ return $this->STR ?? 0; }
    public function getDEX(){ return $this->DEX ?? 0; }
    public function getVIT(){ return $this->VIT ?? 0; }
    public function getAGI(){ return $this->AGI ?? 0; }
    public function getINT(){ return $this->INT ?? 0; }
    public function getMND(){ return $this->MND ?? 0; }
    public function getCHR(){ return $this->CHR ?? 0; }
    public function getEVA(){ return $this->EVA ?? 0; }
    public function getDEF(){ return $this->DEF ?? 0; }
    public function getATT(){ return $this->ATT ?? 0; }
    public function getACC(){ return $this->ACC ?? 0; }


    public function setZone($zone){ $this->zone = $zone; }
    public function setName($name){ $this->name = $name; }
    public function setHP($HP){ $this->HP = $HP; }
    public function setMP($MP){ $this->MP = $MP; }
    // public function setMinlvl($minlvl){ $this->minlvl = $minlvl; }
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

    // public function calcStatsFromSQL($SQLmob){
    //    //$isNM     = $SQLmob->mobType & FFXIPackageHelper_Variables::$mobType["NOTORIOUS"];
    //     $this->setName($SQLmob->name);
    //     $this->setZone($SQLmob->zonename);
    //     $this->setMinlvl( $SQLmob->minLevel );
    //     //$this->setMaxlvl( $SQLmob->maxLevel );
    //     //$mLvl     = $SQLmob->maxLevel;
        
    //     $this->setMaxlvl( 36 );
    //     $mLvl     = 36;

    //     $sLvl     = $mLvl; //$SQLmob->maxLevel;
    //     $mJob     = $SQLmob->mJob;
    //     $sJob     = $SQLmob->sJob;
    //     $familyID = $SQLmob->familyID;

    //     //$zoneType = PMob->loc.zone->GetTypeMask();

    //     $mJobGrade = 0; // main jobs grade
    //     $sJobGrade = 0; // subjobs grade

    //     if ($SQLmob->HPmodifier == 0){
    //         $mobHP = 1; // Set mob HP

    //         $baseMobHP = 0; // Define base mobs hp
    //         $sjHP      = 0; // Define base subjob hp

    //         $mJobGrade = FFXIPH_SkillGrades::$JobGrades[$mJob][0]; // main jobs grade
    //         $sJobGrade = FFXIPH_SkillGrades::$JobGrades[$sJob][0]; // subjobs grade

    //         $base     = 0; // Column for base hp
    //         $jobScale = 1; // Column for job scaling
    //         $scaleX   = 2; // Column for modifier scale

    //         $BaseHP     = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$base];     // Main job base HP
    //         $JobScale   = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$jobScale]; // Main job scaling
    //         $ScaleXHP   = FFXIPH_SkillGrades::$MobHPScale[$mJobGrade][$scaleX];   // Main job modifier scale
    //         $sjJobScale = FFXIPH_SkillGrades::$MobHPScale[$sJobGrade][$jobScale]; // Sub job scaling
    //         $sjScaleXHP = FFXIPH_SkillGrades::$MobHPScale[$sJobGrade][$scaleX];   // Sub job modifier scale

    //         $RIgrade = min($mLvl, 5); // RI Grade
    //         $RIbase  = 1;                        // Column for RI base

    //         $RI = FFXIPH_SkillGrades::$MobRBI[$RIgrade][$RIbase]; // Random Increment addition per grade vs. base

    //         $mLvlIf    = ($mLvl > 5 ? 1 : 0);
    //         $mLvlIf30  = ($mLvl > 30 ? 1 : 0);
    //         $raceScale = 6;
    //         $mLvlScale = 0;

    //         if ($mLvl > 0)
    //         {
    //             $baseMobHP = $BaseHP + (min($mLvl, 5) - 1) * ($JobScale + $raceScale - 1) + $RI + $mLvlIf * (min($mLvl, 30) - 5) * (2 * ($JobScale + $raceScale) + min($mLvl, 30) - 6) / 2 + $mLvlIf30 * (($mLvl - 30) * (63 + $ScaleXHP) + ($mLvl - 31) * ($JobScale + $raceScale));
    //         }

    //         // 50+ = 1 hp sjstats
    //         if ($mLvl > 49)
    //         {
    //             $mLvlScale = floor($mLvl);
    //         }
    //         // 40-49 = 3/4 hp sjstats
    //         else if ($mLvl > 39)
    //         {
    //             $mLvlScale = floor($mLvl * 0.75);
    //         }
    //         // 31-39 = 1/2 hp sjstats
    //         else if ($mLvl > 30)
    //         {
    //             $mLvlScale = floor($mLvl * 0.50);
    //         }
    //         // 25-30 = 1/4 hp sjstats
    //         else if ($mLvl > 24)
    //         {
    //             $mLvlScale = floor($mLvl * 0.25);
    //         }
    //         // 1-24 = no hp sjstats
    //         else
    //         {
    //             $mLvlScale = 0;
    //         }

    //         $sjHP = ceil(($sjJobScale * (max(($mLvlScale - 1), 0)) + (0.5 + 0.5 * $sjScaleXHP) * (max($mLvlScale - 10, 0)) + max($mLvlScale - 30, 0) + max($mLvlScale - 50, 0) + max($mLvlScale - 70, 0)) / 2);

    //         // Orcs 5% more hp
    //         if (($familyID == 189) || ($familyID == 190) || ($familyID == 334) || ($familyID == 407))
    //         {
    //             $mobHP = ($baseMobHP + $sjHP) * 1.05;
    //         }
    //         // Quadavs 5% less hp
    //         else if (($familyID == 200) || ($familyID == 201) || ($familyID == 202) || ($familyID == 337) || ($familyID == 397) || ($familyID == 408))
    //         {
    //             $mobHP = ($baseMobHP + $sjHP) * .95;
    //         }
    //         // Manticore family has 50% more HP
    //         else if ($familyID == 179)
    //         {
    //             $mobHP = ($baseMobHP + $sjHP) * 1.5;
    //         }
    //         else
    //         {
    //             $mobHP = $baseMobHP + $sjHP;
    //         }

    //         // if (PMob->PMaster != nullptr)
    //         // {
    //         //     $mobHP *= 0.30f; // Retail captures have all pets at 30% of the mobs family of the same level
    //         // }

    //         $this->HP = $mobHP;
    //     }
    //     else{
    //         $this->HP = $SQLmob->HPmodifier;
    //     }

    //     $hasMp = false;

    //     switch ($mJob)
    //     {
    //         case FFXIPackageHelper_Variables::$jobArrayByName["PLD"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["WHM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["BLM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["RDM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["DRK"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["BLU"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["SCH"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["SMN"]:
    //             $hasMp = true;
    //             break;
    //         default:
    //             break;
    //     }

    //     switch ($sJob)
    //     {
    //         case FFXIPackageHelper_Variables::$jobArrayByName["PLD"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["WHM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["BLM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["RDM"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["DRK"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["BLU"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["SCH"]:
    //         case FFXIPackageHelper_Variables::$jobArrayByName["SMN"]:
    //             $hasMp = true;
    //             break;
    //         default:
    //             break;
    //     }

    //     // if (PMob->getMobMod(MOBMOD_MP_BASE))
    //     // {
    //     //     hasMp = true;
    //     // }

    //     if ($hasMp)
    //     {
    //         $scale = $SQLmob->MPscale;

    //         if ($SQLmob->MPmodifier == 0)
    //         {
    //             $this->MP = (18.2 * pow($mLvl, 1.1075) * $scale) + 10;
    //         }
    //         else
    //         {
    //             $this->MP = $SQLmob->MPmodifier;
    //         }

    //     }

    //     // ((CItemWeapon*)PMob->m_Weapons[SLOT_MAIN])->setDamage(GetWeaponDamage(PMob, SLOT_MAIN));
    //     // ((CItemWeapon*)PMob->m_Weapons[SLOT_RANGED])->setDamage(GetWeaponDamage(PMob, SLOT_RANGED));

    //     // // reduce weapon delay of MNK
    //     // if (PMob->GetMJob() == JOB_MNK)
    //     // {
    //     //     ((CItemWeapon*)PMob->m_Weapons[SLOT_MAIN])->resetDelay();
    //     // }

    //     // // Deprecate MOBMOD_DUAL_WIELD later, replace if check with value from DB
    //     // if (PMob->getMobMod(MOBMOD_DUAL_WIELD))
    //     // {
    //     //     PMob->m_dualWield = true;
    //     //     // if mob is going to dualWield then need to have sub slot
    //     //     // assume it is the same damage as the main slot
    //     //     static_cast<CItemWeapon*>(PMob->m_Weapons[SLOT_SUB])->setDamage(GetWeaponDamage(PMob, SLOT_MAIN));
    //     // }

    //     $fSTR = FFXIPH_SkillGrades::baseToRank($this->getSTR(), $mLvl);
    //     $fDEX = FFXIPH_SkillGrades::baseToRank($this->getDEX(), $mLvl);
    //     $fVIT = FFXIPH_SkillGrades::baseToRank($this->getVIT(), $mLvl);
    //     $fAGI = FFXIPH_SkillGrades::baseToRank($this->getAGI(), $mLvl);
    //     $fINT = FFXIPH_SkillGrades::baseToRank($this->getINT(), $mLvl);
    //     $fMND = FFXIPH_SkillGrades::baseToRank($this->getMND(), $mLvl);
    //     $fCHR = FFXIPH_SkillGrades::baseToRank($this->getCHR(), $mLvl);

    //     $mSTR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][2], $mLvl );
    //     $mDEX = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][3], $mLvl );
    //     $mVIT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][4], $mLvl );
    //     $mAGI = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][5], $mLvl );
    //     $mINT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][6], $mLvl );
    //     $mMND = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][7], $mLvl );
    //     $mCHR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$mJob][8], $mLvl );

    //     $sSTR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][2], $sLvl );
    //     $sDEX = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][3], $sLvl );
    //     $sVIT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][4], $sLvl );
    //     $sAGI = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][5], $sLvl );
    //     $sINT = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][6], $sLvl );
    //     $sMND = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][7], $sLvl );
    //     $sCHR = FFXIPH_SkillGrades::baseToRank( FFXIPH_SkillGrades::$JobGrades[$sJob][8], $sLvl );


    //     // As per conversation with Jimmayus, all mobs at any level get bonus stats from subjobs.
    //     // From lvl 45 onwards, 1/2. Before lvl 30, 1/4. In between, the value gets progresively higher, from 1/4 at 30 to 1/2 at 44.
    //     // Im leaving that range at 1/3, for now.
    //     if ($mLvl >= 45)
    //     {
    //         $sSTR /= 2;
    //         $sDEX /= 2;
    //         $sAGI /= 2;
    //         $sINT /= 2;
    //         $sMND /= 2;
    //         $sCHR /= 2;
    //         $sVIT /= 2;
    //     }
    //     else if ($mLvl > 30)
    //     {
    //         $sSTR /= 3;
    //         $sDEX /= 3;
    //         $sAGI /= 3;
    //         $sINT /= 3;
    //         $sMND /= 3;
    //         $sCHR /= 3;
    //         $sVIT /= 3;
    //     }
    //     else
    //     {
    //         $sSTR /= 4;
    //         $sDEX /= 4;
    //         $sAGI /= 4;
    //         $sINT /= 4;
    //         $sMND /= 4;
    //         $sCHR /= 4;
    //         $sVIT /= 4;
    //     }

    //     // [stat] = floor[family Stat] + floor[main job Stat] + floor[sub job Stat]
    //     $this->setSTR($fSTR + $mSTR + $sSTR);
    //     $this->setDEX($fDEX + $mDEX + $sDEX);
    //     $this->setVIT($fVIT + $mVIT + $sVIT);
    //     $this->setAGI($fAGI + $mAGI + $sAGI);
    //     $this->setINT($fINT + $mINT + $sINT);
    //     $this->setMND($fMND + $mMND + $sMND);
    //     $this->setCHR($fCHR + $mCHR + $sCHR);
    // }

}

?>