<?php
// String sample tests
// Notes:
// - Adjust longVariableName max to length + 1, and method name to max length
// Copy below this line --------------------------------------------------------


//////////////
// Variable //
//////////////

public function testSetVariable(){
    $variable = 'My CUT';
    $this->cut->setVariable($variable);
    $this->assertEquals($this->cut->getVariable(), $variable);
}

public function testVariableCannotBeEmpty(){
    $this->expectException(\Exception::class);
    $this->cut->setVariable('');
}

public function testVariableCannotBeLongerThan20Chars(){
    $longVariable = '123456789012345678901234567890';
    $this->expectException(\Exception::class);
    $this->cut->setVariable($longVariable);
}

public function testVariableCannotHaveExtraWhitespace(){
    $variableWithWhitespace = ' My CUT   ';
    $expectedVariable =  'My CUT';
    $this->cut->setVariable($variableWithWhitespace);

    $this->assertEquals($this->cut->getVariable(), $expectedVariable,
        'Variable must be trimmed.');
}

public function testVariableIsString(){
    $stringVariable = 'CUT';
    $this->cut->setVariable($stringVariable);
    $this->assertInternalType('string', $stringVariable);
}

public function testNonStringVariablesAreRejected(){
    $nonStringVariable = 0;
    $this->expectException(\Exception::class);
    $this->cut->setVariable($nonStringVariable);
}

public function testNonAlphanumericVariableIsRejected(){
    $invalidVariable = 'A *bad*_variable!';
    $this->expectException(\Exception::class);
    $this->user->setVariable($invalidVariable);
}
