---
currentSection: v1
currentItem: callers
pageflow_prev_url: CodeCaller.html
pageflow_prev_text: CodeCaller class
pageflow_next_url: FilterBacktraceForTwoCodeCallers.html
pageflow_next_text: FilterBacktraceForTwoCodeCallers class
---

# FilterBacktrace

<div class="callout info" markdown="1">
Since v1.2016041701
</div>

## Description

`FilterBacktrace` is a data filter. You give it the output from `debug_backtrace()`, and a list of PHP classnames or namespaces to filter, and it will return the first entry in the debug trace that passes the filter.

`FilterBacktrace` was introduced to separate out the low-level filtering code from `FilterCodeCaller`.

## Public Interface

`FilterBacktrace` has the following public interface:

```php
namespace GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

use GanbaroDigital\MissingBits\TraceInspectors\FilterBacktrace as BaseClass;

class FilterBacktrace extends BaseClass
{
    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $filterList
     *         a list of namespaces and classes to skip over
     * @param  int $index
     *         how far down the stack do we want to start looking from?
     * @return array
     */
    public function __invoke($backtrace, $filterList = [], $index = 1);

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $filterList
     *         a list of namespaces and classes to skip over
     * @param  int $index
     *         how far down the stack do we want to start looking from?
     * @return array
     */
    public static function from($backtrace, $filterList = [], $index = 1);
}
```

## How To Use

### Finding A Single Stack Frame

`FilterBacktrace` can be used as an object, or as a static class method.

```php
// import first
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// we need a list of namespaces to filter out
//
// this can be individual parts of namespaces, or whole classnames
$filterList = [
    'GanbaroDigital\Reflection\V1\Checks',
    'GanbaroDigital\Reflection\V1\Requirements',
    FilterBacktrace::class,
];

// to use as an object
$filter = new FilterBacktrace;
$caller = $filter($trace, $filterList);

// to call statically
$caller = FilterBacktrace::from($trace, $filterList);
```

### Finding Multiple Stack Frames

`debug_backtrace()` returns the stack trace as an array. You can start the search from anywhere in this array by passing a third parameter into this filter. This is especially useful if your exception wants to know who called the code that is trying to throw an exception:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// starts from the beginning of the stack trace
$caller1 = FilterBacktrace::from($trace);
// starts from just after where we found $caller1
$caller2 = FilterBacktrace::from($trace, [], $caller1['stackIndex'] + 1);
```

If you attempt to search beyond the end of the stack trace, `FilterBacktrace` will return the last stack frame.

## Return Value

`FilterBacktrace` returns an array:

Key | Description
----|------------
class | A valid PHP class name, or NULL
function | The method on the class that called us, or NULL
file | The file were the PHP code was defined, or NULL
line | The line number in the file where the PHP code was defined, or NULL
stackIndex | Where did we find these details in the backtrace array?

You should expect some of the fields in the return array to be `NULL`:

* Any entry in the returned array can be `NULL`.
* One of `class`, `class` and `function`, or `file` will have a value.

## Notes

None at this time.

## Changelog

### v1.2016052501

* `FilterBacktrace` now extends the [`FilterBacktrace`](http://ganbarodigital.github.io/php-the-missing-bits/traces/FilterBacktrace.html) from [PHP: The Missing Bits](http://ganbarodigital.github.io/php-the-missing-bits/).

  We didn't want projects depending upon `ganbarodigital/php-mv-exception-helpers` just because they wanted to add caller details to log messages. We've moved `FilterBacktrace` into [PHP: The Missing Bits](http://ganbarodigital.github.io/php-the-missing-bits/) where it probably always belonged.

* `FilterBacktrace` no longer filters out partial namespaces.

  This was a disaster waiting to happen. Filtering partial namespaces meant that we'd filter out your namespaces too - and that's a violation of the rule of no surprises. It's just too blunt an instrument. We've switched to filtering out whole namespaces only to make sure that we never filter out your namespaces when we meant to only filter out our namespaces.

### v1.2016052401

* the default value of `$index` parameter was changed to be `1`. The previous default was `0`.

  This is a bug fix. Frame #0 of a debug stack trace can contain partial information - a `class` and `function` with no matching `file` and `line` data. Because we can never complete that information (the `file` and `line` would have to be a frame #-1, which does not exist), there's absolutely no point in starting the filtering from frame #0.

## See Also

* [`FilterCodeCaller` class](FilterCodeCaller.html)
