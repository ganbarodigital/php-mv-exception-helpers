---
currentSection: v1
currentItem: baseexceptions
pageflow_prev_url: BaseExceptions.html
pageflow_prev_text: BaseExceptions
---

# ParameterisedException

<div class="callout warning" markdown="1">
Not yet in a tagged release.
</div>

## Description

`ParameterisedException` is a base class that builds the exception's message from a supplied format string and data set. The data set is stored in the exception. This can be logged and/or inspected to help with debugging and troubleshooting.

```php
// import
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;

// inherit from ParameterisedException
class MyException extends ParameterisedException
{
    // we recommend using static factory methods for creating exceptions
    public static function newFromData($data)
    {
        return new static($formatString, $data);
    }
}
```

## Public Interface

`ParameterisedException` has the following public interface:

```php
/**
 * ParameterisedException provides a data-oriented base class for your exceptions
 */
class ParameterisedException extends RuntimeException
{
    /**
     * our constructor
     *
     * @param string $formatString
     *        the sprintf() format string for our human-readable message
     * @param array $data
     *        the data required to sprintf() with $formatString
     * @param int $code
     *        the error code that you want to set (if any)
     */
    public function __construct($formatString, $data = [], $code = 0);

    /**
     * what was the data that we used to create the printable message?
     *
     * @return array
     */
    public function getMessageData();

    /**
     * what was the format string we used to create the printable message?
     *
     * @return string
     */
    public function getMessageFormat();
}
```

## Notes

### Named Parameters In The Format String

Internally, `ParameterisedException` uses [`vnsprintf()`](http://ganbarodigital.github.io/php-the-missing-bits/strings/vnsprintf.html) from _[PHP: The Missing Bits](http://ganbarodigital.github.io/php-the-missing-bits/)_. This allows you to use _named parameters_ in the format string:

```php
$format = "Value must be between %min\$s and %max\$s inclusively";
$data = [
    'min' => 10,
    'max' => 20,
];
throw new ParameterisedException($format, $data);
```
