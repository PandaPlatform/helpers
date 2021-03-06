<?php

/*
 * This file is part of the Panda Helpers Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * Class NumberHelperTest
 * @package Panda\Support\Helpers
 */
class NumberHelperTest extends TestCase
{
    /**
     * @covers \Panda\Support\Helpers\NumberHelper::floor
     */
    public function testFloor()
    {
        // No decimals
        $this->assertEquals(10, NumberHelper::floor(10.235, 0));
        $this->assertEquals(10, NumberHelper::floor(10.543, 0));
        $this->assertEquals(10, NumberHelper::floor(10.987, 0));

        // With decimals
        $this->assertEquals(10.12, NumberHelper::floor(10.123456789, 2));
        $this->assertEquals(10.12345, NumberHelper::floor(10.123456789, 5));
        $this->assertEquals(10.12345678, NumberHelper::floor(10.123456789, 8));

        $this->assertEquals(10.98, NumberHelper::floor(10.987654321, 2));
        $this->assertEquals(10.98765, NumberHelper::floor(10.987654321, 5));
        $this->assertEquals(10.98765432, NumberHelper::floor(10.987654321, 8));
    }

    /**
     * @covers \Panda\Support\Helpers\NumberHelper::average
     */
    public function testAverage()
    {
        // Normal input
        $this->assertEquals(2, NumberHelper::average([1, 2, 3]));
        $this->assertEquals(3, NumberHelper::average([3, 3, 3]));

        // Empty input
        $this->assertFalse(NumberHelper::average([]));
        $this->assertFalse(NumberHelper::average(null));
    }

    /**
     * @covers \Panda\Support\Helpers\NumberHelper::isEqual
     */
    public function testIsEqual()
    {
        // Valid cases
        $this->assertTrue(NumberHelper::isEqual(1, 1, 0));
        $this->assertTrue(NumberHelper::isEqual(1, 1, 1));
        $this->assertTrue(NumberHelper::isEqual(1, 1, 2));
        $this->assertTrue(NumberHelper::isEqual(1.1234, 1.1235, 3));

        // Invalid cases
        $this->assertFalse(NumberHelper::isEqual(1, 2, 0));
        $this->assertFalse(NumberHelper::isEqual(1.123, 2.123, 0));
        $this->assertFalse(NumberHelper::isEqual(1.123, 2.123, 3));
        $this->assertFalse(NumberHelper::isEqual(1.1234, 1.123, 4));
    }
}
