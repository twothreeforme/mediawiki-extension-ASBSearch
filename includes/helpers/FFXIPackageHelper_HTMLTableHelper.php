<?php

class FFXIPackageHelper_HTMLTableHelper {

    public static function tableHeader_Forecast(){
		$html = "";
		/************************
		 * Initial HTML for the table
		 */
		$html .= "<br>
		<div ><i> All data and probabilities are based on AirSkyBoat. All earth times are based on your local timezone.</i></div>
		<div style=\"max-height: 400px; overflow: auto; display: inline-block; width: 100%; position: relative; overflow: scroll;\">
		<table id=\"special-weatherforecast-table\" class=\"horizon-table general-table special-weatherforecast-table  sortable\">
            <tr><th>Zone Name</th>
			<th>Vana Days</th>
            <th>Earth Time</th>
            <th>Day's Element</th>
            <th>Moon Phase</th>
			<th>Normal (50%)</th>
			<th>Common (35%)</th>
            <th>Rare (15%)</th>
			</tr>
            ";
		return $html;
	}

    public static function buildWeatherTableRow_DayElement($day, $dayColor){
        // if ( $day == "Lightsday" ) return "<td style=\"text-align:center; text-shadow: -0.5px -0.5px 0 #888888, 0.5px -0.5px 0 #888888, -0.5px 0.5px 0 #888888, 0.5px 0.5px 0 #888888; color:" . $dayColor . "\" ><b>" .  $day . "</b></td>";
        // else
        return "<td style=\"text-align:center; color:" . $dayColor . "\" ><b>" . $day . "</b></td>" ;
    }

    public static function tableHeader_DropRates($showTH){
		$html = "<br>
		<div ><i><b>Disclosure:</b>  All data here is from AirSkyBoat, with minor additions/edits made based on direct feedback from Horizon Devs.<br>Any Horizon specific changes made to the table will be marked with the Template:Changes->{{Changes}} tag.<br><b>**</b> are nuanced drop rates. Please refer to that specific page for more details on how drop rates are calculated.
		<br> <strike>Item Name</strike><sup>(OOE)</sup> are Out of Era items, and are left in the table because it is still unknown how removing these has effected Group drop rates (mainly from BCNMs).</i> </div>
		<div style=\"max-height: 900px; overflow: auto; display: inline-block; width: 100%;\">
		<table id=\"asbsearch_dropstable\" class=\"sortable\">
			<tr><th>Zone Name</th>
			<th>Mob Name <sup>(lvl)</sup></th>
			<th>Details</th>
			<th>Item - Drop Rate</th>
			";
			//<th>Drop Percentage</th>
			//<th>Item (sort)Name</th>
		if ( $showTH == 1) $html .= "<th>TH1</th><th>TH2</th><th>TH3</th><th>TH4</th>";
		$html .= "</tr>";

		return $html;
    }

    public static function table_DropRates($dropRatesArray, $showTH)
	{		
		$html = FFXIPackageHelper_HTMLTableHelper::tableHeader_DropRates($showTH);

		foreach ( $dropRatesArray as $row ) {

				/*******************************************************
				 * Removing OOE 
				 */
				// First check zone names
				
				//$zn = str_replace("[S]", "(S)", $zn );
				// $skipRow = false;
				// foreach( ExclusionsHelper::$zones as $v) { 
				// 	//print_r($zn);
				// 	if ( $zn == $v ) { $skipRow = true; break; } }
				// if ( $skipRow == true ) continue;
				$zn = ParserHelper::zoneERA_forList($row['zoneName']);
				if ( !$zn ) { continue; }
				if ( ExclusionsHelper::mobIsOOE($row['mobName']) ) { continue; }
				/*******************************************************/

				/*******************************************************
				 * This section generally to help deal with gaps between the mob drops and bcnm crate lists 
				 */
				$minL = null; $maxL = null; $dType = null; $mobChanges = null;
				// if ( property_exists($row, 'mobMinLevel') ) $minL = $row->mobMinLevel;
				// if ( property_exists($row, 'mobMaxLevel') ) $maxL = $row->mobMaxLevel;
				// if ( property_exists($row, 'dropType') ) $dType = $row->dropType;
				if ( array_key_exists('mobMinLevel', $row) ) $minL = $row['mobMinLevel'];
				if ( array_key_exists('mobMaxLevel', $row) ) $maxL = $row['mobMaxLevel'];
				if ( array_key_exists('type', $row['dropData']) ) $dType = $row['dropData']['type'];
				else $dType = 1; 	// All bcnm drops are part of a group 	
				if ( array_key_exists('mobChanges', $row) ) $mobChanges = $row['mobChanges'];
				else $mobChanges = 0;

				$zn = ParserHelper::zoneName($row['zoneName']);
				$mn = ParserHelper::mobName($row['mobName'], $minL, $maxL, $row['mobType'], $row['zoneName'], $mobChanges); //need to readdress this later
				
				$html .= "<tr><td><center>$zn</center></td><td><center>$mn</center></td>";
				/*******************************************************/


				/*******************
				 * Handle drop details / grouping / type
				 */
				//print_r($dType);
				$dropDetails = "-";
				if ( $row['dropData']['groupId'] != "0" ) {
					$gR = $row['dropData']['groupRate'];
					if ( $gR > 1000 ) $gR = 1000;
					$dropDetails = "Group " . $row['dropData']['groupId'] . " - " . ($row['dropData']['groupRate'] / 10) . "%" ;	
				}
				else {
					switch ($dType) {
							case 2:
								$dropDetails = "Steal";
								break;
							case 4;
								$dropDetails = 'Despoil';
								break;
							default:
								break;
						}
				}
				$html .= "<td><center>$dropDetails</center></td>";
				/*******************************************************/


				/*******************
				 * Add items as individual tables inside a cell
				 */
				$html .= "<td><table id=\"asbsearch_dropstable2\" >";
				for ( $i = 0; $i < count($row['dropData']['items']); $i ++){
					$item = $row['dropData']['items'][$i];

					$i_n = ParserHelper::itemName($item);
					$gR = $row['dropData']['groupRate'];
					if ( $gR < 1000 ) $gR = 1000;
					$i_dr = ((int)$item['dropRate'] / $gR) * 100 ;
					

					if ( $dType == 2 || $dType == 4 ) $html .= "<tr><center>" . $i_n . "</center></tr>";
					else if ( $i_dr == 0 ) $html .= "<tr><center>" . $i_n . " - " . " ??? </center></tr>";
					else if ( $item['id'] == 65535 ) $html .= "<tr><center>[[Image:Gil_icon.png|18px]] " . $i_n . " - " . $item['gilAmt'] ."</center></tr>";
					else $html .= "<tr><center>" . $i_n . " - " . $i_dr ."%</center></tr>";
				}
				$html .= "</table></td>"; 
				/*******************************************************/


				/*******************
				 * Add TH values
				 */
				if ( $showTH == 1){
					$item = $row['dropData']['items'][0];
					$cat = 0; // @ALWAYS =     1000;  -- Always, 100%

					if ( $row['dropData']['groupId'] == "0" ) {
						//print_r($dType);
						if ( $item['dropRate'] == 0 || $dType != 0 ) $cat = 8;
						elseif ( $item['dropRate'] == 240 ) $cat = 1; 	//@VCOMMON -- Very common, 24%
						elseif ( $item['dropRate'] == 150 ) $cat = 2; 	//@COMMON -- Common, 15%
						elseif ( $item['dropRate'] == 100 ) $cat = 3; 	//@UNCOMMON -- Uncommon, 10%
						elseif ( $item['dropRate'] == 50 ) $cat = 4; 	//@RARE -- Rare, 5%
						elseif ( $item['dropRate'] == 10 ) $cat = 5; 	//@VRARE -- Very rare, 1%
						elseif ( $item['dropRate'] == 5 ) $cat = 6; 	//@SRARE -- Super Rare, 0.5%
						elseif ( $item['dropRate'] == 1 ) $cat = 7; 	//@URARE -- Ultra rare, 0.1%
						else $cat = 8;
					}
					else $cat = 8;

					$th1 = 0; $th2 = 0; $th3 = 0; $th4 = 0;
					
                    function thAdjust($rate, $multiplier){
                        $num = round(($rate * $multiplier) / 10, 2);
                        if ( $num >= 100 ) return "~99";
                        else return $num;
                    }

					switch ($cat) {
						case 0:
							$th1 = 100; $th2 = 100; $th3 = 100; $th4 = 100;
							break;
						case 1:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 2.333); $th3 = self::thAdjust($item['dropRate'], 2.5); $th4 = self::thAdjust($item['dropRate'], 2.666);
							break;
						case 2:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 2.666); $th3 = self::thAdjust($item['dropRate'], 2.833); $th4 = self::thAdjust($item['dropRate'], 3);
							break;
						case 3:
							$th1 = self::thAdjust($item['dropRate'], 1.2); $th2 = self::thAdjust($item['dropRate'], 1.5); $th3 = self::thAdjust($item['dropRate'], 1.65); $th4 = self::thAdjust($item['dropRate'], 1.8);
							break;
						case 4:
							$th1 = self::thAdjust($item['dropRate'], 1.2); $th2 = self::thAdjust($item['dropRate'], 1.4); $th3 = self::thAdjust($item['dropRate'], 1.5); $th4 = self::thAdjust($item['dropRate'], 1.6);
							break;	
						case 5:
							$th1 = self::thAdjust($item['dropRate'], 1.5); $th2 = self::thAdjust($item['dropRate'], 2); $th3 = self::thAdjust($item['dropRate'], 2.25); $th4 = self::thAdjust($item['dropRate'], 2.5);
							break;		
						case 6:
							$th1 = self::thAdjust($item['dropRate'], 1.5); $th2 = self::thAdjust($item['dropRate'], 2); $th3 = self::thAdjust($item['dropRate'], 2.4); $th4 = self::thAdjust($item['dropRate'], 2.8);
							break;
						case 7:
							$th1 = self::thAdjust($item['dropRate'], 2); $th2 = self::thAdjust($item['dropRate'], 3); $th3 = self::thAdjust($item['dropRate'], 3.5); $th4 = self::thAdjust($item['dropRate'], 4);
							break;
						case 8;
							$th1 = "-"; $th2 = "-"; $th3 = "-"; $th4 = "-";
							break;						
						default:
						break;
					}

					$html .= "<td><center>$th1 %</center></td><td><center>$th2 %</center></td><td><center>$th3 %</center></td><td><center>$th4 %</center></td>";
				}
				/*******************************************************/

				$html .= "</tr>";
		}
		return $html;
	}
}

?>