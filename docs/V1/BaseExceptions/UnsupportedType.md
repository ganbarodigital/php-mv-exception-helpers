---
currentSection: v1
currentItem: baseexceptions
pageflow_prev_url: ParameterisedException.html
pageflow_prev_text: ParameterisedException class
pageflow_next_url: UnsupportedValue.html
pageflow_next_text: UnsupportedValue class
---

# UnsupportedType

<div class="callout info" markdown="1">
Since v1.2016041701
</div>

## Description

`UnsupportedType` is an exception thrown when a function or method is passed a parameter that has the wrong data type.

## Public Interface

`UnsupportedType` has the following public interface:

```php
// our base class and interface(s)
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

// return types from our method(s)
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;

// how to import
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\UnsupportedType;

// public interface
class UnsupportedType
  extends ParameterisedException
  implements HttpRuntimeErrorException
{
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
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedType
     *         an fully-built exception for you to throw
     */
    public static function newFromVar($var, $fieldOrVarName, $typeFlags = null, $callerFilter = null);

    /**
     * which HTTP status code do we map onto?
     * @return UnexpectedErrorStatus
     */
    public function getHttpStatus();
}

```

## How To Use

You can use this exception class in one of two ways:

* throw new instances of it, or
* create your own `UnsupportedType` subclass in your own library, and throw those

We recommend the second approach. It allows you to ensure that all of your library's exceptions have a common base class / interface. This can make `try/catch` blocks easier to write.

## Notes

None at this time.

## See Also

* [`ParameterisedException` class](ParameterisedException.html)
* [`HttpRuntimeErrorException` interface](http://ganbarodigital.github.io/php-http-status/reference/Interfaces/HttpRuntimeErrorException.html)
