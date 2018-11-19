<?php
namespace Base\Helpers;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Logs server errors
 */
class Log{

    private $db;

    /**
    * Instantiate mailer
    */
    public function __construct($db) {
      $this->db = $db->getDB();
    }

    public function add($userId, $type, $detail = ''){
      $query = $this->db->prepare('INSERT INTO log
              (timestamp,type,user,detail)
              VALUES(?,?,?,?)');
      @$query->bind_param("ssss",
          date('Y-m-d H:i:s'),
          $type,
          $userId,
          $detail
      );
      $query->execute();
    }

}
?>
