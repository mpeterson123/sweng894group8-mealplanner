<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Factories\CategoryFactory;


class CategoryRepository extends Repository {
    private $db,
        $categoryFactory;

    public function __construct($db, $categoryFactory){
        $this->db = $db;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Search for a category using its id
     * @param  string $id   the category's id
     * @return array        category's details
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM categories WHERE id = ? ORDER BY name');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $categoryRow = $result->fetch_assoc();

        $category = $this->categoryFactory->make($categoryRow);

        return $category;
    }

    /**
     * Get all categories added by a user
     * @return array Associative array of categories
     */
    public function all(){
        $categoryRows = $this->db->query('SELECT * FROM categories')->fetch_all(MYSQLI_ASSOC);

        $collection = array();
        foreach($categoryRows as $categoryRow){
            $collection[] = $this->categoryFactory->make($categoryRow);
        }
        return $collection;
    }

    // /**
    //  * Get all categories added by a user
    //  * @return array Associative array of food items
    //  */
    // public function allForUser($user){
    //     $query = $this->db->prepare('SELECT * FROM categories WHERE user_id = ? ORDER BY name');
    //     $query->bind_param("s", $user->getId());
    //     $query->execute();
    //
    //     $result = $query->get_result();
    //     return $result->fetch_all(MYSQLI_ASSOC);
    // }
}
