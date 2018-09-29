<?php
// namespace Base\Test;
//
// require_once __DIR__.'/../../vendor/autoload.php';
// require_once __DIR__.'/../../app/core/DatabaseHandler.php';
// require_once __DIR__.'/../../app/controllers/FoodItems.php';
//
//
// use PHPUnit\Framework\TestCase;
// use \GuzzleHttp\Client;
// use Base\Core\DatabaseHandler;
// use Base\Controllers\FoodItems;
//
//
// class TestFoodItems extends TestCase {
//     // Variables to be reused
//     private $dbh;
//     private $httpClient;
//     private $foodItemsController;
//
//     /**
//      * Create instances or whatever you need to reuse in several tests here
//      */
//     public function setUp(){
//         @session_start();
//         $_SESSION['id'] = 2;
//         $_SESSION['username'] = 'cperez';
//
//         echo "-------------------------sess id 1: ".$_SESSION['id'];
//         $sessionId = session_id();
//         session_write_close();
//         echo "session id is: ".$sessionId;
//
//         $this->dbh = DatabaseHandler::getInstance();
//         $this->foodItemsController = new FoodItems($this->dbh);
//
//         $domain = 'localhost/';
//
//         $cookie = new \GuzzleHttp\Cookie\SetCookie();
//         $cookie->setName('PHPSESSID');
//         $cookie->setValue($sessionId);
//         $cookie->setDomain($domain);
//
//         $cookieJar = new \GuzzleHttp\Cookie\CookieJar(
//             false,
//             array(
//                 $cookie,
//             )
//         );
//
//         $this->httpClient = new Client(['base_uri' => 'localhost/', 'cookies'  => $cookieJar]);
//     }
//
//     /**
//      * Unset any variables you've created
//      */
//     public function tearDown(){
//         unset($this->foodItemsController);
//         unset($this->httpClient);
//     }
//
//     /**
//      * Passing sample test method
//      */
//     public function testIndex(){
//         $res = $this->httpClient->request('GET', 'FoodItems/index');
//         $this->assertEquals($res->getStatusCode(), 200);
//
//         echo $res->getBody();
//     }
// }
