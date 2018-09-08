<?php
namespace App\Core;

date_default_timezone_set('America/New_York');
/**
 * Handle database connection
 */
class DatabaseHandler
{
  private static $host = 'localhost';
  private static $db   = 'capstone';
  private static $user = 'capstone';
  private static $pass = 'CmklPrew!';
  private static $charset = 'utf8';

  private static $instance = NULL;
  private $mysqli;


    public static function getInstance(){
        if(!self::$instance)
        {
            self::$instance = new DatabaseHandler();
            self::$instance->connect();
        }
        return self::$instance;
    }

    private function connect()
    {
        $this->mysqli = new mysqli('localhost', self::$user, self::$pass, self::$db);

        if($db->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        };
    }

    public function getDB(){
        return $this->mysqli;
    }

    public function disconnect()
    {
        $this->mysqli->close();
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
