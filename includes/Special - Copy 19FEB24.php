<?php
//namespace MediaWiki\Extension\MyExtension;

use MediaWiki\MediaWikiServices;

class SpecialASBSearch extends SpecialPage {
    public function __construct( $title = 'ASBSearch' ) {
        parent::__construct( $title );
    }

	static function onBeforePageDisplay( $out, $skin ) : void  { 
		$out->addModules(['inputHandler']);
	}

	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		//$output->addModules(['inputHandler']);

		//$output->enableOOUI();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		//$wikitext = 'Hello world!';	

////////////////////////////////////////////
		$wikitext = self::build_table(self::getRates());

		$formDropDown = '<form action="fawefaew" method="POST">;
				<select name="lang" multiple>
					<option>choose zone</option>
					<option value="akjweh">alhkjf</option>
				</select>
			</form>';

		$formTextInput = '<form >
			<input type="" name="descr" id="descr">
			<input type="button" id="a123" name="test" value="submit" >
			</form>
			<p id="msg"></p>
		';

		// $uiLayout = new OOUI\HorizontalLayout( [
		// 	'items' => [
		// 		new OOUI\TextInputWidget( [
		// 			'placeholder' => 'A form text field'
		// 		] ),
		// 		new OOUI\ButtonWidget( [
		// 			'label' => 'Search',
		// 			'icon' => 'search',
		// 			'name' => 'search',
		// 			'flags' => [ 'primary', 'progressive'],
		// 			'method' => 'post'
		// 		] )
		// 	]
		// ] );
		// $btn = new OOUI\TextInputWidget( [
		// 	'placeholder' => 'A form text field'
		// ] );

		//$output->addHTML( $uiLayout );
		$output->addHTML( $formTextInput );

		$output->addWikiTextAsInterface( $wikitext );
	}

	function getZoneNames(){
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnectionRef( DB_REPLICA );

		return $dbr->newSelectQueryBuilder()
		->select( [ 'zonename' ] )
		->from( 'cargo__asb_zonelist' )
		->caller( __METHOD__ )->fetchResultSet(); 
	}

	function getRates(){
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnectionRef( DB_REPLICA );

		return $dbr->newSelectQueryBuilder()
		->select( [ 'cargo__asb_mob_droplist.name', 
					'cargo__asb_mob_droplist.itemRate', 
					'cargo__asb_mob_droplist.dropid',
					'cargo__asb_zonelist.zonename',
					'cargo__asb_mob_groups.name' ] )
		->from( 'cargo__asb_mob_droplist' )
		->join( 'cargo__asb_mob_groups', null, 'cargo__asb_mob_groups.dropid=cargo__asb_mob_droplist.dropid' )
		->join( 'cargo__asb_zonelist', null, 'cargo__asb_zonelist.zoneid=cargo__asb_mob_groups.zoneid')
		//->field( 'cargo__asb_mob_groups.name', null )
		// |join on=asb_mob_groups.zoneid=asb_zonelist.zoneid,asb_mob_droplist.dropid=asb_mob_groups.dropid
		->where( [
			'cargo__asb_zonelist.zoneid' => '123'
		])
		->caller( __METHOD__ )->fetchResultSet(); 
	}

	function build_table($items)
	{
		$html = "<br><div style=\"max-height: 500px; overflow: auto; display: inline-block;\"><table><tr><th>Zone Name</th><th>Mob Name</th><th>Drop Name</th><th>Drop Percentage</th></tr>";
		

		foreach ($items as $row)
		{
			//DEBUG	
			// $properties = get_object_vars( $row );
			// print_r( $properties );
			//DEBUG	
			
			$droprate = ($row->itemRate)/10;
			if ( $droprate == 0) continue;
			$html .= "<tr><td><center>$row->zonename</center></td><td><center>$row->name</center></td><td><center>$row->name</center></td><td><center>$droprate %</center></td></tr>";
		}

		$html .= '</table></div>';
		return $html;
	}
}