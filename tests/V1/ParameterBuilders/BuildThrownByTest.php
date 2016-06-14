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
 * @package   ExceptionHelpers/V1/ParameterBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-exception-helpers
 */

namespace GanbaroDigitalTest\ExceptionHelpers\V1\ParameterBuilders;

use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\BuildThrownBy;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\BuildThrownBy
 */
class BuildThrownByTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::from
     */
    public function test_will_prepend_thrower_to_message()
    {
        // ----------------------------------------------------------------
        // setup your test

        $message = "'%fieldOrVarName\$s' cannot be NULL";
        $expectedMessage = '%thrownByName$s: ' . $message;

        // ----------------------------------------------------------------
        // perform the change

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        list($actualMessage, $actualData) = BuildThrownBy::from($message, $backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    /**
     * @covers ::from
     */
    public function test_will_return_thrower_in_data_block()
    {
        // ----------------------------------------------------------------
        // setup your test

        $message = "'%fieldOrVarName\$s' cannot be NULL";

        $expectedData = [
            'thrownBy' => new CodeCaller(__CLASS__, __FUNCTION__, '->', __FILE__, __LINE__ + 12),
            'thrownByName' => __CLASS__ . '->' . __FUNCTION__ . '()@' . (__LINE__ + 11),
        ];

        // ----------------------------------------------------------------
        // perform the change
        //
        // to correctly simulate getting a backtrace inside an exception's
        // factory method, we need to call something that will get the
        // backtrace for us

        $backtraceFn = function() { return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); };
        $backtrace = $backtraceFn();
        list($actualMessage, $actualData) = BuildThrownBy::from($message, $backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedData, $actualData);
    }
}
