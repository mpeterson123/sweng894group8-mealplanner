<?php
// String sample tests
// Notes:
// - Adjust longVariableName max to length + 1, and method name to max length
// Copy below this line --------------------------------------------------------


//////////////
// Variable //
//////////////

public function testSetAndGetVariable(){
    $variable = 1;
    $this->cut->setVariable($variable);
    $this->assertEquals($this->cut->getVariable(), $variable);
}

public function testVariableCannotBeEmpty(){
    $this->expectException(\Exception::class);
    $this->cut->setVariable(NULL);
}

public function testVariableIsAnInteger(){
    $intVariable = 123;
    $this->cut->setVariable($intVariable);
    $this->assertInternalType('integer', $this->cut->getVariable());
}

public function testNonIntVariableIsRejected(){
    $nonIntVariable = '123';
    $this->expectException(\Exception::class);
    $this->cut->setVariable($nonIntVariable);
}
