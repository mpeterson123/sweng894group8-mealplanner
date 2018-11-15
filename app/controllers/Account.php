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
use Base\Repositories\UserRepository;
use Base\Repositories\HouseholdRepository;
use Base\Helpers\Email;
use Base\Helpers\Log;
use Base\Models\User;
use Base\Factories\UserFactory;
use Base\Factories\HouseholdFactory;

class Account extends Controller{
	protected $dbh,
        $session,
				$request,
				$log;

	private $userRepository,
		$userFactory;

	public function __construct(DatabaseHandler $dbh, Session $session, $request){
		$this->dbh = $dbh;
		$this->session = $session;
		$this->request = $request;
		$this->log = new Log($dbh);

        // TODO Use dependency injection
		$householdFactory = new HouseholdFactory();
		$householdRepository = new HouseholdRepository($this->dbh->getDB(), $householdFactory);

		$this->userFactory = new UserFactory($householdRepository);
		$this->userRepository = new UserRepository($this->dbh->getDB(), $this->userFactory);
  	}

	/**
	 * Store a new user record in the DB
	 */
	public function store():void{
		if(isset($this->request['reg_username'])){
			$error = array();

			$input = $this->request;

			$this->validateRegistrationInput($input, 'create');

			$input['password'] = $this->pass_hash($input['password']);
			$user = $this->userFactory->make($input);

			$email = new Email();
			$email->sendEmailAddrConfirm($user->getEmail());
			$this->userRepository->save($user);

			$this->session->flashMessage('success', 'Your account has been created. A confirmation link has been sent to you. Please confirm your email address to activate your account.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
	}

	/**
	 * Show user registration page
	 */
	public function create():void{
			$this->view('auth/register');
	}

	/**
	 * Log out a user and redirect to login page
	 * @return [type] [description]
	 */
	public function logout():void{
		$this->session->remove('user');
		$this->session->remove('username');
		$this->session->remove('id');
		session_destroy();
		Redirect::toControllerMethod('Account', 'showLogin');
	}

	/**
	 * Hash a password
	 * @param  string $password Password to hash
	 * @return string           Hashed password
	 */
	public function pass_hash($password):string{
		for($i = 0; $i < 1000; $i++) $password = hash('sha256',trim(addslashes($password)));
		return $password;
	}

	/**
	 * Confirm a user's email address
	 * @param string $email User's email address
	 * @param string $code  Email confirmation code
	 */
	public function confirmEmail($email,$code):void{
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt) != $code)){
			$this->log->add($user, 'Error', 'Confirm Email - Code is invalid');
			$this->session->flashMessage('danger', 'This link is invalid. Please try again.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}

		// set as confirmed in the db
		$this->userRepository->confirmEmail($email);

		// Redirect to login
		$this->session->flashMessage('success', 'Your email address has been confirmed. Please log in.');
		Redirect::toControllerMethod('Account', 'showLogin');
	}

	/**
	 * Process password reset form and send password set code to user
	 */
	public function forgotPassword():void{
		// Get temp pass code and email
		$code = urlencode(hash('sha256',rand(1000000000,10000000000)));
		$email = addslashes(trim($this->request['email']));

		// Check if email exists in db
		$u = $this->userRepository->get('email',$email);

		if($email == ''){
			$this->log->add($user, 'Error', 'Forgot Password - Email Address not supplied');
			$this->session->flashMessage('success', 'No email has been supplied.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
		else if(!$u){
			$this->session->flashMessage('success', 'Check your email for instructions to reset your password.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
		else {
			$this->userRepository->setPassTemp($email,$code);
			// send Email
			$emailHandler = new Email();
			$emailHandler->sendPasswordReset($email,$code);

			// Redirect to login
			$this->session->flashMessage('success', 'Check your email for instructions to reset your password.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
	}

	/**
	 * Process password resetting from email
	 * @param string $email User's email address
	 * @param string $code  Email confirmation code
	 */
	public function resetPassword($email,$code){
		// Check if email exists in db
		$u = $this->userRepository->get('email',$email);
		if(!$u){
			// Email doesn't exist
			$this->log->add($user, 'Error', 'Reset Password - Email Address doens\'t exist');
			$this->session->flashMessage('danger', 'An error has occured. Please try again.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
		// Check if reset code has been set
		else if($u['passTemp'] == ''){
			$this->log->add($user, 'Error', 'Reset Password - Reset Code not sent');
			$this->session->flashMessage('danger', 'An error has occured. Please try again.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
		// Check if code matches db
		else if($u['passTemp'] != $code){
			$this->log->add($user, 'Error', 'Reset Password - Reset Code doesn\'t match');
			$this->session->flashMessage('danger', 'An error has occured. Please try again.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}
		else{
			// Reset page has been submitted
			if(isset($this->request['rst_password'])){
				// Reset password
				$this->userRepository->setValue('password',$this->pass_hash($this->request['rst_password']),'email',$email);
				// Reset temp pass
				// TODO Check this: why does it use email?
				$this->userRepository->setValue('passTemp','','email',$email);
				// Redirect to login
				$this->session->flashMessage('danger', 'Password has been reset. Please login.');
				Redirect::toControllerMethod('Account', 'showLogin');
			}
			else{
				// Direct to reset pass view
				$this->view('auth/resetPassword',['email'=>$email,'code'=>$code]);
			}
		}
	}

	/**
	 * Show account settings page
	 */
	public function settings():void{
		$this->view('auth/settings');
	}

	/**
	 * Update a user's information (personal, password)
	 */
	public function update():void{
		$user = $this->session->get('user');

		$input = $this->request;
		$this->validateEditInput($input, 'settings');

		// Handle password update
		if(isset($this->request['password']) && isset($this->request['confirmPassword'])){
			$user->setPassword($this->pass_hash($input['password']));
		}
		// Handle name updated
		if($input['firstName'].' '.$input['lastName'] != $user->getFirstName().' '.$user->getLastName()){
			$user->setFirstname($input['firstName']);
			$user->setLastName($input['lastName']);
		}

		$this->userRepository->save($user);

		// Update user in the session
		$this->session->add('user', $user);

		// Handle email updated
		if($input['email'] != $user->getEmail()){
			// send Email
			$emailHandler = new Email();
			$emailHandler->sendEmailUpdateConfirm($input['email'],$user->getEmail());
			$this->session->flashMessage('success', 'A confirmation email has been sent to '.$input['email'].'. Please confirm to update.');
			Redirect::toControllerMethod('Account', 'settings');
			return;
		}

		$this->session->flashMessage('success', 'Your account has been updated. Return to <a href="/Account/dashboard/">Dashboard</a>.');
		Redirect::toControllerMethod('Account', 'settings');

	}

	/**
	 * Confirm email change from new email link
	 * @param string $email     User's new email address
	 * @param string $old_email User's previous email address
	 * @param string $code      Confirmation code
	 */
	public function confirmNewEmail($email,$old_email,$code):void{
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt.$old_email) != $code)){
			$this->log->add($user, 'Error', 'Confirm Email - Link is invalid');
			$this->session->flashMessage('danger', 'Your email confirmation link is invalid.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}

		// TODO change entire user object, not just one value
		// update in the db
		$this->userRepository->setValue('email',$email,'email',$old_email);

		// Redirect to login
		$this->session->flashMessage('success', 'Your email address has been updated.');
		Redirect::toControllerMethod('Account', 'dashboard');

	}

	/**
	 * Delete a user's Account
	 */
	public function delete():void{
		$user = $this->session->get('user');

		$this->log->add($user, 'Delete', 'A user account ('.$user->getUsername().') has been deleted');

		$this->userRepository->remove($user);
		// Remove everything from session
		$this->session->flush();

		$this->session->flashMessage('success', 'Your account has been deleted.');
		Redirect::toControllerMethod('Account', 'showLogin');

	}

	/**
	 * Show user's dashboard if s/he is logged in
	 */
	public function dashboard():void{
		$user = $this->session->get('user');

		if(!$user){
			Redirect::toControllerMethod('Account', 'showLogin');
			return;
		}

		if(empty($user->getHouseholds())){
			$this->view('/auth/newHousehold');
			return;
		}
		$this->view('/dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName(), 'profilePic' => $user->getProfilePic()]);
	}

	/**
	 * Show login page
	 */
	public function showLogin():void{
		$user = $this->session->get('user');

		// Active session
		if($user){
			Redirect::toControllerMethod('Account', 'dashboard');
			return;
		}
		$this->view('/auth/login');
	}

	/**
	 * Validate login credentials and log in user
	 */
	public function logInUser():void{
		$user = $this->session->get('user');
		$input = $this->request;

		// Redirect to dashboard if user is already logged in
		if($user){
			Redirect::toControllerMethod('Account', 'dashboard');
			return;
		}

		// Validate input
		$this->validateLoginInput($input, 'showLogin');

		// Hash password
		$password = $this->pass_hash($input['login_password']);

		// Check credentials
		$user = $this->userRepository->checkUser($input['login_username'],$password);

		if(!$user) {
			// If credentials are not valid, set error message
			$message = 'Incorrect username or password.';
			$this->log->add($user, 'Error', 'Login - '.$message);
		}
		else if(!$user->getActivated()){
			// If credentials are valid, but user is inactive, set error message
			$message = 'Please confirm your email before you can log in.';
		}
		else {
			// If credentials are valid and user is active, log in user
			// $this->session->add('username', $user->getUsername());
			// $this->session->add('id', $user->getId());
			$this->session->add('user', $user);

			$this->log->add($user, 'Login');

			Redirect::toControllerMethod('Account', 'dashboard');
			return;
		}

		$this->session->flashMessage('danger', $message);
		Redirect::toControllerMethod('Account', 'showLogin');
	}


	/**
     * Validates user input from login form
     * @param array $input  	Login form input
     * @param string $method 	Method to redirect to
     * @param array $params 	Parameters for the redirection method
     */
    private function validateLoginInput($input, $method, $params = NULL):void {
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $rules = [
            'required' => [
				['login_username'],
                ['login_password'],
            ],
            'slug' => [
                ['login_username'],
            ],
			'lengthMin' => [
				['login_username', 5],
		        ['login_password', 6]
		    ],
			'lengthMax' => [
		        ['login_password', 30]
		    ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
            'login_username' => 'Username',
            'login_password' => 'Password'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Account', $method, $params);
            return;
        }
    }

	/**
     * Validates user input from registration form
     * @param array $input  	Login form input
     * @param string $method 	Method to redirect to
     * @param array $params 	Parameters for the redirection method
     */
    private function validateRegistrationInput($input, $method, $params = NULL):void {
        $this->session->flashOldInput($input);

		$nameRegex = '/^([a-z]|\s|-|[.])+$/i';

        // Validate input
        $validator = new Validator($input);
        $rules = [
            'required' => [
				['reg_username'],
				['reg_namefirst'],
				['reg_namelast'],
				['reg_email'],
				['reg_password'],
				['reg_password2']
            ],
            'equals' => [
				['reg_password', 'reg_password2'],
                ['reg_password2', 'reg_password']
            ],
			'email' => [
                ['reg_email'],
            ],
			'regex' => [
				['reg_namefirst', $nameRegex],
                ['reg_namelast', $nameRegex]
            ],
			'slug' => [
                ['reg_username'],
            ],
			'lengthMin' => [
				['reg_username', 5],
				['reg_namefirst', 2],
				['reg_namelast', 2],
				['reg_email', 5],
				['reg_password', 6],
		        ['reg_password2', 6]
		    ],
			'lengthMax' => [
				['reg_username', 32],
				['reg_namefirst', 32],
				['reg_namelast', 32],
				['reg_email', 64],
				['reg_password', 30],
		        ['reg_password2', 30]
		    ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
			'reg_username' => 'Username',
			'reg_namefirst' => 'First Name',
			'reg_namelast' => 'Last Name',
			'reg_email' => 'Email Address',
			'reg_password' => 'Password',
			'reg_password2' => 'Password Confirmation'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Account', $method, $params);
            return;
        }
    }


	/**
     * Validates user input from account settings form
     * @param array $input  	Login form input
     * @param string $method 	Method to redirect to
     * @param array $params 	Parameters for the redirection method
     */
    private function validateEditInput($input, $method, $params = NULL):void {
        $this->session->flashOldInput($input);

		$nameRegex = '/^([a-z]|\s|-|[.])+$/i';

        // Validate input
        $validator = new Validator($input);
        $rules = [
            'required' => [
				['firstName'],
				['lastName'],
				['email'],
            ],
            'equals' => [
				['password', 'confirmPassword'],
                ['confirmPassword', 'password']
            ],
			'email' => [
                ['email'],
            ],
			'regex' => [
				['firstName', $nameRegex],
                ['lastName', $nameRegex]
            ],
			'lengthMin' => [
				['firstName', 2],
				['lastName', 2],
				['email', 5],
				['password', 6],
		        ['confirmPassword', 6]
		    ],
			'lengthMax' => [
				['firstName', 32],
				['lastName', 32],
				['email', 64],
				['password', 30],
		        ['confirmPassword', 30]
		    ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
			'reg_username' => 'Username',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'email' => 'Email Address',
			'password' => 'Password',
			'confirmPassword' => 'Password Confirmation'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Account', $method, $params);
            return;
        }
    }



	/**
	 * Change user's profile picture
	 */
	public function changePicture():void{
		// show form
		if(($this->request['submit'] ?? NULL) == ''){
			$this->view('/auth/changePic');
		}
		// upload
		else{
			$user = $this->session->get('user');

			$target_dir = __DIR__.'/../../public/images/users/';
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$newFilename = $this->pass_hash($user->getId()).'.'.$imageFileType;
			$uploadOk = 1;
			$errors = array('fileToUpload' => array());
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			    if($check !== false) {
			        $uploadOk = 1;
			    } else {
			        $errors['fileToUpload'][] = "File must be an image.";
			        $uploadOk = 0;
			    }
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
			    $errors['fileToUpload'][] = "File must be 5 MB or smaller.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    $errors['fileToUpload'][] = "Only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$this->log->add($user, 'Error', 'Upload Picture - '.$errors);
				$errorMessage = Format::validatorErrors($errors);
				$this->session->flashMessage('danger', $errorMessage);
				$this->view('/auth/changePic');
			}
			// if everything is ok, try to upload file
			else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir .$newFilename)) {
					$this->userRepository->setProfilePicture($user,$newFilename);
					$updatedUser = $this->userRepository->find($user->getUsername());
					$this->session->add('user',$updatedUser);

					$this->session->flashMessage('success', 'Your profile picture was updated.');
					Redirect::toControllerMethod('Account', 'Dashboard');
			    } else {
					$this->session->flashMessage('danger', 'Uh oh, an error occured uploading your profile picture.');
					$this->view('/auth/changePic');
			    }
			}
		}
	}


}
?>
