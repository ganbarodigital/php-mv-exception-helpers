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

namespace GanbaroDigital\ExceptionHelpers\V1\Exceptions;

use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;
use GanbaroDigital\HttpStatus\StatusProviders\RuntimeError\UnexpectedErrorStatusProvider;
use GanbaroDigital\MissingBits\TypeInspectors\GetPrintableType;

class NoBuilderForException
  extends ParameterisedException
  implements ExceptionHelpersException, HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  mixed $exceptionName
     *         the name of the exception that we could not find
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return NoBuilderForException
     *         an fully-built exception for you to throw
     */
    public static function newFromExceptionName($exceptionName, $callerFilter = null)
    {
        // what filter are we applying?
        if (!is_array($callerFilter)) {
            $callerFilter = FilterCodeCaller::$DEFAULT_PARTIALS;
        }

        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = FilterCodeCaller::from($backtrace, $callerFilter);

        // all done
        return new static(
            "%callerName\$s: no builder for exception '%exceptionName\$s'",
            [
                'exceptionName' => $exceptionName,
                'callerName' => $caller->getCaller(),
                'caller' => $caller,
            ]
        );
    }
}