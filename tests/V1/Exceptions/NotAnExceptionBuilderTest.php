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
 * @package   ExceptionHelpers/V1/Exceptions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-exception-helpers
 */

namespace GanbaroDigitalTest\ExceptionHelpers\V1\Exceptions;

use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\ExceptionHelpersException;
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NotAnExceptionBuilder;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;
use GanbaroDigital\HttpStatus\StatusProviders\RuntimeError\UnexpectedErrorStatusProvider;
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * @coversDefaultClass GanbaroDigital\ExceptionHelpers\V1\Exceptions\NotAnExceptionBuilder
 */
class NotAnExceptionBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(NotAnExceptionBuilder::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testIsExceptionHelpersException()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ExceptionHelpersException::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testIsParameterisedException()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ParameterisedException::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testIsRuntimeException()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RuntimeException::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testIsHttpRuntimeErrorException()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(HttpRuntimeErrorException::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testMapsToUnexpectedErrorStatus()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new NotAnExceptionBuilder(__CLASS__);

        // ----------------------------------------------------------------
        // perform the change

        $httpStatus = $unit->getHttpStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnexpectedErrorStatus::class, $httpStatus);
    }

    /**
     * @covers ::newFromNonCallable
     * @dataProvider provideNonCallableToTest
     */
    public function testCanBuildFromBadExceptionBuilders($nonCallable, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedAlias = "FakeException";
        $expectedMessage = "ReflectionMethod->invokeArgs(): '\$exceptionBuilder' for '{$expectedAlias}' must be callable; {$expectedType} given";

        // ----------------------------------------------------------------
        // perform the change

        // we have to pass in an empty filter to make sure that we're picked
        // up as the caller
        $unit = NotAnExceptionBuilder::newFromNonCallable($expectedAlias, $nonCallable);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::newFromNonCallable
     * @dataProvider provideNonCallableToTest
     */
    public function testExceptionMessageContainsCaller($nonCallable, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedAlias = "FakeException";
        $expectedMessage = "ReflectionMethod->invokeArgs(): '\$exceptionBuilder' for '{$expectedAlias}' must be callable; {$expectedType} given";

        // ----------------------------------------------------------------
        // perform the change

        // we have to pass in an empty filter to make sure that we're picked
        // up as the caller
        $unit = NotAnExceptionBuilder::newFromNonCallable($expectedAlias, $nonCallable);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::newFromNonCallable
     * @dataProvider provideNonCallableToTest
     */
    public function testExceptionMessageContainsExceptionAlias($nonCallable, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedAlias = "FakeException";
        $expectedMessage = "ReflectionMethod->invokeArgs(): '\$exceptionBuilder' for '{$expectedAlias}' must be callable; {$expectedType} given";

        // ----------------------------------------------------------------
        // perform the change

        // we have to pass in an empty filter to make sure that we're picked
        // up as the caller
        $unit = NotAnExceptionBuilder::newFromNonCallable($expectedAlias, $nonCallable);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::newFromNonCallable
     * @dataProvider provideNonCallableToTest
     */
    public function testExceptionMessageContainsTypeOfBadBuilder($nonCallable, $expectedType)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedAlias = "FakeException";
        $expectedMessage = "ReflectionMethod->invokeArgs(): '\$exceptionBuilder' for '{$expectedAlias}' must be callable; {$expectedType} given";

        // ----------------------------------------------------------------
        // perform the change

        // we have to pass in an empty filter to make sure that we're picked
        // up as the caller
        $unit = NotAnExceptionBuilder::newFromNonCallable($expectedAlias, $nonCallable);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function provideNonCallableToTest()
    {
        return [
            [ null, 'NULL' ],
            [ [], 'array' ],
            [ true, 'boolean<true>' ],
            [ false, 'boolean<false>' ],
            [ 0.0, 'double<0>' ],
            [ 3.1415927, 'double<3.1415927>' ],
            [ 0, 'integer<0>' ],
            [ 100, 'integer<100>' ],
            [ new \stdClass, 'object<stdClass>' ],
            [ STDIN, 'resource' ],
            [ "hello, world!", 'string<hello, world!>' ],
        ];
    }
}
