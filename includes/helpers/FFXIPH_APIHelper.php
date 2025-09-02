<?php

use MediaWiki\MediaWikiServices;

class FFXIPH_APIHelper {

    private $adminArray = [ "bureaucrat","interface-admin","sysop","senior-editor"];

    public function __construct() {
    }

    public function userIsAuth(){
        $user = RequestContext::getMain()->getUser();
        $userGroups = MediaWikiServices::getInstance()->getUserGroupManager()->getUserGroups( $user );

        if ( $user->user_id == 2220 ) return true; 

        if ( count( array_intersect($this->adminArray, $userGroups) ) == 0 ) return false;
        else return true;
    }

}

?>