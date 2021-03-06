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

use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

/**
 * @coversDefaultClass GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException
 */
class ParameterisedExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $formatString = "welcome, %s, to %s";
        $messageData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ParameterisedException($formatString, $messageData, $expectedCode);

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

        $formatString = "welcome, %s, to %s";
        $messageData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ParameterisedException($formatString, $messageData, $expectedCode);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RuntimeException::class, $unit);
    }

    /**
     * @covers ::__construct
     * @dataProvider provideCodesToTest
     */
    public function testCanPassCodeIntoConstructor($expectedCode)
    {
        // ----------------------------------------------------------------
        // setup your test

        $formatString = "welcome, %s, to %s";
        $messageData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ParameterisedException($formatString, $messageData, $expectedCode);

        $actualCode = $unit->getCode();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedCode, $actualCode);
    }

    public function provideCodesToTest()
    {
        return [
            [ 200 ],
            [ 204 ],
            [ 400 ],
            [ 401 ],
            [ 402 ],
            [ 405 ],
            [ 500 ],
            [ 502 ],
            [ 503 ]
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testSetsExceptionMessageToFormattedString()
    {
        // ----------------------------------------------------------------
        // setup your test

        $formatString = "welcome, %s, to %s";
        $messageData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ParameterisedException($formatString, $messageData, $expectedCode);

        $actualMessage = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::__construct
     */
    public function testFormatStringSupportsNamedParameters()
    {
        // ----------------------------------------------------------------
        // setup your test

        $formatString = "welcome, %type\$s, to %place\$s";
        $messageData  = [ 'type' => 'human', 'place' => 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        // ----------------------------------------------------------------
        // perform the change

        $unit = new ParameterisedException($formatString, $messageData, $expectedCode);

        $actualMessage = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::getMessageData
     */
    public function testCanGetMessageData()
    {
        // ----------------------------------------------------------------
        // setup your test

        $formatString = "welcome, %s, to %s";
        $expectedData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        $unit = new ParameterisedException($formatString, $expectedData, $expectedCode);

        // ----------------------------------------------------------------
        // perform the change

        $actualData = $unit->getMessageData();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::getMessageFormat
     */
    public function testCanGetMessageFormatString()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedFormat = "welcome, %s, to %s";
        $expectedData  = [ 'human', 'the new world' ];
        $expectedMessage = "welcome, human, to the new world";
        $expectedCode = 500;

        $unit = new ParameterisedException($expectedFormat, $expectedData, $expectedCode);

        // ----------------------------------------------------------------
        // perform the change

        $actualFormat = $unit->getMessageFormat();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedFormat, $actualFormat);
    }

    /**
     * @covers ::newFromInputParameter
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_input_parameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = 'ReflectionMethod->invokeArgs(): ' . __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 15) . ' says \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 13),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 12),
            'calledBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'calledByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Subclass::newFromInputParameter($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromInputParameter
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_input_parameter_with_extra_data()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $extra = [ 'extra' => 'has been included' ];

        $expectedMessage = 'ReflectionMethod->invokeArgs(): ' . __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 15) . ' says \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 13),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 12),
            'calledBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'calledByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has been included',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Subclass::newFromInputParameter($data, $name, $extra);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromInputParameter
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_input_parameter_with_default_callerFilter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = 'ReflectionMethod->invokeArgs(): ' . __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 15) . ' says \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 13),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 12),
            'calledBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'calledByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Target::fail(ParameterisedExceptionTest_FilteredSubclass::class, 'newFromInputParameter', $data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromInputParameter
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_input_parameter_with_callerFilter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $callStackFilter = ['GanbaroDigitalTest\\ExceptionHelpers\\V1\BaseExceptions\\ParameterisedExceptionTest_Target'];

        $expectedMessage = 'ReflectionMethod->invokeArgs(): ' . __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 15) . ' says \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 13),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 12),
            'calledBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'calledByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Target::fail(ParameterisedExceptionTest_Subclass::class, 'newFromInputParameter', $data, $name, [], null, $callStackFilter);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromVar
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_PHP_variable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 13) . ': \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(self::class, __FUNCTION__, '->', __FILE__, __LINE__ + 11),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 10),
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Subclass::newFromVar($data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromVar
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_PHP_variable_with_extra_data()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $extra = [ 'extra' => 'has been included' ];

        $expectedMessage = __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 13) . ': \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(self::class, __FUNCTION__, '->', __FILE__, __LINE__ + 11),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 10),
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has been included',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Subclass::newFromVar($data, $name, $extra);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromVar
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_PHP_variable_with_default_callerFilter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";

        $expectedMessage = __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 13) . ': \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller(self::class, __FUNCTION__, '->', __FILE__, __LINE__ + 11),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 10),
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Target::fail(ParameterisedExceptionTest_FilteredSubclass::class, 'newFromVar', $data, $name);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * @covers ::newFromVar
     * @covers ::buildFormatAndData
     */
    public function test_can_create_from_PHP_variable_with_callerFilter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = null;
        $name = "\$alfred";
        $callStackFilter = [self::class];

        $expectedMessage = 'ReflectionMethod->invokeArgs(): \'$alfred\' cannot be \'NULL\'';
        $expectedData = [
            'thrownBy' => new CodeCaller('ReflectionMethod', 'invokeArgs', '->', null, null),
            'thrownByName' => 'ReflectionMethod->invokeArgs()',
            'fieldOrVarName' => '$alfred',
            'fieldOrVar' => null,
            'dataType' => 'NULL',
            'extra' => 'has not been included!!',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = ParameterisedExceptionTest_Subclass::newFromVar($data, $name, [], null, $callStackFilter);

        // ----------------------------------------------------------------
        // test the results

        $actualMessage = $unit->getMessage();
        $actualData = $unit->getMessageData();

        $this->assertEquals($expectedMessage, $actualMessage);
        $this->assertEquals($expectedData, $actualData);
    }
}

class ParameterisedExceptionTest_Subclass extends ParameterisedException
{
    static protected $defaultFormat = "'%fieldOrVarName\$s' cannot be '%dataType\$s'";
    static protected $defaultExtras = [
        'extra' => 'has not been included!!',
    ];
}

class ParameterisedExceptionTest_FilteredSubclass extends ParameterisedException
{
    static protected $defaultFormat = "'%fieldOrVarName\$s' cannot be '%dataType\$s'";
    static protected $defaultExtras = [
        'extra' => 'has not been included!!',
    ];
    static protected $defaultCallStackFilter = [
        'GanbaroDigitalTest\\ExceptionHelpers\\V1\BaseExceptions\\ParameterisedExceptionTest_Target',
    ];
}

// we need this to guarantee the contents at the top of the stack trace
class ParameterisedExceptionTest_Target
{
    static public function fail($exceptionClass, $method, $fieldOrVar, $fieldOrVarName, array $extraData = [], $typeFlags = null, array $callStackFilter = [])
    {
        return $exceptionClass::$method($fieldOrVar, $fieldOrVarName, $extraData, $typeFlags, $callStackFilter);
    }
}
