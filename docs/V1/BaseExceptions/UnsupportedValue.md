---
currentSection: v1
currentItem: baseexceptions
pageflow_prev_url: UnsupportedType.html
pageflow_prev_text: UnsupportedType class
---

# UnsupportedType

<div class="callout warning" markdown="1">
Not yet in a tagged release
</div>

## Description

`UnsupportedValue` is an exception thrown when a function or method is passed a parameter, and the parameter's value is not supported by the function or method.

## Public Interface

`UnsupportedValue` has the following public interface:

```php
// our base class and interface(s)
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\HttpStatus\Specifications\HttpStatusProvider;

// return types from our method(s)
use GanbaroDigital\HttpStatus\StatusValues\RequestError\UnprocessableEntityStatus;

// how to import
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\UnsupportedValue;

// public interface
class UnsupportedValue extends ParameterisedException implements HttpStatusProvider
{
    /**
     * create a new exception
     *
     * @param  mixed $var
     *         the variable that has the unsupported value
     * @param  string $fieldOrVarName
     *         the name of the input field, PHP variable or function/method
     *         parameter that contains $data
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return UnsupportedValue
     *         an fully-built exception for you to throw
     */
    public static function newFromVar($var, $fieldOrVarName, $typeFlags = null, $callerFilter = null);

    /**
     * which HTTP status code do we map onto?
     * @return UnprocessableEntityStatusProvider
     */
    public function getHttpStatus();
}

```

## How To Use

You can use this exception class in one of two ways:

* throw new instances of it, or
* create your own `UnsupportedValue` subclass in your own library, and throw those

We recommend the second approach. It allows you to ensure that all of your library's exceptions have a common base class / interface. This can make `try/catch` blocks easier to write.

## Notes

None at this time.

## See Also

* [`ParameterisedException` class](ParameterisedException.html)
* [`HttpStatusProvider` interface](http://ganbarodigital.github.io/php-http-status/httpStatusProviders.html)