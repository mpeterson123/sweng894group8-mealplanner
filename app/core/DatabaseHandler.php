<?php
namespace Base\Core;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Handle database connection
 */
class DatabaseHandler
{
    private $host ,$dbName,$user,$pass,$charset,$db;
    private static $instance = NULL;

    /**
     * Private constructor to prevent outside use
     */
    private function __construct(){
      $this->host = getenv("HTTP_dbHost");
      $this->dbName   = getenv("HTTP_dbName");
      $this->user = getenv("HTTP_dbUser");
      $this->pass = getenv("HTTP_dbPass");
      $this->charset = 'utf8';
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
