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

/**
 * Wrapper class to isolate validation library
 */
class ValidationWrapper {

    private
        $rules,
        $labels,
        $validator,
        $session;

    public function __construct($session)
    {
        $this->session = $session;
        $this->rules = NULL;
        $this->labels = NULL;
    }

    /**
     * Validates input from form
     * @param array $input      Input to validate
     * @param string $method    Method to redirect to
     * @param array $params     Parameters for the redirection method
     */
    public function validateInput($input, $redirectControllerName, $redirectMethodName, $redirectParams = NULL):void{

        // Validate input
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';

        $validator = new Validator($input);
        $validator->rules($this->rules);
        $validator->labels($this->labels);

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod($redirectControllerName, $redirectControllerMethod, $redirectParams);
            return;
        }
    }

    public function setRules($rules){
        $this->rules = $rules;
    }

    public function setLabels($labels){
        $this->labels = $labels;
    }




}
