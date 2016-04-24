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
 * @package   ExceptionHelpers/V1/Callers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-exception-helpers
 */

namespace GanbaroDigitalTest\ExceptionHelpers\V1\Callers\Filters;

use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktraceForTwoCodeCallers;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktraceForTwoCodeCallers
 */
class FilterBacktraceForTwoCodeCallersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new FilterBacktraceForTwoCodeCallers;

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(FilterBacktraceForTwoCodeCallers::class, $unit);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new FilterBacktraceForTwoCodeCallers;
        $backtrace = debug_backtrace();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit($backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals('ReflectionMethod', $result[0]->getClass());
        $this->assertEquals('invokeArgs', $result[0]->getMethod());
    }

    /**
     * @covers ::from
     */
    public function testCanCallStatically()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = debug_backtrace();

        // ----------------------------------------------------------------
        // perform the change

        $result = FilterBacktraceForTwoCodeCallers::from($backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals('ReflectionMethod', $result[0]->getClass());
        $this->assertEquals('invokeArgs', $result[0]->getMethod());
    }

    /**
     * @covers ::from
     */
    public function testWillReturnGlobalFunctions()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = [
            [
                'file' => __FILE__,
                'line' => __LINE__,
                'function' => "testFunction",
            ],
            [
                'file' => __FILE__,
                'line' => __LINE__,
                'function' => "testFunction",
            ],
            [
                'file' => __FILE__,
                'line' => __LINE__,
                'function' => __FUNCTION__,
                'class' => __CLASS__,
            ],
            [
                'file' => __FILE__,
                'line' => __LINE__,
                'function' => __FUNCTION__,
                'class' => __CLASS__,
            ]
        ];

        // ----------------------------------------------------------------
        // perform the change

        $result = FilterBacktraceForTwoCodeCallers::from($backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals($backtrace[0]['file'], $result[0]->getFilename());
        $this->assertEquals($backtrace[0]['line'], $result[0]->getLine());
        $this->assertEquals($backtrace[1]['function'], $result[0]->getFunction());
    }

    /**
     * @covers ::from
     */
    public function testWillFilterOutNamespaces()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
        $partials = [
            __CLASS__,
            'ReflectionMethod'
        ];

        $expectedClass = 'PHPUnit_Framework_TestCase';
        $expectedMethod = 'runTest';

        // ----------------------------------------------------------------
        // perform the change

        $result = FilterBacktraceForTwoCodeCallers::from($backtrace, $partials);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals($expectedClass, $result[0]->getClass());
        $this->assertEquals($expectedMethod, $result[0]->getMethod());
    }

    /**
     * @covers ::from
     */
    public function testReturnsFirstStackFrameWhenEverythingElseFilteredOut()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
        $partials = [
            __CLASS__,
            'ReflectionMethod',
            'PHPUnit_Framework_TestCase',
            'PHPUnit_Framework_TestResult',
            'PHPUnit_Framework_TestSuite',
            'PHPUnit_TextUI_TestRunner',
            'PHPUnit_TextUI_Command',
        ];

        $expectedClass = 'ReflectionMethod';
        $expectedMethod = 'invokeArgs';

        // ----------------------------------------------------------------
        // perform the change

        $result = FilterBacktraceForTwoCodeCallers::from($backtrace, $partials);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals($expectedClass, $result[0]->getClass());
        $this->assertEquals($expectedMethod, $result[0]->getMethod());
    }

    /**
     * @covers ::from
     */
    public function testReturnsTwoStackFrames()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
        $partials = [
            __CLASS__,
            'ReflectionMethod',
        ];

        $expectedClass = $expectedMethod = [];
        $expectedClass[0] = 'PHPUnit_Framework_TestCase';
        $expectedMethod[0] = 'runTest';
        $expectedClass[1] = 'PHPUnit_Framework_TestCase';
        $expectedMethod[1] = 'runBare';

        // ----------------------------------------------------------------
        // perform the change

        $result = FilterBacktraceForTwoCodeCallers::from($backtrace, $partials);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(CodeCaller::class, $result[0]);
        $this->assertEquals($expectedClass[0], $result[0]->getClass());
        $this->assertEquals($expectedMethod[0], $result[0]->getMethod());
        $this->assertInstanceOf(CodeCaller::class, $result[1]);
        $this->assertEquals($expectedClass[1], $result[1]->getClass());
        $this->assertEquals($expectedMethod[1], $result[1]->getMethod());
    }
}
