<?php
// Float sample tests
// Notes:
// - Adjust longVariableName max to length + 1, and method name to max length
// Copy below this line --------------------------------------------------------


//////////////
// Variable //
//////////////

public function testGetVariable(){
    $stock = 3;
    $this->variable->setVariable($stock);
    $this->assertEquals($this->variable->getVariable(), $stock, 'Variable must be ${stock}.');
}

/**
 * @dataProvider tooLowVariableProvider
 */
public function testVariableCannotBeZeroOrNegative($variable){
    $this->expectException(\Exception::class);
    $this->cut->setVariable($variable);
}

public function tooLowVariableProvider()
{
    return [
        'zero' => [0],
        'negative one' => [-1],
        'long negative number' => [-999999999],
        'very small number' => [-0.0000000000000000000000000000000000000001]
    ];
}

/**
 * @dataProvider tooHighVariableProvider
 */
public function testVariableCannotBeOver9999Point99($variable){
    $this->expectException(\Exception::class);
    $this->cut->setVariable($variable);
}

public function tooHighVariableProvider()
{
    return [
        'Too high by one thousandth' => [999.991],
        '1000' => [1000],
        'Too high integer' => [10000000000000000000000000000],
        'Too high decimal' => [100000.5325]
    ];
}

/**
 * @dataProvider inRangeVariableProvider
 */
public function testVariableIsBetween0AndBelowOrEqualTo999Point99($variable){
    $this->cut->setVariable($variable);
    $this->assertEquals($this->cut->getVariable(), $variable);
}

public function inRangeVariableProvider()
{
    return [
        '999 dot 99 units' => [999.99],
        '0 0001' => [0.0001],
        '5555' => [555]
    ];
}

public function testNonNumericVariableIsRejected(){
    $variable = "50.";
    $this->expectException(\Exception::class);
    $this->cut->setVariable($variable);
}
