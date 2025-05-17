<?php

class SpecialEquipsets extends SpecialPage {

    public function __construct( ) {
        parent::__construct( 'Equipsets' );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  {
		//$out->addModules(['FFXIPackageHelper_ASBSearch']);
		if ( $out->getTitle() == "Special:Equipsets" )  {
			$out->addModules(['FFXIPackageHelper_Equipsets']);
		}
	}

	private $requestData =[];

	function execute( $par ) {
		$this->setHeaders();
		$output = $this->getOutput();
		$output->setPageTitle( $this->msg( 'equipsets' ) );
		$request = $this->getRequest();

		/**
		 *	Equipsets Request Data
		 */
		$requestCharacter = new FFXIPH_Character( $request->getText( 'race' ),
													$request->getText( 'mlvl' ),
													$request->getText( 'slvl' ),
													$request->getText( 'mjob' ),
													$request->getText( 'sjob' ),
													$request->getText( 'merits' ),
													$request->getText( 'equipment' ) );

		/**
		 * 	Determine which character to load on startup
		 *  Either the request data (from shared link) or default char saved in DB
		 */											
		if ( !$requestCharacter->isDefault() ) {
			$currentCharacter = $requestCharacter;
		}
		else {
			// Check for user logged in
			$user = RequestContext::getMain()->getUser();
			$uid = $user->getId();
			if ( $uid != 0 ) { // User is logged in
				$db = new DBConnection();
				$defCharacter = $db->getDefaultCharacter( $uid );
				if ( is_null($defCharacter) ) $currentCharacter = new FFXIPH_Character();
				else $currentCharacter = $defCharacter;
			}
			else $currentCharacter = new FFXIPH_Character();
		}

		wfDebugLog( 'Equipsets', json_encode($currentCharacter ) );
		$tabEquipsets = new FFXIPackageHelper_Equipsets($currentCharacter->toArray());

        $html = "<div class=\"FFXIPackageHelper_characterHeader\"><i><b id=\"FFXIPackageHelper_characterHeader_name\">No character selected</b></i><i id=\"FFXIPackageHelper_characterHeader_details\" style=\"font-color:light-grey;\"></i></div>" .
			"<div id=\"initialHide\" style=\"display: none;\">" .
				$this->header() .
				$this->tabEquipsets( $tabEquipsets->showEquipsets() ) .
				$this->tabCharacter( $currentCharacter ) .
            "</div>";

		$output->addHTML( $html );
	}

	public function header(){
        return "<div class=\"FFXIPackageHelper_tabs\">" .
					"<button id=\"FFXIPackageHelper_tabs_equipsets\" class=\"tablinks\">Gear Sets</button>" .
					"<button id=\"FFXIPackageHelper_tabs_characters\" class=\"tablinks\">Characters</button>" .
        		"</div>";
      }

    public function tabEquipsets($content){
        if ( !isset($content) ) $content = "<p>no content yet</p>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_equipsets_shown\" class=\"tabcontent\">
		" . $content . "</div>";

        return $html;
    }

	public function tabCharacter(FFXIPH_Character $c){
		$race = $c->race;
		$stats = $c->meritStats;
		$skill = $c->meritSkills;

		$content = "<span><i><b>Disclosure:</b>  Users must be logged in to save a character. Saving a character stores the RACE and MERITS set below. The character will be de-selected if any changes are made. Refresh button resets stats to default.</i></span>" .

					"<div id=\"FFXIPackageHelper_equipsets_charTab\" >" .
						FFXIPackageHelper_HTMLOptions::selectableButtonsBar("FFXIPackageHelper_equipsets_charSelect") .
						
						"<div id=\"FFXIPackageHelper_equipsets_charSelectMerits\">" .

							"<div class=\"FFXIPackageHelper_equipsets_charSelectOptionsMenu\">" .
								"<button id=\"FFXIPackageHelper_editCharButton\" class=\"FFXIPackageHelper_editCharButton\">Edit</button>" .
								"<button id=\"FFXIPackageHelper_dynamiccontent_saveChar\" class=\"FFXIPackageHelper_newCharButton FFXIPackageHelper_saveCharButton\">Save</button>" .
							"</div>" .
							"<div id=\"FFXIPackageHelper_dynamiccontent_newCharSection\" style=\"display: none;\" >" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Name</p>" .
								"<input type=\"text\" id=\"FFXIPackageHelper_dynamiccontent_charNameInput\" class=\"FFXIPackageHelper_dynamiccontent_charNameInput\" placeholder=\"Character Name\" maxlength=\"25\"></input><br>" .
							"</div>" .
							"<div class=\"FFXIPackageHelper_equipsets_selectRace\">" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Default</p>" .
								"<label class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultLabel\">" .
									"<input type=\"checkbox\" id=\"FFXIPackageHelper_dynamiccontent_defaultChar\" class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultInput\" disabled></input>" .
									"<span class=\"FFXIPackageHelper_dynamiccontent_addCharDefaultSpan FFXIPackageHelper_dynamiccontent_addCharDefaultSpanround\"></span>" .
								"</label>" .
								"<br><p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Race</p>" . FFXIPackageHelper_HTMLOptions::raceDropDown("FFXIPackageHelper_equipsets_selectRace", $race) . "<br>" .
							"</div>" .
							"<div>" .
								"<p id=\"FFXIPackageHelper_dynamiccontent_raceLabel\">Merits</p>" .
								//"<button id=\"FFXIPackageHelper_dynamiccontent_changeMerits\" class=\"FFXIPackageHelper_dynamiccontent_shareButton\">Edit</button><br>" .
							"</div>" .
							"<div class=\"FFXIPackageHelper_dynamiccontent_showMerits\" >
								<table class=\"FFXIPackageHelper_dynamiccontent_showMerits_table\">
									<tr><td><h4>Stats</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">HP</span></td><td style=\"\">" . $this->meritIncrement(2, $stats['2']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">MP</span></td><td style=\"\">" . $this->meritIncrement(5, $stats['5']) . "</td></tr>
									<tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr><tr></tr>

									<tr><td><span style=\"vertical-align:middle;\">STR</span></td><td style=\"\">" . $this->meritIncrement(8, $stats['8']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">DEX</span></td><td style=\"\">" . $this->meritIncrement(9, $stats['9']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">VIT</span></td><td style=\"\">" . $this->meritIncrement(10, $stats['10']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">AGI</span></td><td style=\"\">" . $this->meritIncrement(11, $stats['11']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">INT</span></td><td style=\"\">" . $this->meritIncrement(12, $stats['12']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">MND</span></td><td style=\"\">" . $this->meritIncrement(13, $stats['13']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">CHR</span></td><td style=\"\">" . $this->meritIncrement(14, $stats['14']) . "</td></tr>

									<tr></tr><tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr>
									<tr><td><h4>Combat Skills</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Hand to Hand</span></td><td style=\"\">" . $this->meritIncrement(80, $skill['80']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Dagger</span></td><td style=\"\">" . $this->meritIncrement(81, $skill['81']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Sword</span></td><td style=\"\">" . $this->meritIncrement(82, $skill['82']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Sword</span></td><td style=\"\">" . $this->meritIncrement(83, $skill['83']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Axe</span></td><td style=\"\">" . $this->meritIncrement(84, $skill['84']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Axe</span></td><td style=\"\">" . $this->meritIncrement(85, $skill['85']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Scythe</span></td><td style=\"\">" . $this->meritIncrement(86, $skill['86']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Polearm</span></td><td style=\"\">" . $this->meritIncrement(87, $skill['87']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Katana</span></td><td style=\"\">" . $this->meritIncrement(88, $skill['88']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Great Katana</span></td><td style=\"\">" . $this->meritIncrement(89, $skill['89']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Club</span></td><td style=\"\">" . $this->meritIncrement(90, $skill['90']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Staff</span></td><td style=\"\">" . $this->meritIncrement(91, $skill['91']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Archery</span></td><td style=\"\">" . $this->meritIncrement(104, $skill['104']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Marksmanship</span></td><td style=\"\">" . $this->meritIncrement(105, $skill['105']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Throwing</span></td><td style=\"\">" . $this->meritIncrement(106, $skill['106']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Guard</span></td><td style=\"\">" . $this->meritIncrement(107, $skill['107']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Evasion</span></td><td style=\"\">" . $this->meritIncrement(108, $skill['108']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Shield</span></td><td style=\"\">" . $this->meritIncrement(109, $skill['109']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Parry</span></td><td style=\"\">" . $this->meritIncrement(110, $skill['110']) . "</td></tr>" .

									"<tr></tr><tr><td style=\"height:10px; background-color: #12396c00 !important;\"></td></tr>" .
									"<tr><td><h4>Magic Skills</h4></td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Divine Magic</span></td><td style=\"\">" . $this->meritIncrement(111, $skill['111']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Healing Magic</span></td><td style=\"\">" . $this->meritIncrement(112, $skill['112']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enhancing Magic</span></td><td style=\"\">" . $this->meritIncrement(113, $skill['113']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Enfeebling Magic</span></td><td style=\"\">" . $this->meritIncrement(114, $skill['114']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Elemental Magic</span></td><td style=\"\">" . $this->meritIncrement(115, $skill['115']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Dark Magic</span></td><td style=\"\">" . $this->meritIncrement(116, $skill['116']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Summoning Magic</span></td><td style=\"\">" . $this->meritIncrement(117, $skill['117']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Ninjutsu</span></td><td style=\"\">" . $this->meritIncrement(118, $skill['118']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Singing</span></td><td style=\"\">" . $this->meritIncrement(119, $skill['119']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">String Instrument</span></td><td style=\"\">" . $this->meritIncrement(120, $skill['120']) . "</td></tr>
									<tr><td><span style=\"vertical-align:middle;\">Wind Instrument</span></td><td style=\"\">" . $this->meritIncrement(121, $skill['121']) . "</td></tr>
								</table>
							</div>" .
							"<button id=\"FFXIPackageHelper_deleteCharButton\" class=\"FFXIPackageHelper_deleteCharButton\">Remove this character</button>" .

						"</div>" .

					"</div>";

        //remove when setting up for use on HXI
        //if ( isset($_SERVER['HTTP_HOST']) &&  $_SERVER['HTTP_HOST'] != 'localhost' ) $content = "<p><b>This site is still under construction. Coming soon !</b></p>";

        $html = "<div id=\"FFXIPackageHelper_tabs_characters_shown\" class=\"tabcontent\">" . $content . "</div>";

        return $html;
    }

	private function meritIncrement($merit, $value = 0){
		if ( $merit <= 14 ) $type = "stats";
		else $type = "skill";

        $html = "<div id=\"FFXIPackageHelper_dynamiccontent_counterbox\" class=\"FFXIPackageHelper_dynamiccontent_counterbox\">
            <button class=\"FFXIPackageHelper_dynamiccontent_incrementButton\">
                <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">

                    <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\" stroke-linecap=\"round\"/>
                </svg>
            </button><input id=\"FFXIPackageHelper_equipsets_merits_$type$merit\" class=\"FFXIPackageHelper_dynamiccontent_incrementInput\" type=\"text\" value=\"$value\" readonly >
            <button class=\"FFXIPackageHelper_dynamiccontent_incrementButton\">
                <svg width=\"10\" height=\"10\" viewBox=\"0 0 10 10\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">

                    <line x1=\"0\" y1=\"5\" x2=\"10\" y2=\"5\" stroke-linecap=\"round\"/>
                    <line x1=\"5\" y1=\"0\" x2=\"5\" y2=\"10\" stroke-linecap=\"round\"/>
                </svg>
            </button>
            </div>";

			return $html;
    }


}