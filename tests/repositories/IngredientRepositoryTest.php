<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Base\Repositories\IngredientRepository;
use Base\Models\Unit;
use Base\Models\Quantity;
use Base\Models\FoodItem;
use Base\Models\Category;
use Base\Core\DatabaseHandler;
use Base\Models\Ingredient;
use Base\Factories\IngredientFactory;

class IngredientRepositoryTest extends TestCase {
    // Variables to be reused
    private $ingredient,
      $host,
      $dbName,
      $user,
      $pass,
      $charset,
      $db,
      $ingredientId;

      // Example: private $classYouAreTesting

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->foodUnit = new Unit();
      $this->foodUnit->setId(2);
      $this->foodUnit->setName('piece(s)');
      $this->category = new Category();
      $this->category->setId(1);
      $this->category->setName('Fruit');
      $this->food = new FoodItem(5, 'Ovaltine', 1, $this->foodUnit, $this->category, 1, 5, 5);
      $this->food->setId(5);
      $this->food->setName('Ovaltine');
      $this->food->setStock(0.0);
      $this->food->setUnit($this->foodUnit);
      $this->food->setCategory($this->category);
      $this->food->setUnitsInContainer(1);
      $this->food->setContainerCost(2);
      $this->food->setUnitCost(1);
      $this->ingrUnit = new Unit();
      $this->ingrUnit->setId(6);
      $this->ingrUnit->setName('liter(s)');
      $this->quantity= new Quantity('2.0', $this->ingrUnit);
      $this->ingredient = new Ingredient($this->food, $this->quantity, 1, $this->ingrUnit);
      //$this->ingredient->setFood(5);
      //$this->ingredient->setQuantity(2.0);
      //$this->ingredient->setRecipeId(1);
      //$this->ingredient->setUnit(8);

      $this->host = 'localhost';
      $this->dbName   = 'capstone';
      $this->user = 'capstone';
      $this->pass = 'CmklPrew!';
      $this->charset = 'utf8';

      //private static $instance = NULL;
      //private $db;


      $this->dbh = DatabaseHandler::getInstance();
      //$this->db = $this->dbh->getDB();

      $this->db = new \mysqli($this->host, $this->user, $this->pass,$this->dbName);
      $this->db->autocommit(FALSE);
    //  $this->dbh->getDB()->autocommit(FALSE);

      $this->ingredientRepository = new IngredientRepository($this->db);
      $this->ingredientFactory = new IngredientFactory($this->db);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->foodUnit);
      unset($this->category);
      unset($this->food);
      unset($this->ingrUnit);
      unset($this->quantity);

      $this->db->close();
      //$this->dbh->getDB()->close;

      unset($this->ingredient);
      unset($this->db);
      unset($this->dbh);
      unset($this->ingredientRepository);
      unset($this->ingredientFactory);

    }

    public function testInsert(){

      $this->ingredientRepository->insert($this->ingredient);

      $id = $this->ingredient->getId();

      $query = "SELECT * FROM ingredients WHERE id = $id";
      $result = mysqli_query($this->db, $query)->fetch_assoc();

      $ingredientArray = array("id" => $result['id'],
                              "foodid" => $result['foodid'],
                              "quantity" => $result['quantity'],
                              "recipeid" => $result['recipeid'],
                              "unit_id" => $result['unit_id']);

      $ingredReturned = $this->ingredientFactory->make($ingredientArray);

      //$ingredReturned = $this->ingredientFactory->make($result->fetch_assoc());
      $this->assertEquals($this->ingredient, $ingredReturned, '');

      //$returnedId = $ingredReturned->GetId();

    }

    public function testFind(){

      //$foodid = $this->ingredient->getFood()->getId();
      //$recipeid = $this->ingredient->getRecipeId();
      //$quantity = $this->ingredient->getQuantity()->getValue();
      //$unit = $this->ingredient->getUnit()->getId();

      //Insert an ingredient
      $query = $this->db
          ->prepare('INSERT INTO ingredients
              (foodid, recipeid, quantity, unit_id)
              VALUES (?, ?, ?, ?)
          ');

      // @ operator to suppress bind_param asking for variables by reference
      // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
      @$query->bind_param("iidi",
          $this->ingredient->getFood()->getId(), //->getId(),
          $this->ingredient->getRecipeId(),
          $this->ingredient->getQuantity()->getValue(), //->getValue()
          $this->ingredient->getUnit()->getId()
      );

      $bool = $query->execute();
      if($bool) {
        $this->ingredient->setId($query->insert_id);
      }

      //Find
      $ingredReturned = $this->ingredientRepository->find($this->ingredient->getId());

      $this->assertEquals($this->ingredient, $ingredReturned, '');
    }

    public function testSaveNewIngredient() {
      $this->ingredientRepository->save($this->ingredient);

      $id = $this->ingredient->getId();

      $query = "SELECT * FROM ingredients WHERE id = $id";
      $result = mysqli_query($this->db, $query);

      $ingredReturned = $this->ingredientFactory->make($result->fetch_assoc());
      $this->assertEquals($this->ingredient, $ingredReturned, '');

      //$returnedId = $ingredReturned->GetId();
    }

    public function testAllForRecipe() {

    }
}
