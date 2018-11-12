<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\UserFactory;
use Base\Models\User;
use Base\Models\Household;
use Base\Core\DatabaseHandler;
use Base\Repositories\HouseholdRepository;


class UserFactoryTest extends TestCase {
    // Variables to be reused
    private $userFactory,
        $householdRepositoryStub,
        $unitRepositoryStub;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        /////////////////////////////
        // Stub householdRepositoryStub //
        /////////////////////////////
        $this->householdRepositoryStub = $this
            ->createMock(HouseholdRepository::class);

        // Configure the stub.
        $householdStub = $this->createMock(Household::class);
        $householdsArray = array(
            $householdStub,
            $householdStub,
            $householdStub
        );
        $this->householdRepositoryStub->method('allForUser')
            ->will($this->returnValue($householdsArray));

        $this->householdRepositoryStub->method('find')
            ->will($this->returnValue($householdStub));

        /////////////////////
        // Create instance //
        /////////////////////
        $this->userFactory = new UserFactory(
            $this->householdRepositoryStub
        );
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->userFactory);
    }

    public function testCreateUserFactory(){
        $userFactory = new UserFactory($this->householdRepositoryStub);

    	$this->assertInstanceOf(
            'Base\Factories\UserFactory',
            $userFactory,
            'Object must be instance of UserFactory');
    }

    public function testMakeUserWithId(){
        $userArray = array(
            'id' => 1234,
            'username' => 'jsmith',
            'password' => 'b9c73a5ddae499143c1031d9a1ff0f34f90d9d346df83075583c56bcbc9a5675',
            'email' => 'john.smith@example.com',
            'joined' => '2018-01-01 12:00:00',
            'namefirst' => 'John',
            'namelast' => 'Smith',
            'activated' => 1,
            'passTemp' => NULL,
            'currHouseholdId' => 1,
            'profilePic' => 'hsdfyugds78fsdfuile4r83rudsgfjkf.png'
        );

        $user = $this->userFactory->make($userArray);
    	$this->assertInstanceOf(
            'Base\Models\User',
            $user,
            'Object must be instance of User');

        // Check primitive values
        $this->assertEquals($user->getId(), $userArray['id']);
        $this->assertEquals($user->getUsername(), $userArray['username']);
        $this->assertEquals($user->getPassword(), $userArray['password']);
        $this->assertEquals($user->getJoined(), $userArray['joined']);
        $this->assertEquals($user->getFirstName(), $userArray['namefirst']);
        $this->assertEquals($user->getLastName(), $userArray['namelast']);
        $this->assertEquals($user->getActivated(), $userArray['activated']);
        $this->assertEquals($user->getPassTemp(), $userArray['passTemp']);
        $this->assertEquals($user->getProfilePic(), $userArray['profilePic']);

        // Check households are set
        $this->assertInternalType('array',$user->getHouseholds());
        $this->assertEquals(3,count($user->getHouseholds()));
        foreach ($user->getHouseholds() as $household) {
            $this->assertInstanceOf('Base\Models\Household', $household);
        }

        // Check current household is Household object
        $this->assertInstanceOf(
            'Base\Models\Household',
            $user->getCurrHousehold(),
            'Current household must be instance of Household'
        );


    }

    public function testMakeUserWithoutId(){
        $userArray = array(
            'username' => 'jsmith',
            'password' => 'b9c73a5ddae499143c1031d9a1ff0f34f90d9d346df83075583c56bcbc9a5675',
            'email' => 'john.smith@example.com',
            'joined' => '2018-01-01 12:00:00',
            'namefirst' => 'John',
            'namelast' => 'Smith',
            'activated' => 1,
            'passTemp' => NULL,
            'currHouseholdId' => 1,
            'profilePic' => 'hsdfyugds78fsdfuile4r83rudsgfjkf.png'
        );

        $user = $this->userFactory->make($userArray);
        $this->assertInstanceOf(
            'Base\Models\User',
            $user,
            'Object must be instance of User');

        // Check primitive values
        $this->assertEquals($user->getId(), NULL);
        $this->assertEquals($user->getUsername(), $userArray['username']);
        $this->assertEquals($user->getPassword(), $userArray['password']);
        $this->assertEquals($user->getJoined(), $userArray['joined']);
        $this->assertEquals($user->getFirstName(), $userArray['namefirst']);
        $this->assertEquals($user->getLastName(), $userArray['namelast']);
        $this->assertEquals($user->getActivated(), $userArray['activated']);
        $this->assertEquals($user->getPassTemp(), $userArray['passTemp']);
        $this->assertEquals($user->getProfilePic(), $userArray['profilePic']);

        // Check households aren't set
        $this->assertInternalType('array',$user->getHouseholds());
        $this->assertEquals(0, count($user->getHouseholds()));

        // Check current household is NULL
        $this->assertEquals(0, $user->getCurrHousehold());

    }
}
