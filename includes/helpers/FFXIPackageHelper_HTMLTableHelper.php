<?php

class HTMLTableHelper {

    public static function buildTableHeaders(){
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
        //  $html .= "<br>
        //     <div ><i> All data and probabilities are based on AirSkyBoat. All earth times are based on your local timezone.</i></div>
        //     <div style=\"max-height: 900px; overflow: auto; display: inline-block; width: 100%;\">
        //     <table class=\"special-weatherforecast-table\">
        //         <tr><th>Zone Name</th>
        //         <th>Vana Days</th>
        //         <th>Earth Time</th>
        //         <th>Day's Element</th>
        //         <th>Moon Phase</th>
        //         <th>Normal (50%)</th>
        //         <th>Common (35%)</th>
        //         <th>Rare (15%)</th>
        //         </tr>
        //         ";
		return $html;
	}

    public static function buildWeatherTableRow_DayElement($day, $dayColor){
        // if ( $day == "Lightsday" ) return "<td style=\"text-align:center; text-shadow: -0.5px -0.5px 0 #888888, 0.5px -0.5px 0 #888888, -0.5px 0.5px 0 #888888, 0.5px 0.5px 0 #888888; color:" . $dayColor . "\" ><b>" .  $day . "</b></td>";
        // else
        return "<td style=\"text-align:center; color:" . $dayColor . "\" ><b>" . $day . "</b></td>" ;
    }
}

?>