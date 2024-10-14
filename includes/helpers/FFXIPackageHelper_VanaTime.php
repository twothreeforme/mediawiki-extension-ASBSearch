<?php

class VanaTime {

    private $elementalDay =     ["Firesday",    "Earthsday",        "Watersday",        "Windsday",         "Iceday",           "Lightningday",     "Lightsday",    "Darksday"];
    private $moonPhaseName =    ["New Moon",    "Waxing Crescent",  "First Quarter",    "Waxing Gibbous",   "Full Moon",        "Waning Gibbous",   "Last Quarter", "Waning Crescent"];
    private $dayColor =         ["#FF0000",     "#80581C",          "#0000DD",           "#00AA22",         "#7799FF",          "#AA00AA",          "#888888",      "#333333"];
    private $moonIcon =         ["\u{1F311}",   "\u{1F312}",        "\u{1F313}",        "\u{1F314}",        "\u{1F315}",        "\u{1F316}",        "\u{1F317}",    "\u{1F318}"];

    private $VTIME_BIRTH = 1024844400000;
    private $VTIME_BASEDATE  = 1009810800;
    private $vanaBirthday = (((898 * 360) + 30) * 24 * 60 * 60) / (25 / 1000); // 1117359360000 - in earth time milliseconds
    private $difference = 92514960000; //$this->vanaBirthday - $this->VTIME_BIRTH;

    // Conversions in Minutes
    private $VTIME_YEAR  =      518400;   // 360 * GameDay
    private $VTIME_MONTH =      43200;      // 30 * GameDay
    private $VTIME_WEEK  =      11520;      // 8 * GameDay
    private $VTIME_DAY   =      1440;       // 24 hours * GameHour
    private $VTIME_HOUR  =      60;         // 60 minutes
    private $VMULTIPLIER =      25;
    private $MOON_CYCLE_DAYS =  84;


    function getTimeZone(){
        if( !isset($_COOKIE['timezone'] )){

            $ip = $_SERVER['REMOTE_ADDR'];

            //Open GeoIP database and query our IP
            $gi = geoip_open("GeoLiteCity.dat", GEOIP_STANDARD);
            $record = geoip_record_by_addr($gi, $ip);

            //If we for some reason didnt find data about the IP, default to a preset location.
            //You can also print an error here.
            if(!isset($record))
            {
                $record = new geoiprecord();
                $record->latitude = 59.2;
                $record->longitude = 17.8167;
                $record->country_code = 'SE';
                $record->region = 26;
            }

            //Calculate the timezone and local time
            try
            {
                //Create timezone
                $user_timezone = new DateTimeZone(get_time_zone($record->country_code, ($record->region!='') ? $record->region : 0));

                setcookie("timezone", strval($user_timezone), time() + (86400 * 30), "/"); //setting cookie to the browser for reference

                //Create local time
                $user_localtime = new DateTime("now", $user_timezone);
                $user_timezone_offset = $user_localtime->getOffset();
            }
            //Timezone and/or local time detection failed
            catch(Exception $e)
            {
                $user_timezone_offset = 7200;
                $user_localtime = new DateTime("now");
            }

            // print_r( 'User local time: ' . $user_localtime->format('H:i:s') . '<br/>' );
            // print_r(  'Timezone GMT offset: ' . $user_timezone_offset . '<br/>' );
        }
    }

    /**
     *  All forms of time are derived from this.
     *  Integer - represented in Vana'diel MINUTES
     */
    public function _vTime(){
        $earthTime = floor( floor(microtime(true) * 1000)  / 1000 );
        return (($earthTime - $this->VTIME_BASEDATE) / 60 * 25) + 886 * $this->VTIME_YEAR;
    }

    public function _vTimeBeginningOfCurrentHour(){
        $earthTime = floor( floor(microtime(true) * 1000)  / 1000 );
        //$earthTime = ( $earthTime - ( $earthTime % (24 * 60  ) ));
        $vt = (int)(($earthTime - $this->VTIME_BASEDATE) / 60 * 25) + 886 * $this->VTIME_YEAR;
        return  ( $vt - ( $vt % ( 24 * 60)));
    }

    /**
     *  Only used to calculate - getWeatherDate()
     *  Should not otherwise be used.
     */
    public function _eTime(){
        return floor( floor(microtime(true) * 1000)  / 1000 );
    }

    /**
     *  Integer - Vana'diel Year
     *  Verified
     */
    public function year($vanatime) {
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        return floor($vanatime / $this->VTIME_YEAR);
    }

    public function today_inMS(){ // result in earth Milliseconds
        $now = ((898 * 360 + 30) * (24 * 60 * 60 * 1000)) + (floor(microtime(true)) - $this->VTIME_BIRTH) * $this->VMULTIPLIER;
        return ( $now - ( $now % (24 * 60 * 60 * 1000) ));
    }

    // public function now_inEarthMS(){
    //     return ((898 * 360 + 30) * (24 * 60 * 60 * 1000)) + (floor(microtime(true)) - $this->VTIME_BIRTH) * $this->VMULTIPLIER;
    // }

    // public function now(){ return $this->now_inEarthMS() / ( 60 * 1000); }

    public function getWeatherDate(){
        return intval((($this->_eTime() - $this->VTIME_BASEDATE) / 3456)) % 2160 ;
    }

    /**
     *
     */
    public function getVanaTimeFromDaysAhead($daysAhead){
        return (int)$this->_vTimeBeginningOfCurrentHour() + ($daysAhead * $this->VTIME_DAY); 
    }

    public function weekDayFrom($daysAhead){
        //print_r("<br/>" . ((int)$this->_vTime() % $this->VTIME_WEEK) + ( $daysAhead * $this->VTIME_DAY));
        return floor(( ( (int)$this->_vTime() + ($daysAhead * $this->VTIME_DAY)) % $this->VTIME_WEEK)  / $this->VTIME_DAY);
    }

    public function weekDay($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        return floor(((int)$vanatime % $this->VTIME_WEEK) / $this->VTIME_DAY);
    }

    public function getWeekDayElement($vanaWeekDay){
        return $this->elementalDay[$this->weekDayFrom($vanaWeekDay)];
    }

    public function dayColor($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        return $this->dayColor[$this->weekDay($vanatime)];
    }

    public function moonLatentPhase($vanatime, $daysAhead){
        if ( !isset($daysAhead) ) $daysAhead = 0;
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        $vanatime = $vanatime + ($daysAhead * $this->VTIME_DAY);

        $moonPhase = $this->moonPercent($vanatime);
        $moonDirection = $this->moonDirection($vanatime);

        if ($moonPhase <= 5 || ($moonPhase <= 10 && $moonDirection == 1)) {return 0;} // New Moon - 10% waning -> 5% waxing
        else if ($moonPhase >= 7 && $moonPhase <= 38 && $moonDirection == 2) {return 1;}  // Waxing Crescent - 7% -> 38% waxing
        else if ($moonPhase >= 40 && $moonPhase <= 55 && $moonDirection == 2){return 2;}  // First Quarter - 40%% -> 55% waxing
        else if ($moonPhase >= 57 && $moonPhase <= 88 && $moonDirection == 2){return 3;}  // Waxing Gibbous - 57% -> 88%
        else if ($moonPhase >= 95 || ($moonPhase >= 90 && $moonDirection == 2)){return 4;}  // Full Moon - waxing 90% -> waning 95%
        else if ($moonPhase >= 62 && $moonPhase <= 93 && $moonDirection == 1){return 5;}  // Waning Gibbous - 93% -> 62%
        else if ($moonPhase >= 45 && $moonPhase <= 60 && $moonDirection == 1){return 6;}  // Last Quarter - 60% -> 45%
        else{return 7;}  // Waning Crescent - 43% -> 12%
    }

    public function moonDirection($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
            $moondays = floor($this->moonDays($vanatime));
            //console.log(daysmod);
            if ($moondays == 42 || $moondays == 0) { return 0; }// neither waxing nor waning
            else if ($moondays < 42){ return 1; } // waning
            else{ return 2; } // waxing
        }

    public function moonDays($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        return  ( (int)(( $vanatime /  $this->VTIME_DAY ) + 26) % $this->MOON_CYCLE_DAYS);
    }

    public function moonPercent($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();
        return abs( round((42 - floor($this->moonDays($vanatime))) / 42 * 100) );
    }

    public function moonPhaseNameFrom($daysAhead){
        $vt = $this->getVanaTimeFromDaysAhead($daysAhead);
        $moonday = $this->moonLatentPhase($vt, null);
        return $this->moonPhaseName[$moonday] . " " . $this->moonPhaseIcon($moonday) . $this->moonPercent($vt) . "%";
    }

    public function moonPhaseIcon($day){
        if (!isset($day) ) return $this->moonIcon[$this->moonLatentPhase(null, null)];
        else return $this->moonIcon[$day];
    }

    public function earthTime($vanatime){
        if ( !isset($vanatime) ) $vanatime = $this->_vTime();

        /**
         * Should be backwards from this
         * $vanatime = (($earthTime - $this->VTIME_BASEDATE) / 60 * 25) + 886 * $this->VTIME_YEAR;
         * $vanatime - (886 * $this->VTIME_YEAR) = (($earthTime - $this->VTIME_BASEDATE) / 60 ) * 25
         * (( $vanatime - (886 * $this->VTIME_YEAR) ) / 25 ) = ($earthTime - $this->VTIME_BASEDATE) / 60
         * ((( $vanatime - (886 * $this->VTIME_YEAR) ) / 25 ) * 60) = ($earthTime - $this->VTIME_BASEDATE)
         * ((( $vanatime - (886 * $this->VTIME_YEAR) ) / 25 ) * 60) + $this->VTIME_BASEDATE = $earthTime
         * 
         * $earthTime = floor( floor(microtime(true) * 1000)  / 1000 );
         * floor (floor ( (($vanatime - (886 * $this->VTIME_YEAR) ) / 25 * 60 + $this->VTIME_BASEDATE) * 1000) / 1000 )
         * 
         **/
        
         $et = floor( floor(microtime(true) * 1000)  / 1000 );
         //$test =  (int)((($et - $this->VTIME_BASEDATE) / 60) * 25) + ( 886 * $this->VTIME_YEAR );
         $test = ((( $vanatime - (886 * $this->VTIME_YEAR) ) / 25 ) * 60) + $this->VTIME_BASEDATE;


         //print_r("<br/>et: " . $et . " vt: " . $test);

        // $vanatime = $vanatime / ( $this->VMULTIPLIER );
        // $vTempTime = $this->today_inMS() / (60 * 1000);
        // $vTempTime = $vTempTime * 60;
        // $vTempTime = floor($vTempTime / (25 / 1000)) - $this->difference;
        $vTempTime =   (int)(((($vanatime / $this->VTIME_YEAR) - 886 / 25 ) * 60 ) + $this->VTIME_BASEDATE) ;


        //print_r("<br/>" . $vTempTime . "  " . (int)$vanatime);

        $this->getTimeZone();
        $dt = new DateTime("now", new DateTimeZone($_COOKIE['timezone']));
        //$dt->setTimestamp(floor((int)$vanatime) - ($this->vanaBirthday - $this->VTIME_BIRTH));
        $dt->setTimestamp( $test );
        return $dt->format("d-M h:i A");
    }


}

 ?>

<script type="text/javascript">
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
if(getCookie('timezone')!=Intl.DateTimeFormat().resolvedOptions().timeZone){
    document.cookie = "timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone;
    location.reload();
}
</script>