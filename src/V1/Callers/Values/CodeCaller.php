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

namespace GanbaroDigital\ExceptionHelpers\V1\Callers\Values;

/**
 * CodeCaller holds details about who called a piece of PHP code.
 *
 * Use `FilterCodeCaller` to build a CodeCaller from the call stack.
 */
class CodeCaller
{
    /**
     * which class called us?
     *
     * @var string|null
     */
    private $class;

    /**
     * which function or method called us?
     *
     * @var string|null
     */
    private $function;

    /**
     * which file was the code in?
     *
     * @var string|null
     */
    private $file;

    /**
     * which line in $file was the code on?
     *
     * @var int|null
     */
    private $line;

    /**
     * what were the contents of the call stack at the time?
     *
     * @var array
     */
    private $stack;

    /**
     * constructor
     *
     * @param string|null $class
     *        which class called us?
     * @param string|null $function
     *        which function or method called us?
     * @param string|null $file
     *        which file was the calling code in?
     * @param int|null $line
     *        which line in $file was the calling code on?
     * @param array $stack
     *        what was the call stack at the time?
     */
    public function __construct($class, $function, $file, $line, $stack = [])
    {
        $this->class = $class;
        $this->function = $function;
        $this->file = $file;
        $this->line = $line;
        $this->stack = $stack;
    }

    /**
     * which class called us?
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * which function or method called us?
     *
     * @return string|null
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * which method called us?
     *
     * @return string|null
     */
    public function getMethod()
    {
        if ($this->class === null) {
            return null;
        }
        return $this->function;
    }

    /**
     * which file was the calling code defined in?
     *
     * @return string|null
     */
    public function getFilename()
    {
        return $this->file;
    }

    /**
     * which line in $this->getFile() was the calling code defined on?
     *
     * @return int|null
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * what were the contents of the call stack at the time?
     *
     * an empty array means that we weren't asked to save the call stack
     * (probably to save memory - the call stack can be large)
     *
     * @return array
     */
    public function getStack()
    {
        return $this->stack;
    }

    // =========================================================================
    //
    // HELPERS
    //
    // -------------------------------------------------------------------------

    /**
     * return our contents as a sensible, printable string
     *
     * @return string
     */
    public function getCaller()
    {
        // we have been called from a class, a function, or a PHP script
        if (isset($this->class)) {
            $retval = $this->class . '::' . $this->function;
        }
        else if (isset($this->function)) {
            $retval = $this->function;
        }
        else {
            $retval = $this->file;
        }

        // which line was the caller on?
        if (isset($this->line)) {
            $retval .= "@" . $this->line;
        }

        // all done
        return $retval;
    }
}
