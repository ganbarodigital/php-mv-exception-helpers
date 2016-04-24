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
use GanbaroDigital\HttpStatus\Specifications\HttpStatusProvider;
use GanbaroDigital\HttpStatus\StatusValues\RequestError\UnprocessableEntityStatus;
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
    public function testIsHttpStatusProvider()
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

        $this->assertInstanceOf(HttpStatusProvider::class, $unit);
    }

    /**
     * @covers ::getHttpStatus
     */
    public function testMapsToHttpStatus422()
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

        $this->assertInstanceOf(UnprocessableEntityStatus::class, $httpStatus);
    }

    /**
     * @covers ::getMessage
     */
    public function testExceptionMessageContainsFieldAndUnsupportedValue()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $unit = UnsupportedValue::newFromVar($data, $name);

        $expectedMessage = "GanbaroDigitalTest\ExceptionHelpers\V1\BaseExceptions\UnsupportedValueTest->testExceptionMessageContainsFieldAndUnsupportedValue()@158: '\$alfred' contains an unsupported value";

        // ----------------------------------------------------------------
        // perform the change

        $actualMessage = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $actualMessage);
    }

}