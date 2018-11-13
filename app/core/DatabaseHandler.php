<?php
namespace Base\Core;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Handle database connection
 */
class DatabaseHandler
{
    private $host;
    private $dbName;
    private $user;
    private $pass;
    private $charset;

    private static $instance = NULL;
    private $db;

    /**
     * Private constructor to prevent outside use
     */
    private function __construct(){
        require_once __DIR__.'/../../.env.php';

        $this->host    = $ENV_HOST;
        $this->dbName  = $ENV_DBNAME;
        $this->user    = $ENV_USER;
        $this->pass    = $ENV_PASS;
        $this->charset = $ENV_CHARSET;
    }

    /**
     * Return an instance of itself, creating it if necessary
     * @return Base\Core\DatabaseHandler Instance of itself
     */
    public static function getInstance(){
        if(!self::$instance)
        {
            self::$instance = new DatabaseHandler();
            self::$instance->connect();
        }
        return self::$instance;
    }

    /**
     * Connect to the database
     * @return boolean Whether connection was successful
     */
    private function connect()
    {
        $this->db = new \mysqli($this->host, $this->user, $this->pass, $this->dbName);

        if($this->db->connect_errno > 0){
            return false;
        };
        return true;
    }

    /**
     * Return instance of database connection object
     * @return [type] [description]
     */
    public function getDB(){
        return $this->db;
    }

    /**
     * Disconnect from database
     * @return [type] [description]
     */
    public function disconnect()
    {
        $this->db->close();
    }

    public function __destruct()
    {
        if($this->db && $this->db->ping()){
            $this->disconnect();
        };
    }
}
