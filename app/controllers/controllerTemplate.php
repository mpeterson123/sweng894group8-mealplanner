<?php
namespace Base\Controllers;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';


//////////////////////
// Standard classes //
//////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
use Base\Helpers\Redirect;
use Base\Helpers\Format;
use \Valitron\Validator;

///////////////////////////
// File-specific classes //
///////////////////////////

// REPLACE with use statements for factories, repos, and models as needed


/**
 * Objects users can add and keep track of
 */
class Objects extends Controller {

    protected $dbh,
        $session,
        $request;

    private $objectRepository,
        $objectFactory;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
        $this->dbh = $dbh;
        $this->session = $session;
        $this->request = $request;

        // TODO Use dependency injection
        $this->objectRepository = new ObjectRepository($this->dbh->getDB());
        $this->objectFactory = new ObjectFactory(/*dependecies*/);
    }

    /**
     * Lists all objects belonging to a user
     */
    public function index():void{
        // Code fetching objects

        $this->view('objects/index', compact('objects'));
    }

    /**
     * Lets users edit a(n) object
     * @param string $id Object's id
     */
    public function edit($id):void{
        // Some code here (if needed)

        // Get object details
        $object = $this->objectRepository->find($id);

        $this->view('object/edit', compact('object' /*, whatever variables need to be sent to the view*/ ));
    }

    /**
     * Lets users create a(n) object
     */
    public function create():void{
        // Some code here (if needed)

        $this->view('object/create', compact(/*whatever variables need to be sent to the view*/));
    }

    /**
     * Stores a new object in the DB
     */
    public function store():void{

        $input = $this->request;

        (new Session())->flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make object
        $object = $this->objectFactory->make($input);

        // Save to DB
        $this->objectRepository->save($object);

        // Flash success message and flush old input
        (new Session())->flashMessage('success', ucfirst($object->getName()).' was added to your list.');
        (new Session())->flushOldInput();

        // Redirect to index after creating
        Redirect::toControllerMethod('Objects', 'index');
        return;
    }

    /**
     * Deletes a(n) object
     * @param string $id Object's id
     */
    public function delete($id):void{
        $object = $this->objectRepository->find($id);

        // If object doesn't exist, load 404 error page
        if(!$object){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $this->checkObjectBelongsToUser($id);

        $this->objectRepository->remove($id);

        (new Session())->flashMessage('success', $object->getName().' was removed from your items.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('Objects', 'index');
        return;
    }

    /**
     * Updates a(n) object in the debug
     * @param string $id Object's id
     */
    public function update($id):void{
        $object = $this->objectRepository->find($id);
        $this->checkObjectBelongsToUser($id);

        $this->validateInput($this->request, 'edit', [$id]);

        $this->objectRepository->save($object);

        // Flash success message
        (new Session())->flashMessage('success', ucfirst($object->getName()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('Objects', 'edit', array('objectId' => $object->getId()));
        return;
    }

    /**
     * Check if a(n) objects belongs to the current user
     * @param string $id Object's id
     */
    public function checkObjectBelongsToUser($id):void{
        $user = (new Session())->get('user');

        // If object doesn't belong to user, show forbidden error
        if(!$this->objectRepository->objectBelongsToUser($id, $user)){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    /**
     * Validates object input from user form
     * @param array $input  [description]
     * @param string $method Method to redirect to
     * @param array $params Parameters for the redirection method
     */
    private function validateInput($input, $method, $params = NULL):void{
        (new Session())->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            // SEE FoodItemController for example
        ];
        $validator->rules($rules);
        $validator->labels(array(
            // SEE FoodItemController for example
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            (new Session())->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Objects', $method, $params);
            return;
        }
    }
}
