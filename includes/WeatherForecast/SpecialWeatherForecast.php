<?php


class SpecialWeatherForecast extends SpecialPage {

    public function __construct( ) {
        parent::__construct( 'WeatherForecast' );
    }

    static function onBeforePageDisplay( $out, $skin ) : void  { 
        $out->addModules(['FFXIPackageHelper_WeatherForecast']);

    }

    function zoneNameArray($weatherArray){
        $result = array();
        foreach ( $weatherArray as $zone){
            $result[$zone['name']] =  $zone['pagelinkname'] ;
        }
        ksort($result);
        return $result;
    }

    function execute( $par ) {


        $request = $this->getRequest();
		$output = $this->getOutput();
		//$output->addModules(['inputHandler']);
		$output->setPageTitle( $this->msg( 'weatherforecast' ) );

        $host = $_SERVER['HTTP_HOST'];
        //print_r( $host . "/Special:DiggingWeatherForecast");
        $output->addHTML( "<br><center><button type=\"button\" onclick=\"location.href='/Special:DiggingWeatherForecast'\">Digging - Weather Page</button></center><br><br>"  );
        $this->setHeaders();

		$zoneNameDropDown = $request->getText( 'zoneNameDropDown' );
		$weatherTypeDropDown = $request->getText( 'weatherTypeDropDown' );

        //$zoneNamesList
        $db = new DBConnection();
        $weatherArray = $db->getWeather(null);
        $zoneNamesList = $this->zoneNameArray($weatherArray);

        // print_r($zoneNameDropDown);
        // print_r($weatherTypeDropDown);

        $wikitext = "";
        if ( !$zoneNameDropDown  && !$weatherTypeDropDown  ) {
			//$wikitext = ' Make a selection ';
		}
        else {
            //create new array with weather values
            $wikitext .= $this->showWeatherPressed( $weatherArray, $zoneNameDropDown, $weatherTypeDropDown );
        }
        //print_r( array_values($zoneNamesList) );

        $formDescriptor = [
			// 'info' => [
			// 	'type' => 'info',
			// 	'label' => 'info',
			// 	// Value to display
			// 	'default' => 'Select a zone, and enter characters to search for in the mob name. Leave \'Mob name\' field blank to see all mobs. ',
			// 	// If true, the above string won't be HTML escaped
			// 	'raw' => true,
			// ],
            'weatherTypeDropDown' => [
				'type' => 'limitselect',
				'name' => 'weatherTypeDropDown',
				'label' => 'Select Weather Type', // Label of the field
				'class' => 'HTMLSelectField', // Input type
				'options' => WeatherForecast_ElementMaps::$elements ,
				'default' => 8,
			],
			'zoneNameDropDown' => [
				'type' => 'limitselect',
				'name' => 'zoneNameDropDown',
				'label' => 'Select Zone', // Label of the field
				'class' => 'HTMLSelectField', // Input type
				'options' => $zoneNamesList,
				'default' => "searchallzones",
            ]
        ];


    	$htmlForm = new HTMLForm( $formDescriptor, $this->getContext(), 'WeatherForecast_Form' );
		$htmlForm->setMethod( 'get' );
		// Text to display in submit button
		$htmlForm->setSubmitText( 'Show Weather' );

		// We set a callback function
		$htmlForm->setSubmitCallback( [ $this, 'processInput' ] );
		// Call processInput() in your extends SpecialPage class on submit
		$htmlForm->show(); // Display the form

		//print_r($htmlForm->wasSubmitted());


        $output->addWikiTextAsInterface( $wikitext );
    }


    public static function processInput( $formData ) {
        // print_r( $formData['weatherTypeDropDown']);
        // print_r( $formData['zoneNameDropDown']);

		// If true is returned, the form won't display again
		// If a string is returned, it will be displayed as an error message with the form
		if ( $formData['weatherTypeDropDown'] == 8 && $formData['zoneNameDropDown'] == 'searchallzones' ) {
			//return 'Select something first....';
            return;
		}
		return false;
	}


    function showWeatherPressed($weatherArray, $zone, $weatherType){
        $html = HTMLTableHelper::buildTableHeaders();

        $time = new VanaTime();
        //print_r($zone ." : ". $weatherType ." : ". count($weatherArray) );
        //print_r("<br>" . $time->weekDayFrom(1));

        $shouldAddDay = null;
        if ( $zone == "searchallzones" && $weatherType == 8){
            $html = "<br /><div><i><b> You must select a zone or specific element. </b></i></div>";
        }
        else {
            foreach( $weatherArray as $row ){
            //print_r("<br />" . $row['name'] . " : " . $zone );
                if (!$row['weather']) continue;

                foreach($row['weather'] as $key => $day) {

                    foreach( $day as $dayWeatherType){
                        if ( $row['pagelinkname'] == $zone || $zone == "searchallzones"){
                            if ( $weatherType == 8 ){
                                $shouldAddDay = $day;
                                break;
                            }
                            foreach ( WeatherForecast_ElementMaps::$weatherelements[$weatherType] as $value){
                                if ( str_contains($dayWeatherType, $value ) ) {
                                    //print_r( "<br> match: " . $value . " : ". $dayWeatherType);
                                    $shouldAddDay = $day;
                                    break 2;
                                }
                            }
                        }
                    }

                    // Add row to table
                    if ( $shouldAddDay != null && $shouldAddDay != 0){
                        $vt = $time->getVanaTimeFromDaysAhead($key);
                        //$vanadays = ( $key == 0 ) ? "0 (Today)" : $key;
                        $vanadays = $key;

                        $weekDayEle = $time->getWeekDayElement($key);
                        //if ( $weekDayEle == "Lightsday" ) $weekDayEle = "<td style=\"text-align:center; text-shadow: -0.5px -0.5px 0 #262626, 0.5px -0.5px 0 #262626, -0.5px 0.5px 0 #262626, 0.5px 0.5px 0 #262626; color:" . $time->dayColor($vt) . "\" ><b>" .  $weekDayEle . "</b></td>";
                        //else $weekDayEle = "<td style=\"text-align:center; color:" . $time->dayColor($vt) . "\" ><b>" . $weekDayEle . "</b></td>" ;

                        //print_r($key);

                        $name = ParserHelper::zoneName($row['name']);

                        $html .= "<tr><td>". $row['name'] ."</td><td style=\"text-align:center;\">$vanadays</td><td>" . $time->earthTime($vt) . "</td><td style=\"text-align:center; color:" . $time->dayColor($vt) . "\" >" . $time->getWeekDayElement($key) .  "</td><td style=\"text-align:center;\">" . $time->moonPhaseNameFrom($key) . "</td><td>". $day['normal']. "</td><td>". $day['common']. "</td><td>". $day['rare']. "</td>";
                        //$html .= "<tr><td>". $name ."</td><td style=\"text-align:center;\">$vanadays</td>" . HTMLTableHelper::buildWeatherTableRow_DayElement($weekDayEle, $time->dayColor($vt)) .  "<td style=\"text-align:center;\">" . $time->moonPhaseNameFrom($key) . "</td><td>". $day['normal']. "</td><td>". $day['common']. "</td><td>". $day['rare']. "</td>";
                        $shouldAddDay = 0;
                    }

                }
            }

            if ( !isset($shouldAddDay) ){
                //print_r( $shouldAddDay )
                $html = "<br /><div><i><b> No weather data matches this request. </b></i></div>";
            }
         }
        return $html;
    }


}

?>

