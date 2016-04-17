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

namespace GanbaroDigital\ExceptionHelpers\V1\Callers\Filters;

/**
 * find the first entry in a debug_backtrace() array that contains useful
 * information, with optional support for skipping over namespaces
 * (e.g. skip over namespaces used to enforce robustness)
 */
class FilterBacktrace
{
    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @return array
     */
    public function __invoke($backtrace, $partialsToFilterOut = [])
    {
        return self::from($backtrace, $partialsToFilterOut);
    }

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @return array
     */
    public static function from($backtrace, $partialsToFilterOut = [])
    {
        // find the first backtrace entry that passes our filters
        foreach ($backtrace as $frame) {
            if (!isset($frame['class'])) {
                // called from global function
                return self::extractFrameDetails($frame);
            }

            // do we want to skip over this class name?
            if (self::isClassNameOkay($frame['class'], $partialsToFilterOut)) {
                return self::extractFrameDetails($frame);
            }
        }

        // if we get here, then we have run out of places to look
        return self::extractFrameDetails($backtrace[0]);
    }

    /**
     * is the given classname NOT in our list of partial namespaces?
     *
     * @param  string  $className
     *         the fully-qualified class name to check
     * @param  array $partialsToFilterOut
     *         the list of partial namespaces to filter for
     * @return boolean
     *         TRUE if the classname is NOT in our list of partial namespaces
     *         FALSE otherwise
     */
    private static function isClassNameOkay($className, $partialsToFilterOut)
    {
        // we search individual namespaces, plus the whole class (in case
        // someone has used a ::class constant)
        $parts = explode('\\', $className);
        $parts[] = $className;

        if (empty(array_intersect($partialsToFilterOut, $parts))) {
            return true;
        }

        // if we get here, then this class isn't one that we want to return
        // to the caller
        return false;
    }

    /**
     * extract only the stack frame fields we are interested in
     *
     * guarantees that the return value contains all four keys, even if they
     * are missing from the stack frame
     *
     * @param  array $frame
     *         a stack frame from `debug_backtrace`
     * @return array
     *         contains class, function, file, and line
     */
    private static function extractFrameDetails($frame)
    {
        $retval = [
            'class' => null,
            'function' => null,
            'file' => null,
            'line' => null,
        ];

        // we only want entries from the $frame array that we intend to return
        $parts = array_intersect_key($frame, $retval);
        $retval = array_merge($retval, $parts);

        // all done
        return $retval;
    }
}