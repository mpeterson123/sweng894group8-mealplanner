<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
//use PHPUnit\DbUnit\TestCaseTrait;
use Base\Repositories\IngredientRepository;
use Base\Models\Unit;
use Base\Models\Quantity;
use Base\Models\FoodItem;
use Base\Models\Category;
use Base\Core\DatabaseHandler;
use Base\Models\Ingredient;
use Base\Factories\IngredientFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;

class IngredientRepositoryTest extends TestCase {
//  use TestCaseTrait;

  // Variables to be reused
  private $expectedIngredientArray = Array(),
    $foodUnit,
    $category,
    $food,
    $ingrUnit,
    $quantity,
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
    $this->foodUnit->setId(6);
    $this->foodUnit->setName('liter(s)');
    $this->foodUnit->setAbbreviation('L');
    $this->foodUnit->setBaseUnit('mL');
    $this->foodUnit->setBaseEqv(1000.00);

    $this->category = new Category();
    $this->category->setId(3);
    $this->category->setName('Dairy');

    $this->food = new FoodItem();
    $this->food->setId(5);
    $this->food->setName('Milk');
    $this->food->setStock(2.0);
    $this->food->setUnit($this->foodUnit);
    $this->food->setCategory($this->category);
    $this->food->setUnitsInContainer(1.89);
    $this->food->setContainerCost(3.06);
    $this->food->setUnitCost(4.39);

    $this->ingrUnit = new Unit();
    $this->ingrUnit->setId(5);
    $this->ingrUnit->setName('milliliter(s)');
    $this->ingrUnit->setAbbreviation('mL');
    $this->ingrUnit->setBaseUnit('mL');
    $this->ingrUnit->setBaseEqv(1.00);

    $this->quantity= new Quantity('2.0', $this->ingrUnit);

    $this->expectedIngredientArray[] = new Ingredient($this->food, $this->quantity, 1, $this->ingrUnit);

    $this->foodUnit->setId(2);
    $this->foodUnit->setName('piece(s)');
    $this->foodUnit->setAbbreviation('pc');
    $this->foodUnit->setBaseUnit('pc');
    $this->foodUnit->setBaseEqv(1.00);

    $this->category->setId(7);
    $this->category->setName('Miscellaneous');

    $this->food->setId(17);
    $this->food->setName('sem');
    $this->food->setStock(0.0);
    $this->food->setUnit($this->foodUnit);
    $this->food->setCategory($this->category);
    $this->food->setUnitsInContainer(1.00);
    $this->food->setContainerCost(1.00);
    $this->food->setUnitCost(4.74);

    $this->ingrUnit->setId(2);
    $this->ingrUnit->setName('piece(s)');
    $this->ingrUnit->setAbbreviation('pc');
    $this->ingrUnit->setBaseUnit('pc');
    $this->ingrUnit->setBaseEqv(1.00);

    $this->quantity= new Quantity('1.0', $this->foodUnit);

    $this->expectedIngredientArray[] = new Ingredient($this->food, $this->quantity, 1, $this->foodUnit);

    $this->host = 'localhost';
    $this->dbName   = 'capstone';
    $this->user = 'capstone';
    $this->pass = 'CmklPrew!';
    $this->charset = 'utf8';

    //private static $instance = NULL;
    //private $db;


    $this->dbh = DatabaseHandler::getInstance();

    $this->db = new \mysqli($this->host, $this->user, $this->pass,$this->dbName);
    $this->db->autocommit(FALSE);

    $this->ingredientRepository = new IngredientRepository($this->db);


    // TODO Use dependency injection
    $categoryFactory = new CategoryFactory($this->db);
    $categoryRepository = new CategoryRepository($this->db, $categoryFactory);

    $unitFactory = new UnitFactory($this->db);
    $unitRepository = new UnitRepository($this->db, $unitFactory);

    $foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
    $foodItemRepository = new FoodItemRepository($this->db, $foodItemFactory);

    $this->ingredientFactory = new IngredientFactory($this->db, $foodItemRepository);
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

    unset($this->expectedIngredientArray);
    unset($this->dbh);
    unset($this->ingredientRepository);
    unset($this->ingredientFactory);

  }

  /**
  * Create DB Connection
  * @return PHPUnit\DbUnit\Database\Connection
  */
  /*
  public function getConnection() {

    $pdo = new PDO('');
    return $this->createDefaultDBConnection($pdo, '');

  }
*/
    /**
    * Get the DatSet
    * @return PHPUnit\DBunit\DataSet\IDataSet
    */
    /*
    public function getDataSet() {
      return $this->createFlatXMLDataSet(dirname(__FILE__).'\_filename.xml');
    }
*/

    public function testInsert(){
      $this->ingredientRepository->insert($this->expectedIngredientArray[0]);

      $id = $this->expectedIngredientArray[0]->getId();

      $query = "SELECT * FROM ingredients WHERE id = $id";
      $result = mysqli_query($this->db, $query)->fetch_assoc();

      $actualIngredientArray = array("id" => $result['id'],
                              "foodid" => $result['foodid'],
                              "quantity" => $result['quantity'],
                              "recipeid" => $result['recipeid'],
                              "unit_id" => $result['unit_id']);

      $actualIngredient = $this->ingredientFactory->make($actualIngredientArray);

      //$ingredReturned = $this->ingredientFactory->make($result->fetch_assoc());
      $this->assertEquals($this->expectedIngredientArray[0], $actualIngredient, '');

    }

    public function testFind(){

      //Insert an ingredient
      $query = $this->db
          ->prepare('INSERT INTO ingredients
              (foodid, recipeid, quantity, unit_id)
              VALUES (?, ?, ?, ?)
          ');

      // @ operator to suppress bind_param asking for variables by reference
      // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
      @$query->bind_param("iidi",
          $this->expectedIngredientArray[0]->getFood()->getId(), //->getId(),
          $this->expectedIngredientArray[0]->getRecipeId(),
          $this->expectedIngredientArray[0]->getQuantity()->getValue(), //->getValue()
          $this->expectedIngredientArray[0]->getUnit()->getId()
      );

      $bool = $query->execute();
      if($bool) {
        $this->expectedIngredientArray[0]->setId($query->insert_id);
      }

      //Find
      $ingredReturned = $this->ingredientRepository->find($this->expectedIngredientArray[0]->getId());

      $this->assertEquals($this->expectedIngredientArray[0], $ingredReturned, '');
    }

    public function testSaveNewIngredient() {
      $this->ingredientRepository->save($this->expectedIngredientArray[0]);

      $id = $this->expectedIngredientArray[0]->getId();

      $query = "SELECT * FROM ingredients WHERE id = $id";
      $result = mysqli_query($this->db, $query);

      $ingredReturned = $this->ingredientFactory->make($result->fetch_assoc());
      $this->assertEquals($this->expectedIngredientArray[0], $ingredReturned, '');

      //$returnedId = $ingredReturned->GetId();
    }

    public function testAllForRecipe() {

      //Insert 2 ingredients into the Database
      $this->ingredientRepository->save($this->expectedIngredientArray[0]);
      $this->ingredientRepository->save($this->expectedIngredientArray[1]);

      $ingredients = $this->ingredientRepository->allForRecipe(1);

      $this->assertEquals($this->expectedIngredientArray, $ingredients);

    }

    public function testUpdateIngredient() {
      //Insert an ingredient
      $query = $this->db
          ->prepare('INSERT INTO ingredients
              (foodid, recipeid, quantity, unit_id)
              VALUES (?, ?, ?, ?)
          ');

      // @ operator to suppress bind_param asking for variables by reference
      // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
      @$query->bind_param("iidi",
          $this->expectedIngredientArray[0]->getFood()->getId(), //->getId(),
          $this->expectedIngredientArray[0]->getRecipeId(),
          $this->expectedIngredientArray[0]->getQuantity()->getValue(), //->getValue()
          $this->expectedIngredientArray[0]->getUnit()->getId()
      );

      $bool = $query->execute();

      if($bool) {
        $this->expectedIngredientArray[0]->setId($query->insert_id);
      }

      $this->expectedIngredientArray[0]->getQuantity()->setValue(5.0);

      $this->ingredientRepository->update($this->expectedIngredientArray[0]);

      //Find the ingredient
      $id = $this->expectedIngredientArray[0]->getId();
      $query = $this->db->prepare('SELECT * FROM ingredients WHERE id = ?');
      $query->bind_param("s", $id);
      $query->execute();
      $result = $query->get_result();
      $ingredientRow = $result->fetch_assoc();

      $actualIngredient = $this->ingredientFactory->make($ingredientRow);

      $this->assertEquals($this->expectedIngredientArray[0], $actualIngredient);

    }

    public function testSaveExistingIngredient() {
      //Insert an ingredient
      $query = $this->db
          ->prepare('INSERT INTO ingredients
              (foodid, recipeid, quantity, unit_id)
              VALUES (?, ?, ?, ?)
          ');

      // @ operator to suppress bind_param asking for variables by reference
      // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
      @$query->bind_param("iidi",
          $this->expectedIngredientArray[0]->getFood()->getId(), //->getId(),
          $this->expectedIngredientArray[0]->getRecipeId(),
          $this->expectedIngredientArray[0]->getQuantity()->getValue(), //->getValue()
          $this->expectedIngredientArray[0]->getUnit()->getId()
      );

      $bool = $query->execute();

      if($bool) {
        $this->expectedIngredientArray[0]->setId($query->insert_id);
      }

      $this->expectedIngredientArray[0]->getQuantity()->setValue(5.0);

      $this->ingredientRepository->save($this->expectedIngredientArray[0]);

      //Find the ingredient
      $id = $this->expectedIngredientArray[0]->getId();
      $query = $this->db->prepare('SELECT * FROM ingredients WHERE id = ?');
      $query->bind_param("s", $id);
      $query->execute();
      $result = $query->get_result();
      $ingredientRow = $result->fetch_assoc();

      $actualIngredient = $this->ingredientFactory->make($ingredientRow);

      $this->assertEquals($this->expectedIngredientArray[0], $actualIngredient);

    }

    public function testRemoveIngredient() {

      //Insert an ingredient into the Database
      //If it was successful call remove
      if($this->insert() ) {
        $this->ingredientRepository->remove($this->expectedIngredientArray[0]->getId());
      }

      //Confirm it was removed
      $actualIngredient = $this->find($this->expectedIngredientArray[0]->getId());

      $this->assertEquals(null, $actualIngredient);

    }

    private function insert() {
      //Insert an ingredient
      $query = $this->db
          ->prepare('INSERT INTO ingredients
              (foodid, recipeid, quantity, unit_id)
              VALUES (?, ?, ?, ?)
          ');

      // @ operator to suppress bind_param asking for variables by reference
      // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
      @$query->bind_param("iidi",
          $this->expectedIngredientArray[0]->getFood()->getId(), //->getId(),
          $this->expectedIngredientArray[0]->getRecipeId(),
          $this->expectedIngredientArray[0]->getQuantity()->getValue(), //->getValue()
          $this->expectedIngredientArray[0]->getUnit()->getId()
      );

      $bool = $query->execute();

      if($bool) {
        $this->expectedIngredientArray[0]->setId($query->insert_id);
      }

      return $bool;
    }

    private function find() {
      $id = $this->expectedIngredientArray[0]->getId();
      $query = $this->db->prepare('SELECT * FROM ingredients WHERE id = ?');
      $query->bind_param("s", $id);
      $query->execute();
      $result = $query->get_result();
      $ingredientRow = $result->fetch_assoc();

      if($ingredientRow) {
        $actIngredient = (new IngredientFactory($this->db))->make($ingredientRow);
      }
      else {
        $actIngredient = null;
      }

      return $actIngredient;
    }
}
