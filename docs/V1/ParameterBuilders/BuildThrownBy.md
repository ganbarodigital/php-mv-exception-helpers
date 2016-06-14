---
currentSection: v1
currentItem: parameterbuilders
pageflow_prev_url: BuildThrownAndCalledBy.html
pageflow_prev_text: BuildThrownAndCalledBy class
---

# BuildThrownBy

<div class="callout info">
Since v1.2016061401
</div>

## Description

Use `BuildThrownBy` to build the `$formatString` and initial `$data` parameters for [`ParameterisedException::__construct()`](../BaseExceptions/ParameterisedException.html).

`BuildThrownBy` will add details about:

- the code that is throwing the exception

to both the `$formatString` and `$data`.

It's intended for use when throwing an exception about:

- a value has been created in a method, or
- a value that has been returned from a piece of code you have called

## Public Interface

`BuildThrownBy` has the following public interface:

```php
// BuildThrownBy lives in this namespace
namespace GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders;

class BuildThrownBy
{
    /**
     * build an exception message and data, including details of
     * - who is throwing the exception
     *
     * @param  string $formatString
     *         the message for your exception
     * @param  array $backtrace
     *         the output of debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
     * @param  array $callerFilter
     *         list of classes to ignore in the backtrace
     * @return array
     *         - [0] is the modified format string
     *         - [1] is the message data
     */
    public static function from(
        $formatString,
        array $backtrace,
        array $callerFilter = []
    );
}
```

## Return Values

`BuildThrownBy::from()` returns an array containing two values:

1. the modified `$formatString`
2. an array of named parameters to use as `ParameterisedException`'s `$data`

Use `list()` to unpack these values:

```php
list($newFormat, $data) = BuildThrownBy::from($format, $backtrace);
```

### Modified Format String

The first parameter that you pass to `BuildThrownBy::from()` is a [`vnsprintf()`](http://ganbarodigital.github.io/php-the-missing-bits/strings/vnsprintf.html) format string. `BuildThrownBy::from()` takes your format string, and adds the following to the front of it:

    %thrownByName$s:

For example, if you called `BuildThrownBy::from()` with the format string:

    '%fieldOrVarName$s' is less than zero

then `BuildThrownBy::from()` will return a modified format string:

    %thrownByName$s: '%fieldOrVarName$s' is less than zero

`%thrownByName$s` is a _named parameter_. Internally, `ParameterisedException::__construct()` calls [`vnsprintf()`](http://ganbarodigital.github.io/php-the-missing-bits/strings/vnsprintf.html) to expand your final format string. The value of `%thrownByName$s` is in the parameter data. This is the second value returned by `BuildThrownBy::from()`.

### Parameter Data

The second parameter that you pass to `BuildThrownBy::from()` is a PHP `debug_backtrace()` result. `BuildThrownBy::from()` examines the backtrace that you provide, and returns an array of named parameters.

Named Parameter | Type | Description
----------------|------|------------
`thrownBy` | [`CodeCaller`](../Callers/CodeCaller.html) | full details of the code that is throwing the exception
`thrownByName` | string | human-readable description of the code that is throwing the exception

<div class="callout info" markdown="1">
#### Filtering The Backtrace

By default, the code that extracts `thrownBy` et al from the backtrace reports the first two complete entries in the backtrace. (We say 'complete' because [PHP stack traces are a little bit weird](http://ganbarodigital.github.io/php-the-missing-bits/traces/HowThePhpStackFrameWorks.html)).

You probably don't want any reusable robustness checkers appearing as `thrownBy` in your `$data` list. You've delegated the check to a separate piece of code to avoid repeating code in your library or app. It's the code that's calling your reusable checker that should appear as `thrownBy`.

You can avoid that by passing a list of fully-qualified classnames as the third parameter to `BuildThrownBy::from()`. The code that analyses the backtrace will ignore any stack frames that contain any classes in this list.
</div>

## How To Use

### In A ParameterisedException

The steps are:

1. define your format string
1. create your backtrace
1. pass the format string and backtrace into `BuildThrownBy::from()`
1. `BuildThrownBy::from()` returns an array containing
   - your new format string
   - an array of named parameters and their values
1. add any additional named parameters to the list
1. call `ParameterisedException::__construct()` to finish building the exception

Here's what this looks like as code:

```php
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\
    ParameterisedException;
use GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\
    BuildThrownBy;

class BadResponseCode extends ParameterisedException
{
    /**
     * create a new exception
     *
     * @param  mixed $var
     *         the variable that has the unsupported type
     * @param  string $fieldOrVarName
     *         the name of the input field, PHP variable or
     *         function/method parameter that contains $data
     * @param  array $callerFilter
     *         are there any namespaces we want to filter out
     *         of the call stack?
     * @return BadResponseCode
     *         an fully-built exception for you to throw
     */
    public static function newFromResponseCode(
        $var,
        $fieldOrVarName,
        array $callerFilter = []
    )
    {
        // what is our format string?
        $formatString = "'%fieldOrVarName\$s' is a bad response code";

        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // build the basic message and data
        list($formatString, $data) = BuildThrownBy::from(
            $formatString, $backtrace
        );

        // add in what's unique to us
        $data['fieldOrVarName'] = $fieldOrVarName;

        // all done
        return new static($message, $data);
    }
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigitalTest\ExceptionHelpers\V1\ParameterBuilders\BuildThrownBy
     [x] will prepend thrower to message
     [x] will return thrower in data block

Class contracts are built from this class's unit tests.

<div class="callout success">
Future releases of this class will not break this contract.
</div>

<div class="callout info" markdown="1">
Future releases of this class may add to this contract. New additions may include:

* clarifying existing behaviour (e.g. stricter contract around input or return types)
* add new behaviours (e.g. extra class methods)
</div>

<div class="callout warning" markdown="1">
When you use this class, you can only rely on the behaviours documented by this contract.

If you:

* find other ways to use this class,
* or depend on behaviours that are not covered by a unit test,
* or depend on undocumented internal states of this class,

... your code may not work in the future.
</div>

## Notes

None at this time.

## See Also

* [`ParameterisedException`](../BaseExceptions/ParameterisedException.html) - the exception class that this helper builds information for
