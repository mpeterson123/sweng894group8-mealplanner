<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FoodTest extends TestCase
{
    public function testCannotBeUpdatedMissingName(): void
    {
        $this->setExpectedException('Exception');

        Food::updateFood(1, '', '3.65');
    }

}
