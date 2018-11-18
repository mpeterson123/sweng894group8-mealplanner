<?php
// Object sample tests
// Notes:
// - Change `cut` for the name of the class you are testing, in camelCase
// Copy below this line --------------------------------------------------------


//////////////
// Variable //
//////////////

public function testGetVariable(){
    $variable = $this->createMock(Variable::class);
    $this->cut->setVariable($variable);
    $this->assertEquals($this->cut->getVariable(), $variable);
}

public function testVariableIsOfTypeVariable(){
    $variable = $this->createMock(Variable::class);
    $this->cut->setVariable($variable);
    $this->assertEquals($this->cut->getVariable(), $variable);

    $this->assertInstanceOf(
        'Base\Models\Variable',
        $variable,
        'Object must be instance of Variable');
}

public function testRejectInvalidVariable(){
    $this->expectException(\Exception::class);
    $this->variable->setVariable('bad');
}
