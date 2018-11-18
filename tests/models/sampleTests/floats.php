<?php
// Float sample tests
// Notes:
// - Adjust longVariableName max to length + 1, and method name to max length
// Copy below this line --------------------------------------------------------


//////////////
// Variable //
//////////////

public function testVariableIsStoredAsFloat(){
    $floatVariable = '123.45';
    $this->cut->setVariable($floatVariable);
    $this->assertInternalType('float', $this->cut->getVariable());
}
