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

    public function getSlash_sdt(){ return $this->slash_sdt ?? 0; }
    public function getPierce_sdt(){ return $this->pierce_sdt ?? 0; }
    public function getH2H_sdt(){ return $this->h2h_sdt ?? 0; }
    //public function getimpact_sdt(){ return $this->impact_sdt ?? 0; }

    public function getMagical_sdt(){ return $this->magical_sdt ?? 0; }
    public function getFire_sdt(){ return $this->fire_sdt ?? 0; }
    public function getIce_sdt(){ return $this->ice_sdt ?? 0; }
    public function getWind_sdt(){ return $this->wind_sdt ?? 0; }
    public function getEarth_sdt(){ return $this->earth_sdt ?? 0; }
    public function getLightning_sdt(){ return $this->lightning_sdt ?? 0; }
    public function getWater_sdt(){ return $this->water_sdt ?? 0; }
    public function getLight_sdt(){ return $this->light_sdt ?? 0; }
    public function getDark_sdt(){ return $this->dark_sdt ?? 0; }
    
    public function getFire_res_rank(){ return $this->fire_res_rank ?? 0; }
    public function getIce_res_rank(){ return $this->ice_res_rank ?? 0; }
    public function getWind_res_rank(){ return $this->wind_res_rank ?? 0; }
    public function getEarth_res_rank(){ return $this->earth_res_rank ?? 0; }
    public function getLightning_res_rank(){ return $this->lightning_res_rank ?? 0; }
    public function getWater_res_rank(){ return $this->water_res_rank ?? 0; }
    public function getLight_res_rank(){ return $this->light_res_rank ?? 0; }
    public function getDark_res_rank(){ return $this->dark_res_rank ?? 0; }

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

    public function setSlash_sdt($slash_sdt){                   return $this->slash_sdt             = $slash_sdt; }
    public function setPierce_sdt($pierce_sdt){                 return $this->pierce_sdt            = $pierce_sdt; }
    public function setH2H_sdt($h2h_sdt){                       return $this->h2h_sdt               = $h2h_sdt; }
    //public function setimpact_sdt($impact_sdt){               return $this->impact_sdt            = $impact_sdt; }

    public function setMagical_sdt($magical_sdt){               return $this->magical_sdt           = $magical_sdt; }
    public function setFire_sdt($fire_sdt){                     return $this->fire_sdt              = $fire_sdt; }
    public function setIce_sdt($ice_sdt){                       return $this->ice_sdt               = $ice_sdt; }
    public function setWind_sdt($wind_sdt){                     return $this->wind_sdt              = $wind_sdt; }
    public function setEarth_sdt($earth_sdt){                   return $this->earth_sdt             = $earth_sdt; }
    public function setLightning_sdt($lightning_sdt){           return $this->lightning_sdt         = $lightning_sdt; }
    public function setWater_sdt($water_sdt){                   return $this->water_sdt             = $water_sdt; }
    public function setLight_sdt($light_sdt){                   return $this->light_sdt             = $light_sdt; }
    public function setDark_sdt($dark_sdt){                     return $this->dark_sdt              = $dark_sdt; }

    public function setFire_res_rank($fire_res_rank){           return $this->fire_res_rank         = $fire_res_rank; }
    public function setIce_res_rank($ice_res_rank){             return $this->ice_res_rank          = $ice_res_rank; }
    public function setWind_res_rank($wind_res_rank){           return $this->wind_res_rank         = $wind_res_rank; }
    public function setEarth_res_rank($earth_res_rank){         return $this->earth_res_rank        = $earth_res_rank; }
    public function setLightning_res_rank($lightning_res_rank){ return $this->lightning_res_rank    = $lightning_res_rank; }
    public function setWater_res_rank($water_res_rank){         return $this->water_res_rank        = $water_res_rank; }
    public function setLight_res_rank($light_res_rank){         return $this->light_res_rank        = $light_res_rank; }
    public function setDark_res_rank($dark_res_rank){           return $this->dark_res_rank         = $dark_res_rank; }

    public function setEVA($baseEVA){  
        // Enemy evasion = f(Lv, main job evasion skill rank) + [AGI/2] + job characteristics
        $EVA = $baseEVA + ($this->getAGI() / 2);
        $this->EVA = $EVA; 
    }
    public function setDEF($baseDEF){
        // Enemy defense = [baseDEF + 8 + [VIT/2] + job characteristics] x racial characteristics

        $DEF = ($baseDEF + 8 + ($this->getVIT() / 2) );
        $this->DEF = $DEF; 
    }
    public function setATT($ATT){  $this->ATT = $ATT; }
    public function setACC($ACC){  $this->ACC = $ACC; }


}

?>