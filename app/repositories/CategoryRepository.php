<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Category;
use Base\Repositories\Repository;


class CategoryRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
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

        $category = new Category();
        $category->setId($categoryRow['id']);
        $category->setName($categoryRow['name']);

        return $category;
    }

    /**
     * Get all categories added by a user
     * @return array Associative array of categories
     */
    public function all(){
        return $this->db->query('SELECT * FROM categories')->fetch_all(MYSQLI_ASSOC);
    }

    // /**
    //  * Get all categories added by a user
    //  * @return array Associative array of food items
    //  */
    // public function allForUser($userId){
    //     $query = $this->db->prepare('SELECT * FROM categories WHERE user_id = ? ORDER BY name');
    //     $query->bind_param("s", $userId);
    //     $query->execute();
    //
    //     $result = $query->get_result();
    //     return $result->fetch_all(MYSQLI_ASSOC);
    // }

    public function save($object){}
    public function remove($object){}
    protected function insert($object){}
    protected function update($object){}
}
