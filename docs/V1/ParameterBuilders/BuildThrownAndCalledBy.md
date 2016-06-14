---
currentSection: v1
currentItem: parameterbuilders
pageflow_prev_url: index.html
pageflow_prev_text: Parameter Builders
pageflow_next_url: BuildThrownBy.html
pageflow_next_text: BuildThrownBy class
---

# BuildThrownAndCalledBy

<div class="callout warning">
Not yet in a tagged release
</div>

## Description

Use `BuildThrownAndCalledBy` to build the `$formatString` and initial `$data` parameters for [`ParameterisedException::__construct()`](../BaseExceptions/ParameterisedException.html).

`BuildThrownAndCalledBy` will add details about:

- the code that is throwing the exception
- and the code that is calling that code

to both the `$formatString` and `$data`.

It's intended for use when applying robustness checks to a function or method's input parameters.

## Public Interface

`BuildThrownAndCalledBy` has the following public interface:

```php
// BuildThrownAndCalledBy lives in this namespace
namespace GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders;

class BuildThrownAndCalledBy
{
    /**
     * build an exception message and data, including details of
     * - who is throwing the exception
     * - who called the code that is throwing the exception
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

`BuildThrownAndCalledBy::from()` returns an array containing two values:

1. the modified `$formatString`
2. an array of named parameters to use as `ParameterisedException`'s `$data`

Use `list()` to unpack these values:

```php
list($newFormat, $data) = BuildThrownAndCalledBy::from($format, $backtrace);
```

### Modified Format String

The first parameter that you pass to `BuildThrownAndCalledBy::from()` is a [`vnsprintf()`](http://ganbarodigital.github.io/php-the-missing-bits/strings/vnsprintf.html) format string. `BuildThrownAndCalledBy::from()` takes your format string, and adds the following to the front of it:

    %calledByName$s: %thrownByName$s says

For example, if you called `BuildThrownAndCalledBy::from()` with the format string:

    '%fieldOrVarName$s' is less than zero

then `BuildThrownAndCalledBy::from()` will return a modified format string:

    %calledByName$s: %thrownByName$s says '%fieldOrVarName$s' is less than zero

Both `%thrownByName$s` and `%calledByName$s` are _named parameters_. Internally, `ParameterisedException::__construct()` calls [`vnsprintf()`](http://ganbarodigital.github.io/php-the-missing-bits/strings/vnsprintf.html) to expand your final format string. The values of `%thrownByName$s` and `%calledByName$s` are in the parameter data. This is the second value returned by `BuildThrownAndCalledBy::from()`.

### Parameter Data

The second parameter that you pass to `BuildThrownAndCalledBy::from()` is a PHP `debug_backtrace()` result. `BuildThrownAndCalledBy::from()` examines the backtrace that you provide, and returns an array of named parameters.

Named Parameter | Type | Description
----------------|------|------------
`thrownBy` | [`CodeCaller`](../Callers/CodeCaller.html) | full details of the code that is throwing the exception
`thrownByName` | string | human-readable description of the code that is throwing the exception
`calledBy` | [`CodeCaller`](../Callers/CodeCaller.html) | full details of the code that has called the code that is throwing the exception
`calledByName` | string | human-readable description of the code that has called the code that is throwing the exception

<div class="callout info" markdown="1">
#### Filtering The Backtrace

By default, the code that extracts `thrownBy` et al from the backtrace reports the first two complete entries in the backtrace. (We say 'complete' because [PHP stack traces are a little bit weird](http://ganbarodigital.github.io/php-the-missing-bits/traces/HowThePhpStackFrameWorks.html)).

You probably don't want any reusable robustness checkers appearing as `thrownBy` in your `$data` list. You've delegated the check to a separate piece of code to avoid repeating code in your library or app. It's the code that's calling your reusable checker that should appear as `thrownBy`.

You can avoid that by passing a list of fully-qualified classnames as the third parameter to `BuildThrownAndCalledBy::from()`. The code that analyses the backtrace will ignore any stack frames that contain any classes in this list.
</div>

## How To Use

### In A ParameterisedException

The steps are:

1. define your format string
1. create your backtrace
1. pass the format string and backtrace into `BuildThrownAndCalledBy::from()`
1. `BuildThrownAndCalledBy::from()` returns an array containing
   - your new format string
   - an array of named parameters and their values
1. add any additional named parameters to the list
1. call `ParameterisedException::__construct()` to finish building the exception

Here's what this looks like as code:

```php
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\
    ParameterisedException;
use GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\
    BuildThrownAndCalledBy;

class BadInputParameter extends ParameterisedException
{
    /**
     * create a new exception
     *
     * @param  mixed $var
     *         the variable that has the unsupported type
     * @param  string $fieldOrVarName
     *         the name of the input field, PHP variable or
     *         function/method parameter that contains $data
     * @param  int|null $typeFlags
     *         do we want any extra type information in the
     *         final exception message?
     * @param  array $callerFilter
     *         are there any namespaces we want to filter out
     *         of the call stack?
     * @return BadInputParameter
     *         an fully-built exception for you to throw
     */
    public static function newFromInputParameter(
        $var,
        $fieldOrVarName,
        $typeFlags = null,
        array $callerFilter = []
    )
    {
        // what is our format string?
        $formatString = "'%fieldOrVarName\$s' is a bad input";

        // who called us?
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // build the basic message and data
        list($formatString, $data) = BuildThrownAndCalledBy::from(
            $formatString, $backtrace
        );

        // add in what's unique to us
        $data['dataType'] = GetPrintableType::of($var, $typeFlags);
        $data['fieldOrVarName'] = $fieldOrVarName;

        // all done
        return new static($message, $data);
    }
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders\BuildThrownAndCalledBy
     [x] will prepend caller and thrower to message
     [x] will return caller and thrower in data block

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
