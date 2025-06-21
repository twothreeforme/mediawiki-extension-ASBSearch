<?php

use Wikimedia\Rdbms\DatabaseFactory;

class DatabaseConnection {

	private $dbUsername;  
	private $dbPassword;

    public function __construct() {
        global $wgDBuser;
        global $wgDBpassword;

        $this->dbUsername = $wgDBuser;
        $this->dbPassword = $wgDBpassword;
    }

    private function getDatabaseFactory(mixed $database = null): DatabaseMysqli{
        return ( new DatabaseFactory() )->create( 'mysql', [
                'host' => 'localhost',
                'user' => $this->dbUsername,
                'password' => $this->dbPassword,
                'dbname' => $database,
                'flags' => 0,
                'tablePrefix' => ''] );
    }

    public function openConnection(mixed $dbName = null): mixed{
        try {
            $returnDB = $this->getDatabaseFactory($dbName);            
        } catch ( DBConnectionError $e ) {
            $status->fatal( 'config-connection-error', $e->getMessage() );
            return $status;
        }
        return $returnDB; 
    }

}

?>