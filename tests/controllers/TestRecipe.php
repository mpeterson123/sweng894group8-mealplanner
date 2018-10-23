<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../app/core/DatabaseHandler.php';
require_once __DIR__.'/../../app/controllers/Recipes.php';


use PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client;
use Base\Core\DatabaseHandler;
use Base\Controllers\Recipes;


class TestRecipes extends TestCase {
    // Variables to be reused
    private $dbh;
    private $httpClient;
    private $recipeController;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        @session_start();
        $_SESSION['id'] = 3;
        $_SESSION['username'] = 'mpeterson';

        //echo "\n-------------------------sess id 1: ".$_SESSION['id'];
        $sessionId = session_id();
        session_write_close();
        //echo "\nsession id is: ".$sessionId;

        $this->dbh = DatabaseHandler::getInstance();
        $this->recipeController = new Recipes($this->dbh);

        $domain = 'localhost/';

        $cookie = new \GuzzleHttp\Cookie\SetCookie();
        $cookie->setName('PHPSESSID');
        $cookie->setValue($sessionId);
        $cookie->setDomain($domain);

        $cookieJar = new \GuzzleHttp\Cookie\CookieJar(
            false,
            array(
                $cookie,
            )
        );

        $this->httpClient = new Client(['base_uri' => 'localhost/', 'cookies'  => $cookieJar]);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
        unset($this->recipeController);
        unset($this->httpClient);
    }

    /**
     * Passing sample test method
     */
    public function testIndex(){
        $res = $this->httpClient->request('GET', 'Recipes/index');
        $this->assertEquals($res->getStatusCode(), 200);

        //echo $res->getBody();
    }

    public function testStore() {
      $res = $this->httpClient->request('POST', 'localhost/Recipes/store', [
          'form_params' => [
              'name' => 'Name',
              'description' => 'Desc',
              'servings' => 2,
              'source' => 'Source',
              'notes' => 'Notes',
              'ingredient1' => [
                  'foodid' => 5,
                  'quantity' => 2.0,
                  'unitid' => 8
                ],
              'ingredient2' => [
                'foodid' => 1,
                'quantity' => 1.0,
                'unitid' => 5
              ],
              'ingredient3' => [
                'foodid' => 2,
                'quantity' => 3.0,
                'unitid' => 2
              ],
              'user_id' => $_SESSION['id'] = 3
          ]
      ]);
      $this->assertEquals($res->getStatusCode(), 200);

      //echo $res->getBody();
    }

}
