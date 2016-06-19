<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   ExceptionHelpers/V1/BaseExceptions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-exception-helpers
 */

namespace GanbaroDigitalTest\ExceptionHelpers\V1\BaseExceptions;

use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\UnsupportedValue;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * @coversDefaultClass GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\UnsupportedValue
 */
class UnsupportedValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::newFromVar
     */
    public function testCanCreateFromVariable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        // ----------------------------------------------------------------
        // perform the change

        $unit = UnsupportedValue::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnsupportedValue::class, $unit);
    }

    /**
     * @covers ::newFromVar
     */
    public function testIsRuntimeException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        // ----------------------------------------------------------------
        // perform the change

        $unit = UnsupportedValue::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RuntimeException::class, $unit);
    }

    /**
     * @covers ::newFromVar
     */
    public function testIsHttpRuntimeErrorException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        // ----------------------------------------------------------------
        // perform the change

        $unit = UnsupportedValue::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(HttpRuntimeErrorException::class, $unit);
    }

    /**
     * @covers ::getHttpStatus
     */
    public function testMapsToHttpStatus500()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $unit = UnsupportedValue::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // perform the change

        $httpStatus = $unit->getHttpStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnexpectedErrorStatus::class, $httpStatus);
    }

    /**
     * @covers ::newFromInputParameter
     */
    public function test_can_create_from_input_parameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = 'ReflectionMethod->invokeArgs(): ' . __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 14) . ' says \'$alfred\' of type NULL contains an unsupported value';
        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 12),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 11),
            'calledBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'calledByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = UnsupportedValue::newFromInputParameter($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromVar
     */
    public function test_can_create_from_PHP_variable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 12) . ': \'$alfred\' of type NULL contains an unsupported value';
        $expectedData = [
            'thrownBy' => new CodeCaller(self::class, __FUNCTION__, '->', __FILE__, __LINE__ + 10),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 9),
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = UnsupportedValue::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }
}
