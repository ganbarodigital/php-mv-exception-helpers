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
use GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\BuildThrownAndCalledBy;
use GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\BuildThrownBy;
use GanbaroDigital\MissingBits\TypeInspectors\GetPrintableType;
use RuntimeException;

/**
 * ParameterisedException provides a data-oriented base class for your exceptions
 */
class ParameterisedException extends RuntimeException
{
    /**
     * the data used to construct our message
     *
     * @var array
     */
    private $data;

    /**
     * the format string used to construct our message
     *
     * @var string
     */
    private $formatString;

    /**
     * default values for extra data
     * @var array
     */
    static protected $defaultExtras = [];

    /**
     * default filter for the call stack
     * @var array
     */
    static protected $defaultCallStackFilter = [];

    /**
     * our constructor
     *
     * You should call one of the 'newFromXXX()' methods to create a new
     * exception to throw. These methods customise the format string and
     * exception data for different contexts.
     *
     * @param string $formatString
     *        the sprintf() format string for our human-readable message
     * @param array $data
     *        the data required to sprintf() with $formatString
     * @param int $code
     *        the error code that you want to set (if any)
     */
    public function __construct($formatString, $data = [], $code = 0)
    {
        // remember our extra parameters
        $this->formatString = $formatString;
        $this->data = $data;

        // build the printable message
        //
        // this uses vnsprintf() from ganbarodigital/php-the-missing-bits
        // to support named parameters in the format string :)
        $message = vnsprintf($formatString, $data);
        parent::__construct($message, $code);
    }

    /**
     * create a new exception about your function / method's input parameter
     *
     * @param  mixed $fieldOrVar
     *         the input parameter that you're throwing an exception about
     * @param  string $fieldOrVarName
     *         the name of the input parameter in your code
     * @param  array $extraData
     *         extra data that you want to include in your exception
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array $callStackFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedType
     *         an fully-built exception for you to throw
     */
    public static function newFromInputParameter($fieldOrVar, $fieldOrVarName, array $extraData = [], $typeFlags = null, array $callStackFilter = [])
    {
        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // build the message and data
        list($message, $data) = self::buildFormatAndData(
            BuildThrownAndCalledBy::class,
            static::$defaultFormat,
            $backtrace,
            $fieldOrVar,
            $fieldOrVarName,
            static::$defaultExtras,
            $extraData,
            $typeFlags,
            static::$defaultCallStackFilter,
            $callStackFilter
        );

        // all done
        return new static($message, $data);
    }

    /**
     * create a new exception about a value generated by / returned to your
     * function or method
     *
     * @param  mixed $fieldOrVar
     *         the value that you're throwing an exception about
     * @param  string $fieldOrVarName
     *         the name of the value in your code
     * @param  array $extraData
     *         extra data that you want to include in your exception
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array $callStackFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedType
     *         an fully-built exception for you to throw
     */
    public static function newFromVar($fieldOrVar, $fieldOrVarName, array $extraData = [], $typeFlags = null, array $callStackFilter = [])
    {
        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // build the basic message and data
        list($message, $data) = self::buildFormatAndData(
            BuildThrownBy::class,
            static::$defaultFormat,
            $backtrace,
            $fieldOrVar,
            $fieldOrVarName,
            static::$defaultExtras,
            $extraData,
            $typeFlags,
            static::$defaultCallStackFilter,
            $callStackFilter
        );

        // all done
        return new static($message, $data);
    }

    /**
     * create the format string and exception data
     *
     * @param  string $builder
     *         name of the ParameterBuilder class to use
     * @param  string $formatString
     *         the format string to customise
     * @param  array $backtrace
     *         a debug_backtrace()
     * @param  mixed $fieldOrVar
     *         the value that you're throwing an exception about
     * @param  string $fieldOrVarName
     *         the name of the value in your code
     * @param  array $extraDefaults
     *         default values for extra data that you want to include in your exception
     * @param  array $extra
     *         extra data that you want to include in your exception
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array $defaultCallStackFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @param  array $callStackFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return ParameterisedException
     *         an fully-built exception for you to throw
     */
    protected static function buildFormatAndData($builder, $formatString, $backtrace, $fieldOrVar, $fieldOrVarName, $extraDefaults, $extra, $typeFlags = null, array $defaultCallStackFilter = [], array $callStackFilter = [])
    {
        // merge the defaults into the provided call stack filter
        foreach ($defaultCallStackFilter as $className) {
            $callStackFilter[] = $className;
        }

        // build the basic message and data
        list($message, $data) = $builder::from($formatString, $backtrace, $callStackFilter);

        // merge in our defaults
        foreach ($extraDefaults as $key => $value) {
            $data[$key] = $value;
        }

        // merge in any extra data we've been given
        foreach ($extra as $key => $value) {
            $data[$key] = $value;
        }

        // merge in the remainder of our parameters
        $data['dataType'] = GetPrintableType::of($fieldOrVar, $typeFlags);
        $data['fieldOrVarName'] = $fieldOrVarName;
        $data['fieldOrVar'] = $fieldOrVar;

        // all done
        return [$message, $data];
    }

    /**
     * what was the data that we used to create the printable message?
     *
     * @return array
     */
    public function getMessageData()
    {
        return $this->data;
    }

    /**
     * what was the format string we used to create the printable message?
     *
     * @return string
     */
    public function getMessageFormat()
    {
        return $this->formatString;
    }
}
