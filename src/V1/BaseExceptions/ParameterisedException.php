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
     * our constructor
     *
     * @param string $formatString
     *        the printf() format string for our human-readable message
     * @param array $data
     *        the data required to printf() with $formatString
     * @param int $code
     *        the error code that you want to set (if any)
     */
    public function __construct($formatString, $data = [], $code = 0)
    {
        // remember our extra parameters
        $this->formatString = $formatString;
        $this->data = $data;

        // build the printable message
        $message = vsprintf($formatString, $data);
        parent::__construct($message, $code);
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
