---
currentSection: v1
currentItem: callers
pageflow_prev_url: CodeCaller.html
pageflow_prev_text: CodeCaller class
pageflow_next_url: FilterCodeCaller.html
pageflow_next_text: FilterCodeCaller class
---

# FilterBacktrace

<div class="callout warning" markdown="1">
Not yet in a tagged release.
</div>

## Description

`FilterBacktrace` is a data filter. You give it the output from `debug_backtrace()`, and a list of PHP classnames or partial namespaces to filter, and it will return the first entry in the debug trace that passes the filter.

`FilterBacktrace` was introduced to separate out the low-level filtering code from `FilterCodeCaller`.

## Public Interface

`FilterBacktrace` has the following public interface:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

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
    public function __invoke($backtrace, $partialsToFilterOut = []);

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @return array
     */
    public static function from($backtrace, $partialsToFilterOut = []);
}
```

## How To Use

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
$partials = [
    'Checks',
    'Requirements',
    FilterBacktrace::class,
];

// to use as an object
$filter = new FilterBacktrace;
$caller = $filter($trace, $partials);

// to call statically
$caller = FilterBacktrace::from($trace, $partials);
```

## Return Value

`FilterBacktrace` returns an array:

Key | Description
----|------------
class | A valid PHP class name, or NULL
function | The method on the class that called us, or NULL
file | The file were the PHP code was defined, or NULL
line | The line number in the file where the PHP code was defined, or NULL

You should expect some of the fields in the return array to be `NULL`:

* Any entry in the returned array can be `NULL`.
* One of `class`, `class` and `function`, or `file` will have a value.

## Notes

None at this time.

## See Also

* [`FilterCodeCaller` class](FilterCodeCaller.html)
