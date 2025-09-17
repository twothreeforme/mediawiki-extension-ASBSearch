<?php

use Wikimedia\Rdbms\DatabaseFactory;

class DatabaseConnection {
    private $dbServer;
    private $dbUsername;
    private $dbPassword;

    public function __construct() {
        global $wgDBserver;
        global $wgDBuser;
        global $wgDBpassword;

        $this->dbServer = $wgDBserver;
        $this->dbUsername = $wgDBuser;
        $this->dbPassword = $wgDBpassword;
    }

    private function getDatabaseFactory(mixed $database = null): DatabaseMysqli{
        return ( new DatabaseFactory() )->create( 'mysql', [
                'host' => $this->dbServer,
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
