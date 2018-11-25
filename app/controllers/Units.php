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
use Base\Models\Unit;
use Base\Factories\UnitFactory;
use Base\Repositories\UnitRepository;

/**
 * Units users can add and keep track of
 */
class Units extends Controller {

    protected $dbh,
        $session,
        $request;

    private $unitRepository,
        $unitFactory;

    public function __construct(DatabaseHandler $dbh, Session $session, $request, $dependencies){
        $this->dbh = $dbh;
        $this->session = $session;
        $this->request = $request;

        $this->unitFactory = $dependencies['unitFactory'];
        $this->unitRepository = $dependencies['unitRepository'];
    }

    /**
     * Returns convertible units from abbreviation
     */
    public function getConvertibleFrom($abbreviation):void{

        $convertibleUnits = $this->unitRepository->allConvertibleFrom($abbreviation);

        $units = array();
        foreach ($convertibleUnits as $unit) {
            $newUnit = array();
            $newUnit['id']= $unit->getId();
            $newUnit['name']= $unit->getName();
            $newUnit['abbreviation']= $unit->getAbbreviation();
            $units[]=$newUnit;
        }

        echo json_encode($units);
        return;
    }

}
