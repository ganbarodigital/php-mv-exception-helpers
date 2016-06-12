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

namespace GanbaroDigital\ExceptionHelpers\V1\BaseExceptions;

use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktraceForTwoCodeCallers;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;
use GanbaroDigital\HttpStatus\StatusProviders\RuntimeError\UnexpectedErrorStatusProvider;
use GanbaroDigital\MissingBits\TypeInspectors\GetPrintableType;

/**
 * UnsupportedType is thrown whenever input data fails validation because
 * the input data has the wrong data type.
 *
 * Subclass this exception in your own libraries and applications.
 */
class UnsupportedType extends ParameterisedException implements HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  mixed $var
     *         the variable that has the unsupported type
     * @param  string $fieldOrVarName
     *         the name of the input field, PHP variable or function/method
     *         parameter that contains $data
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedType
     *         an fully-built exception for you to throw
     */
    public static function newFromInputParameter($var, $fieldOrVarName, $typeFlags = null, array $callerFilter = [])
    {
        // what flags are we applying?
        if (!is_int($typeFlags)) {
            $typeFlags = GetPrintableType::FLAG_DEFAULTS;
        }

        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $callers = FilterBacktraceForTwoCodeCallers::from($backtrace, $callerFilter);

        // what type was rejected?
        $type = GetPrintableType::of($var, $typeFlags);

        // all done
        return new static(
            "%calledByName\$s: %thrownByName\$s says '%fieldOrVarName\$s' cannot be type '%dataType\$s'",
            [
                'fieldOrVarName' => $fieldOrVarName,
                'dataType' => $type,
                'thrownByName' => $callers[0]->getCaller(),
                'thrownBy' => $callers[0],
                'calledByName' => $callers[1]->getCaller(),
                'calledBy' => $callers[1],
            ]
        );
    }

    /**
     * create a new exception
     *
     * @param  mixed $var
     *         the variable that has the unsupported type
     * @param  string $fieldOrVarName
     *         the name of the input field, PHP variable or function/method
     *         parameter that contains $data
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedType
     *         an fully-built exception for you to throw
     */
    public static function newFromVar($var, $fieldOrVarName, $typeFlags = null, array $callerFilter = [])
    {
        // what flags are we applying?
        if (!is_int($typeFlags)) {
            $typeFlags = GetPrintableType::FLAG_DEFAULTS;
        }

        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = FilterCodeCaller::from($backtrace, $callerFilter);

        // what type was rejected?
        $type = GetPrintableType::of($var, $typeFlags);

        // all done
        return new static(
            "%thrownByName\$s: '%fieldOrVarName\$s' cannot be type '%dataType\$s'",
            [
                'fieldOrVarName' => $fieldOrVarName,
                'dataType' => $type,
                'thrownByName' => $caller->getCaller(),
                'thrownBy' => $caller,
            ]
        );
    }
}
